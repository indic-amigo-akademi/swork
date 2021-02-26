<?php

class UserController
{
    public static function index()
    {
        $db = $_SERVER['PHP_AUTH_APP']['database'];

        if (isset($_SESSION['PHP_AUTH_USER'])) {
            $id = $_SESSION['PHP_AUTH_USER']->getId();

            $plans = $db->findAllBy('plans', [
                'author' => $id,
            ]);

            $plans = json_decode(json_encode($plans));

            return Template::view('user.ptml', [
                'title' => "Dashboard | {$_SESSION['PHP_AUTH_USER']->getUsername()}",
                'user' => $_SESSION['PHP_AUTH_USER'],
                'plans' => $plans,
            ]);
        }

        return Template::view('user.ptml', [
            'title' => 'Home Page',
            'user' => null,
        ]);
    }

    public static function login()
    {
        $db = $_SERVER['PHP_AUTH_APP']['database'];

        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = $db->findOneBy('users', [
            'username' => $username,
        ]);
        if (!is_null($user) && isset($user) && $user) {
            if (password_verify($password, $user['password'])) {
                $newUser = new User();
                $newUser
                    ->setUsername($user['username'])
                    ->setId($user['id'])
                    ->setPassword($user['password']);
                $_SESSION['PHP_AUTH_USER'] = $newUser;

                return json_encode([
                    'msg' => 'Logged in Successfully',
                    'status' => '200',
                ]);
            } else {
                return json_encode([
                    'msg' => 'Invalid password given',
                    'status' => '200',
                ]);
            }
        } elseif (is_bool($user)) {
            return json_encode([
                'msg' => 'User not found',
                'status' => '200',
            ]);
        } else {
            return json_encode([
                'status' => '500',
                'error' => 'Internal Server',
            ]);
        }
    }

    public static function register()
    {
        $db = $_SERVER['PHP_AUTH_APP']['database'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password = password_hash($password, PASSWORD_BCRYPT);
        $user = $db->findOneBy('users', [
            'username' => $_POST['username'],
        ]);
        if (is_null($user)) {
            return json_encode([
                'msg' => "Username ($username) is already present",
                'status' => '409',
            ]);
        }

        if (
            $db->insert('users', [
                'username' => $username,
                'password' => $password,
            ])
        ) {
            $user = $db->findOneBy('users', [
                'username' => $_POST['username'],
            ]);
            $newUser = new User();
            $newUser
                ->setUsername($user['username'])
                ->setId($user['id'])
                ->setPassword($user['password']);
            $_SESSION['PHP_AUTH_USER'] = $newUser;
            return json_encode([
                'msg' => 'Registered Successfully',
                'status' => '200',
            ]);
        } else {
            return json_encode([
                'status' => '500',
                'error' => 'Internal Server',
            ]);
        }
    }

    public static function logout()
    {
        session_destroy();
        $msg = ['msg' => 'Logged Out Successfully', 'status' => '200'];
        session_start();
        return json_encode($msg);
    }
}

?>
