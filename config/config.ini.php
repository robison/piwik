<?php exit; ?>

mysql://b1b5ef915e1b6f:5e89decc@us-cdbr-iron-east-02.cleardb.net/heroku_51c3173c53f5f4b?reconnect=true
[database]
host = us-cdbr-iron-east-02.cleardb.net
username = b1b5ef915e1b6f
password = 5e89decc
dbname = heroku_51c3173c53f5f4b
tables_prefix = piwik_
port = 3306
adapter = PDO\MYSQL
type = InnoDB
schema = Mysql

[Mbstring]
mbstring.func_overload = 0

session_save_handler = dbtable
