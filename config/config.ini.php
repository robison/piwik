<?php
    $db = parse_url(getenv('CLEARDB_DATABASE_URL'));

    $database_host = $db['host'];
    $database_port = $db['port'];
    $database_name = substr($db["path"], 1);
    $database_user = $db['user'];
    $database_pass = $db['pass'];
    echo "[database]" . "\n";
    echo "host = " . $database_host . "\n";
    echo "username = " . $database_user . "\n";
    echo "password = " . $database_pass . "\n";
    echo "dbname = " . $database_name . "\n";
    echo "port = " . $database_port . "\n";
?>
adapter = PDO\MYSQL
type = InnoDB
schema = Mysql

[General]
session_save_handler = dbtable

Plugins[] = "VisitorGenerator"

