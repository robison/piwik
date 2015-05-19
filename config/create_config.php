<?php

    $db = parse_url(getenv('CLEARDB_DATABASE_URL'));

    echo "[database]" . "\n";
    echo "host = " . $db['host'] . "\n";
    echo "username = " . $db['user'] . "\n";
    echo "password = " . $db['pass'] . "\n";
    echo "dbname = " . substr($db['path'], 1) . "\n";
    echo "port = " . $db['port'] . "\n";
?>
charset = "utf8"

