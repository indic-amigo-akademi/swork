<?php

define('PROJECT_ROOT', realpath(__DIR__ . '/../'));
define('VIEW_PATH', realpath(PROJECT_ROOT . '/views'));
define('CACHE_PATH', realpath(PROJECT_ROOT . '/cache'));
define('CONTROLLER_PATH', realpath(PROJECT_ROOT . '/src/controller'));
define('MODEL_PATH', realpath(PROJECT_ROOT . '/src/model'));
// define('DATABASE_URL','http://localhost');

function project_include($path = '')
{
    return realpath(PROJECT_ROOT . '/' . $path);
}

function view_include($path = 'index.ptml')
{
    return realpath(VIEW_PATH . '/' . $path);
}

if (!file_exists(PROJECT_ROOT . '/cache')) {
    mkdir(PROJECT_ROOT . '/cache', 0755, true);
}

function asset($url = '')
{
    return "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}{$url}";
}

?>
