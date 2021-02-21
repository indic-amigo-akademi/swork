<?php

define('PROJECT_ROOT', realpath(__DIR__ . '/../'));

function project_include($path = '')
{
    return realpath(PROJECT_ROOT . '/' . $path);
}

?>
