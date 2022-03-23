<?php
include 'src/helpers/Dotenv.php';
include 'src/helpers/Query.php';

(new Dotenv('.env.local'))->load();

// loading .env files

$db = new Query(getenv('DB_HOSTNAME'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));

foreach (glob('migrations/*.php') as $filename) {
    require_once $filename;
}

$db->close();
