[General]
trusted_hosts[] = "hidden-harbor-4647.herokuapp.com"
session_save_handler = dbtable

[database]
host = "us-cdbr-iron-east-02.cleardb.net"
username = "b1b5ef915e1b6f"
password = "5e89decc"
dbname = "heroku_51c3173c53f5f4b"
tables_prefix = "piwik_"
port = 3306
adapter = PDO\MYSQL
type = InnoDB
schema = Mysql

[superuser]
login = "admin"
password = "beerpong"
email = "robbie@weebly.com"

Plugins[] = "VisitorGenerator"

