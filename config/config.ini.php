<?php
    $db = parse_url(getenv('CLEARDB_DATABASE_URL'));

    define('DBHOST', $db['host']);
    define('DBUSER', $db['user']);
    define('DBPASS', $db['pass']);
    define('DBNAME', substr($db["path"], 1));
?>
[database]
host = $DBHOST
username = $DBUSER
password = $DBPASS
dbname = $DBNAME
port = 3306
adapter = PDO\MYSQL
type = InnoDB
schema = Mysql

[General]
session_save_handler = dbtable

Plugins[] = "VisitorGenerator"

