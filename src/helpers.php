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

class lambda
{
    private $f;
    private $args;
    private $count;
    public function __construct($f, $args = [])
    {
        if ($f instanceof lambda) {
            $this->f = $f->f;
            $this->count = $f->count;
            $this->args = array_merge($f->args, $args);
        }
        else {
            $this->f = $f;
            $this->count = count((new ReflectionFunction($f))->getParameters());
            $this->args = $args;
        }
    }

    public function __invoke()
    {
        if (count($this->args) + func_num_args() < $this->count) {
            return new lambda($this, func_get_args());
        }
        else {
            $args = array_merge($this->args, func_get_args());
            $r = call_user_func_array($this->f, array_splice($args, 0, $this->count));
            return is_callable($r) ? call_user_func(new lambda($r, $args)) : $r;
        }
    }
}
function lambda($f)
{
    return new lambda($f);
}

?>
