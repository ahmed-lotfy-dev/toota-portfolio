<?php

echo "<h1>Environment Debug</h1>";

echo "<h2>PHP Extensions</h2>";
$extensions = get_loaded_extensions();
echo in_array('zip', $extensions) ? "<p style='color:green'>✅ zip extension loaded</p>" : "<p style='color:red'>❌ zip extension MISSING</p>";
echo in_array('pdo_mysql', $extensions) ? "<p style='color:green'>✅ pdo_mysql loaded</p>" : "<p style='color:grey'>ℹ️ pdo_mysql not loaded</p>";
echo in_array('pdo_pgsql', $extensions) ? "<p style='color:green'>✅ pdo_pgsql loaded</p>" : "<p style='color:grey'>ℹ️ pdo_pgsql not loaded</p>";

echo "<h2>System Binaries</h2>";
function check_binary($name)
{
  $path = shell_exec("which $name 2>&1");
  if ($path && !str_contains($path, 'not found')) {
    echo "<p style='color:green'>✅ $name found at: " . htmlspecialchars(trim($path)) . "</p>";
  } else {
    echo "<p style='color:red'>❌ $name NOT found in PATH</p>";
  }
}

check_binary('zip');
check_binary('unzip');
check_binary('mysqldump');
check_binary('pg_dump');

echo "<h2>PATH Variable</h2>";
echo "<pre>" . htmlspecialchars(getenv('PATH')) . "</pre>";

echo "<h2>Nix Store Check</h2>";
if (file_exists('/nix/store')) {
  echo "<p style='color:green'>✅ /nix/store exists</p>";
  $pgDump = shell_exec("find /nix/store -name pg_dump -type f 2>/dev/null | head -1");
  if ($pgDump) {
    echo "<p style='color:green'>✅ pg_dump found at: " . htmlspecialchars(trim($pgDump)) . "</p>";
  }
} else {
  echo "<p style='color:grey'>ℹ️ /nix/store not found</p>";
}
