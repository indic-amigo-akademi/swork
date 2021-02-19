<?php

if (!function_exists('router')) {
    function router($httpMethods, $route, $callback, $exit = true)
    {
        static $path = null;
        if ($path === null) {
            $path = parse_url($_SERVER['REQUEST_URI'])['path'];
            $scriptName = dirname(dirname($_SERVER['SCRIPT_NAME']));
            $scriptName = str_replace('\\', '/', $scriptName);
            $len = strlen($scriptName);
            if ($len > 0 && $scriptName !== '/') {
                $path = substr($path, $len);
            }
        }
        if (!in_array($_SERVER['REQUEST_METHOD'], (array) $httpMethods)) {
            return;
        }
        $matches = null;
        $regex = '/' . str_replace('/', '\/', $route) . '/';
        if (!preg_match_all($regex, $path, $matches)) {
            return;
        }
        if (empty($matches)) {
            $callback();
        } else {
            $params = [];
            foreach ($matches as $k => $v) {
                if (!is_numeric($k) && !isset($v[1])) {
                    $params[$k] = $v[0];
                }
            }
            $callback($params);
        }
        if ($exit) {
            exit();
        }
    }
}

if (!function_exists('generateString')) {
    function generateString(
        $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        $strength = 16
    ) {
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }
}

?>