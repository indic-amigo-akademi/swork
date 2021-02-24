<?php

foreach (glob('migrations/*.php') as $filename) {
    require_once $filename;
}

?>
