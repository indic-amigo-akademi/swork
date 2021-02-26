<?php

include 'src/helpers/Query.php';

$db = new Query();

foreach (glob('migrations/*.php') as $filename) {
    require_once $filename;
}

$db->close();

?>
