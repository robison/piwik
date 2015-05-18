[Mbstring]
mbstring.func_overload = 0

[database]
host = $database_host
username =$database_user
password = $database_password
dbname = $database_name
tables_prefix = piwik_
port = 3306
adapter = PDO\MYSQL
type = InnoDB
schema = Mysql

; if charset is set to utf8, Piwik will ensure that it is storing its data using UTF8 charset.
; it will add a sql query SET at each page view.
; Piwik should work correctly without this setting.
;charset = utf8

session_save_handler = dbtable
