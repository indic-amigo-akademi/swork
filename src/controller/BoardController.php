<?php

class BoardController
{
    public static function index()
    {
        // if ($_SERVER['PHP_AUTH_APP']['user']->getUsername() == '') {
        //     return 'Not Logged In';
        // }
        return Template::view('index.ptml', [
            'title' => 'Home Page',
            'user' => isset($_SESSION['PHP_AUTH_USER'])
                ? $_SESSION['PHP_AUTH_USER']
                : null,
            'colors' => ['red', 'blue', 'green'],
        ]);
    }
}

?>
