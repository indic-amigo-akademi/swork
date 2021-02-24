<?php
// ini_set('display_errors', 1);

// ERROR_REPORTING(E_ALL);

require_once '../src/config.php';
require_once '../src/helpers.php';
foreach (glob(CONTROLLER_PATH . '/*.php') as $filename) {
    require_once $filename;
}
foreach (glob(MODEL_PATH . '/*.php') as $filename) {
    require_once $filename;
}
require_once '../src/helpers/View.php';
require_once '../src/helpers/Template.php';
require_once '../src/helpers/Query.php';
require_once '../src/helpers/Parsedown.php';
require_once '../src/helpers/Err.php';

$_SERVER['PHP_AUTH_APP'] = [
    'database' => new Query(),
];

// $datetime = DateTime::createFromFormat('Y-m-d\TH:i:s+', '2013-02-13T08:35:34.195Z');
session_start();
// Default index page
router('GET', '^/$', function () {
    echo BoardController::index();
});

router('POST', '^/login$', function () {
    echo UserController::login();
});

router('POST', '^/register$', function () {
    echo UserController::register();
});

router('POST', '^/logout$', function () {
    echo UserController::logout();
});

// GET request to /users
router('GET', '^/users$', function () {
    $userView = new View('../views/user.html');
    $userView->name = 'Megha';
    echo $userView->render();
});

router('GET', '^/view$', function () {
    $userView = new View('board.html');
    $userView->name = 'Megha';
    echo $userView->render();
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

router('POST', '^/test$', function () {
    echo $_POST['username'];
    echo $_POST['password'];
});

router('GET', '^/test1$', function () {
    echo "
    <form method='post' action='http://localhost:8241/test'>
    <input name='username' type='text'  placeholder='Enter your username'/> <br>
    <input name='password' type='password'  placeholder='Enter your password'/> <br>
    <button type='submit'>Submit</button>
    </form>    
    ";
});

router('GET', '^/test2$', function () {
    $Parsedown = new Parsedown();
    echo $Parsedown->text('Hello _Parsedown_!');
});

router('GET', '^/test3$', function () {
    $add = lambda(function ($a, $b) {
        return $a + $b;
    });
    $add1 = $add(5);
    echo $add1(7); // 3
});

router('GET', '^/404', function () {
    echo Err::view();
});

router('GET', '^/500', function () {
    echo Err::view(500, 'Internal Server Error');
});

header('Location:http://localhost:8241/404');

?>
