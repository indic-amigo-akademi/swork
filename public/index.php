<?php
require_once '../src/config.php';
require_once '../src/helpers.php';
foreach(glob(CONTROLLER_PATH.'/*.php') as $filename){
    require_once $filename;
}
foreach(glob(MODEL_PATH.'/*.php') as $filename){
    require_once $filename;
}
require_once '../src/helpers/View.php';
require_once '../src/helpers/Template.php';

// Default index page
router('GET', '^/$', function () {
    $boardView = new View('user.html');
    $boardView->name = 'Megha';
    echo $boardView->render();
});

// GET request to /users
router('GET', '^/users$', function () {
    echo '<a href="users/1000">Show user: 1000</a>';
});

// With named parameters
router('GET', '^/users/(?<id>\d+)$', function ($params) {
    echo 'You selected User-ID: ';
    var_dump($params);
});

// POST request to /users
router('POST', '^/users$', function () {
    header('Content-Type: application/json');
    $json = json_decode(file_get_contents('php://input'), true);
    echo json_encode(['result' => 1]);
});

// With named parameters
router('GET', '^/board/(?<id>\d+)$', function ($params) {
    echo 'You selected Board: ';
    var_dump($params);
});

router('GET', '^/entry', function () {
    Template::view('about.ptml', [
        'title' => 'About Page',
        'name' => 'Megha',
        'colors' => ['red', 'blue', 'green'],
    ]);
});


router('GET', '^/test', function () {
    $add = lambda(function($a, $b) { 
        return $a + $b; 
    });
    $add1 = $add(5);
    echo $add1(7); // 3
    
});

header('HTTP/1.0 404 Not Found');
echo '404 Not Found';

?>
