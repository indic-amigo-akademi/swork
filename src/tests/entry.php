<?php
ini_set('display_errors', 1);
ERROR_REPORTING(E_ALL);

// Template::view('index.ptml', [
//     'title' => 'Home Page',
//     'name' => 'Megha',
//     'colors' => ['red', 'blue', 'green'],
// ]);

Template::view('about.ptml', [
    'title' => 'About Page',
    'name' => 'Megha',
    'colors' => ['red', 'blue', 'green'],
]);
?>
