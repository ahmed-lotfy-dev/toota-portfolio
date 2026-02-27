import { Pool } from "pg";
import * as schema from "./schema";
import { drizzle } from "drizzle-orm/node-postgres";

const rawConnectionString = process.env.DATABASE_URL || "";
const isLocal = rawConnectionString.includes("localhost") || rawConnectionString.includes("127.0.0.1");

const pool = new Pool({
  connectionString: rawConnectionString,
  ssl: isLocal ? false : { rejectUnauthorized: false },
});

export const db = drizzle(pool, { schema });
