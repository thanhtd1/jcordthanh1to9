
[software]
	postgresql 10
	apache
	apache-php

[db]
table and seq
psql -f lib/db/sql/mk_system_seq.sql
psql -f lib/db/sql/mk_t_dat_group.sql


[php]
module config modify
vi .config.php
--------------------------------------------------
define("TOP_DIR",       "/var/www/html/lab1/");
define("LOGS_DIR",      "/var/www/html/lab1/log/");
--------------------------------------------------

vi lib/common/define.php
--------------------------------------------------
        const DB_SERVER = "10.10.31.150";
        const DB_NAME = "dbname";
        const DB_USER = "userid";
        const DB_PASS = "passwd";
        const DB_PORT = "5432";
--------------------------------------------------

chmod 777 log
chmod 777 tmp
