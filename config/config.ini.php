<?php
    $db = parse_url(getenv('CLEARDB_DATABASE_URL'));

    $database_pass = $db['pass'];
    echo "[database]" . "\n";
    echo "host = " . $db['host'] . "\n";
    echo "username = " . $db['user'] . "\n";
    echo "password = " . $db['pass'] . "\n";
    echo "dbname = " . substr($db["path"], 1) . "\n";
?>
port = 3306
adapter = PDO\MYSQL
type = InnoDB
schema = Mysql

[General]
session_save_handler = dbtable

Plugins[] = "VisitorGenerator"

