<?php

define('PROJECT_ROOT', realpath(__DIR__ . '/../'));
define('VIEW_PATH', realpath(PROJECT_ROOT . '/views'));
define('CACHE_PATH', realpath(PROJECT_ROOT . '/cache'));
define('CONTROLLER_PATH', realpath(PROJECT_ROOT . '/src/controller'));
define('MODEL_PATH', realpath(PROJECT_ROOT . '/src/model'));

function project_include($path = '')
{
    return realpath(PROJECT_ROOT . '/' . $path);
}

function view_include($path = 'index.ptml')
{
    return realpath(VIEW_PATH . '/' . $path);
}

?>
