import { pgTable, text, timestamp, boolean, integer, bigint, varchar, decimal, bigserial } from "drizzle-orm/pg-core";
import { relations } from "drizzle-orm";

// Better Auth Tables
export const user = pgTable("user", {
  id: text("id").primaryKey(),
  name: text("name").notNull(),
  email: text("email").notNull().unique(),
  emailVerified: boolean("email_verified").notNull(),
  twoFactorEnabled: boolean("two_factor_enabled").notNull().default(false),
  image: text("image"),
  createdAt: timestamp("created_at").notNull(),
  updatedAt: timestamp("updated_at").notNull(),
});

export const session = pgTable("session", {
  id: text("id").primaryKey(),
  expiresAt: timestamp("expires_at").notNull(),
  token: text("token").notNull().unique(),
  createdAt: timestamp("created_at").notNull(),
  updatedAt: timestamp("updated_at").notNull(),
  ipAddress: text("ip_address"),
  userAgent: text("user_agent"),
  userId: text("user_id").notNull().references(() => user.id),
});

export const account = pgTable("account", {
  id: text("id").primaryKey(),
  accountId: text("account_id").notNull(),
  providerId: text("provider_id").notNull(),
  userId: text("user_id").notNull().references(() => user.id),
  accessToken: text("access_token"),
  refreshToken: text("refresh_token"),
  idToken: text("id_token"),
  accessTokenExpiresAt: timestamp("access_token_expires_at"),
  refreshTokenExpiresAt: timestamp("refresh_token_expires_at"),
  scope: text("scope"),
  password: text("password"),
  createdAt: timestamp("created_at").notNull(),
  updatedAt: timestamp("updated_at").notNull(),
});

export const verification = pgTable("verification", {
  id: text("id").primaryKey(),
  identifier: text("identifier").notNull(),
  value: text("value").notNull(),
  expiresAt: timestamp("expires_at").notNull(),
  createdAt: timestamp("created_at"),
  updatedAt: timestamp("updated_at"),
});

// Portfolio Tables
export const categories = pgTable("categories", {
  id: bigserial("id", { mode: "number" }).primaryKey(),
  name: varchar("name", { length: 255 }).notNull(),
  slug: varchar("slug", { length: 255 }).notNull().unique(),
  description: text("description"),
  order: integer("order").default(0),
  createdAt: timestamp("created_at"),
  updatedAt: timestamp("updated_at"),
});

export const projects = pgTable("projects", {
  id: bigserial("id", { mode: "number" }).primaryKey(),
  categoryId: bigint("category_id", { mode: "number" }).references(() => categories.id, { onDelete: "cascade" }).notNull(),
  title: varchar("title", { length: 255 }).notNull(),
  slug: varchar("slug", { length: 255 }).notNull().unique(),
  description: text("description"),
  isPublished: boolean("is_published").default(true),
  isFeatured: boolean("is_featured").default(false),
  order: integer("order").default(0),
  createdAt: timestamp("created_at"),
  updatedAt: timestamp("updated_at"),
});

export const projectImages = pgTable("project_images", {
  id: bigserial("id", { mode: "number" }).primaryKey(),
  projectId: bigint("project_id", { mode: "number" }).references(() => projects.id, { onDelete: "cascade" }).notNull(),
  imagePath: varchar("image_path", { length: 255 }).notNull(),
  caption: varchar("caption", { length: 255 }),
  isPrimary: boolean("is_primary").default(false),
  order: integer("order").default(0),
  createdAt: timestamp("created_at"),
  updatedAt: timestamp("updated_at"),
});

export const testimonials = pgTable("testimonials", {
  id: bigserial("id", { mode: "number" }).primaryKey(),
  clientName: varchar("name", { length: 255 }).notNull(),
  clientTitle: varchar("title", { length: 255 }),
  content: text("body").notNull(),
  isPublished: boolean("is_published").default(false),
  createdAt: timestamp("created_at"),
  updatedAt: timestamp("updated_at"),
});

export const heroImages = pgTable("hero_images", {
  id: bigserial("id", { mode: "number" }).primaryKey(),
  imagePath: varchar("image_path", { length: 255 }).notNull(),
  label: varchar("label", { length: 50 }).notNull(),
  position: integer("position").notNull().unique(),
  width: integer("width"),
  height: integer("height"),
  aspectRatio: decimal("aspect_ratio", { precision: 8, scale: 4 }),
  ratioMode: varchar("ratio_mode", { length: 255 }).default("original"),
  createdAt: timestamp("created_at"),
  updatedAt: timestamp("updated_at"),
});

export const subscribers = pgTable("subscribers", {
  id: bigserial("id", { mode: "number" }).primaryKey(),
  email: varchar("email", { length: 255 }).notNull().unique(),
  createdAt: timestamp("created_at"),
  updatedAt: timestamp("updated_at"),
});

export const twoFactor = pgTable("twoFactor", {
  id: text("id").primaryKey(),
  secret: text("secret").notNull(),
  backupCodes: text("backup_codes").notNull(),
  userId: text("user_id").notNull().references(() => user.id, { onDelete: "cascade" }),
});

export const backupSettings = pgTable("backup_settings", {
  id: bigserial("id", { mode: "number" }).primaryKey(),
  enabled: boolean("enabled").notNull().default(false),
  frequency: varchar("frequency", { length: 20 }).notNull().default("weekly"),
  time: varchar("time", { length: 8 }).notNull().default("01:00"),
  keepDaily: integer("keep_daily").notNull().default(7),
  keepWeekly: integer("keep_weekly").notNull().default(4),
  keepMonthly: integer("keep_monthly").notNull().default(3),
  updatedByUserId: text("updated_by_user_id").references(() => user.id, { onDelete: "set null" }),
  createdAt: timestamp("created_at"),
  updatedAt: timestamp("updated_at"),
});

// Relations
export const userRelations = relations(user, ({ many }) => ({
  sessions: many(session),
  accounts: many(account),
}));

export const sessionRelations = relations(session, ({ one }) => ({
  user: one(user, {
    fields: [session.userId],
    references: [user.id],
  }),
}));

export const accountRelations = relations(account, ({ one }) => ({
  user: one(user, {
    fields: [account.userId],
    references: [user.id],
  }),
}));

export const projectRelations = relations(projects, ({ one, many }) => ({
  category: one(categories, {
    fields: [projects.categoryId],
    references: [categories.id],
  }),
  images: many(projectImages),
}));

export const categoriesRelations = relations(categories, ({ many }) => ({
  projects: many(projects),
}));

export const projectImagesRelations = relations(projectImages, ({ one }) => ({
  project: one(projects, {
    fields: [projectImages.projectId],
    references: [projects.id],
  }),
}));
