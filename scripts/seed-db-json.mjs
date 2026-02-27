#!/usr/bin/env node
import { readFile } from "node:fs/promises";
import { resolve } from "node:path";
import { Pool } from "pg";

function getDatabaseUrl() {
  const direct = process.env.DATABASE_URL;
  if (!direct) {
    throw new Error("DATABASE_URL is not set. Export it before running this script.");
  }
  return direct;
}

function buildSslConfig(databaseUrl) {
  try {
    const url = new URL(databaseUrl);
    const sslmode = url.searchParams.get("sslmode");
    const isLocal = ["localhost", "127.0.0.1"].includes(url.hostname);

    if (isLocal || sslmode === "disable") {
      return undefined;
    }

    return { rejectUnauthorized: false };
  } catch {
    return { rejectUnauthorized: false };
  }
}

function quoteIdentifier(name) {
  return `"${String(name).replace(/"/g, '""')}"`;
}

async function getExistingTables(client) {
  const result = await client.query(`
    SELECT table_name
    FROM information_schema.tables
    WHERE table_schema = 'public' AND table_type = 'BASE TABLE'
  `);
  return new Set(result.rows.map((row) => row.table_name));
}

async function getFkEdges(client) {
  const result = await client.query(`
    SELECT
      tc.table_name AS child_table,
      ccu.table_name AS parent_table
    FROM information_schema.table_constraints tc
    JOIN information_schema.key_column_usage kcu
      ON tc.constraint_name = kcu.constraint_name
      AND tc.table_schema = kcu.table_schema
    JOIN information_schema.constraint_column_usage ccu
      ON ccu.constraint_name = tc.constraint_name
      AND ccu.table_schema = tc.table_schema
    WHERE tc.constraint_type = 'FOREIGN KEY'
      AND tc.table_schema = 'public'
  `);

  return result.rows.map((row) => ({
    child: row.child_table,
    parent: row.parent_table,
  }));
}

function topoSortTables(tableNames, edges) {
  const nodes = new Set(tableNames);
  const indegree = new Map();
  const graph = new Map();

  for (const table of nodes) {
    indegree.set(table, 0);
    graph.set(table, []);
  }

  for (const edge of edges) {
    if (!nodes.has(edge.child) || !nodes.has(edge.parent)) {
      continue;
    }
    graph.get(edge.parent).push(edge.child);
    indegree.set(edge.child, (indegree.get(edge.child) || 0) + 1);
  }

  const queue = [];
  for (const [table, degree] of indegree.entries()) {
    if (degree === 0) queue.push(table);
  }

  const sorted = [];
  while (queue.length > 0) {
    const current = queue.shift();
    sorted.push(current);

    for (const neighbor of graph.get(current) || []) {
      const nextDegree = (indegree.get(neighbor) || 0) - 1;
      indegree.set(neighbor, nextDegree);
      if (nextDegree === 0) {
        queue.push(neighbor);
      }
    }
  }

  if (sorted.length === nodes.size) {
    return sorted;
  }

  // Fallback for cycles or unexpected metadata.
  return tableNames;
}

async function insertRows(client, tableName, rows) {
  if (!rows || rows.length === 0) return;

  const columns = Object.keys(rows[0]);
  if (columns.length === 0) return;

  const columnList = columns.map(quoteIdentifier).join(", ");

  for (const row of rows) {
    const values = columns.map((column) => row[column]);
    const placeholders = values.map((_, index) => `$${index + 1}`).join(", ");
    const sql = `INSERT INTO ${quoteIdentifier(tableName)} (${columnList}) VALUES (${placeholders})`;
    await client.query(sql, values);
  }
}

async function main() {
  const inputPathArg = process.argv[2];
  if (!inputPathArg) {
    throw new Error("Usage: node scripts/seed-db-json.mjs <path-to-export.json>");
  }

  const inputPath = resolve(inputPathArg);
  const raw = await readFile(inputPath, "utf8");
  const parsed = JSON.parse(raw);

  if (!parsed || typeof parsed !== "object" || !parsed.tables || typeof parsed.tables !== "object") {
    throw new Error("Invalid backup format. Expected an object with a 'tables' field.");
  }

  const databaseUrl = getDatabaseUrl();
  const pool = new Pool({
    connectionString: databaseUrl,
    ssl: buildSslConfig(databaseUrl),
  });

  try {
    const client = await pool.connect();
    try {
      const allBackupTables = Object.keys(parsed.tables);
      const existingTables = await getExistingTables(client);
      const targetTables = allBackupTables.filter((table) => existingTables.has(table));

      const fkEdges = await getFkEdges(client);
      const orderedTables = topoSortTables(targetTables, fkEdges);

      await client.query("BEGIN");

      if (targetTables.length > 0) {
        const truncateList = targetTables.map(quoteIdentifier).join(", ");
        await client.query(`TRUNCATE TABLE ${truncateList} RESTART IDENTITY CASCADE`);
      }

      for (const tableName of orderedTables) {
        await insertRows(client, tableName, parsed.tables[tableName]);
      }

      await client.query("COMMIT");

      const skipped = allBackupTables.filter((table) => !existingTables.has(table));
      const restoredRows = orderedTables.reduce((sum, table) => {
        const rows = parsed.tables[table];
        return sum + (Array.isArray(rows) ? rows.length : 0);
      }, 0);

      console.log(`Restore complete. Restored ${restoredRows} rows across ${orderedTables.length} tables.`);
      if (skipped.length > 0) {
        console.log(`Skipped missing tables in current schema: ${skipped.join(", ")}`);
      }
    } catch (error) {
      await client.query("ROLLBACK");
      throw error;
    } finally {
      client.release();
    }
  } finally {
    await pool.end();
  }
}

main().catch((error) => {
  console.error("Restore failed:", error?.message || error);
  process.exit(1);
});
