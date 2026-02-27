#!/usr/bin/env node
import { mkdir, writeFile } from "node:fs/promises";
import { dirname, resolve } from "node:path";
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

function isoStamp() {
  return new Date().toISOString().replace(/[:.]/g, "-");
}

function quoteIdentifier(name) {
  return `"${String(name).replace(/"/g, '""')}"`;
}

async function main() {
  const outputArg = process.argv[2];
  const defaultPath = `backups/local/db-export-${isoStamp()}.json`;
  const outputPath = resolve(outputArg ?? defaultPath);

  const databaseUrl = getDatabaseUrl();
  const pool = new Pool({
    connectionString: databaseUrl,
    ssl: buildSslConfig(databaseUrl),
  });

  try {
    const client = await pool.connect();
    try {
      const tablesResult = await client.query(`
        SELECT table_name
        FROM information_schema.tables
        WHERE table_schema = 'public' AND table_type = 'BASE TABLE'
        ORDER BY table_name ASC
      `);

      const tableNames = tablesResult.rows.map((row) => row.table_name);
      const tables = {};
      const counts = {};

      for (const tableName of tableNames) {
        const query = `SELECT * FROM ${quoteIdentifier(tableName)}`;
        const result = await client.query(query);
        tables[tableName] = result.rows;
        counts[tableName] = result.rowCount;
      }

      const payload = {
        meta: {
          exportedAt: new Date().toISOString(),
          databaseHost: (() => {
            try {
              return new URL(databaseUrl).host;
            } catch {
              return "unknown";
            }
          })(),
          tableCount: tableNames.length,
          rowCounts: counts,
        },
        tables,
      };

      await mkdir(dirname(outputPath), { recursive: true });
      await writeFile(outputPath, JSON.stringify(payload, null, 2), "utf8");

      const totalRows = Object.values(counts).reduce((sum, value) => sum + Number(value || 0), 0);
      console.log(`Exported ${tableNames.length} tables and ${totalRows} rows.`);
      console.log(`Backup file: ${outputPath}`);
    } finally {
      client.release();
    }
  } finally {
    await pool.end();
  }
}

main().catch((error) => {
  console.error("Backup export failed:", error?.message || error);
  process.exit(1);
});
