ALTER TABLE "user"
ADD COLUMN IF NOT EXISTS "two_factor_enabled" boolean DEFAULT false NOT NULL;

CREATE TABLE IF NOT EXISTS "twoFactor" (
  "id" text PRIMARY KEY NOT NULL,
  "secret" text NOT NULL,
  "backup_codes" text NOT NULL,
  "user_id" text NOT NULL
);

DO $$ BEGIN
 ALTER TABLE "twoFactor" ADD CONSTRAINT "twoFactor_user_id_user_id_fk"
 FOREIGN KEY ("user_id") REFERENCES "public"."user"("id") ON DELETE cascade ON UPDATE no action;
EXCEPTION
 WHEN duplicate_object THEN null;
END $$;

CREATE TABLE IF NOT EXISTS "backup_settings" (
  "id" bigserial PRIMARY KEY NOT NULL,
  "enabled" boolean DEFAULT false NOT NULL,
  "frequency" varchar(20) DEFAULT 'weekly' NOT NULL,
  "time" varchar(8) DEFAULT '01:00' NOT NULL,
  "keep_daily" integer DEFAULT 7 NOT NULL,
  "keep_weekly" integer DEFAULT 4 NOT NULL,
  "keep_monthly" integer DEFAULT 3 NOT NULL,
  "updated_by_user_id" text,
  "created_at" timestamp,
  "updated_at" timestamp
);

DO $$ BEGIN
 ALTER TABLE "backup_settings" ADD CONSTRAINT "backup_settings_updated_by_user_id_user_id_fk"
 FOREIGN KEY ("updated_by_user_id") REFERENCES "public"."user"("id") ON DELETE set null ON UPDATE no action;
EXCEPTION
 WHEN duplicate_object THEN null;
END $$;
