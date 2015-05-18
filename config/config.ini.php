<?php
$db = parse_url(getenv("CLEARDB_DATABASE_URL"));

echo '[database]';
echo 'host = ' . $db["host"] . "\n";
echo 'username = ' . $db["username"] . "\n";
echo 'password = ' . $db["password"] . "\n";
echo 'port = ' . $db["port"] . "\n";
echo 'dbname = ' . substr($db["path"], 1) . "\n";
echo 'tables_prefix = piwik_' . "\n";
echo 'adapter = PDO\MYSQL' . "\n";
echo 'type = InnoDB' . "\n";
echo 'schema = Mysql' . "\n";
?>

[Mbstring]
mbstring.func_overload = 0

session_save_handler = dbtable
