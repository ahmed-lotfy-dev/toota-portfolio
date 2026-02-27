import test from "node:test";
import assert from "node:assert/strict";
import { checkRateLimit } from "../src/lib/rate-limit.ts";

test("allows first request within window", () => {
  const result = checkRateLimit(`test-allow-${Date.now()}`, 2, 5_000);
  assert.equal(result.allowed, true);
  assert.equal(result.remaining, 1);
});

test("blocks requests after limit is reached", () => {
  const key = `test-block-${Date.now()}`;
  const first = checkRateLimit(key, 1, 5_000);
  const second = checkRateLimit(key, 1, 5_000);

  assert.equal(first.allowed, true);
  assert.equal(second.allowed, false);
  assert.equal(second.remaining, 0);
});
