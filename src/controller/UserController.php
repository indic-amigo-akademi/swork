<?php

class UserController
{
    public static function index()
    {
    }

    public static function login()
    {
        return $_POST['username'] . '<br>' . $_POST['password'];
    }

    public static function register()
    {
    }

    public static function logout()
    {
    }
}

?>
