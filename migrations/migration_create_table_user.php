<?php

include 'src/helpers/Query.php';

$db = new Query();

if ($db->is_live()) {
    $db->createTable(
        'users',
        [
            ['id', 'INT(6) UNSIGNED', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            ['username', 'VARCHAR(256)', 'NOT NULL'],
            ['password', 'VARCHAR(256)', 'NOT NULL'],
            ['UNIQUE KEY', 'unique_user', '(username)'],
        ],
        true,
        true
    );

    // $db->insert(
    //     'users',
    //     [
    //         [
    //             'username' => 'bobby',
    //             'password' => 'iloveyou',
    //         ],
    //         [
    //             'username' => 'ria',
    //             'password' => 'iloveyou',
    //         ],
    //         [
    //             'username' => 'dilip',
    //             'password' => 'iloveyou',
    //         ],
    //         [
    //             'username' => 'jatin',
    //             'password' => 'iloveyou',
    //         ],
    //     ],
    //     true
    // );

    // $db->deleteBy('users', [
    //     'username' => ['ria', 'dilip'],
    //     'password' => 'iloveyou',
    // ]);

    // $users = $db->findOneBy('users', [
    //     'username' => ['ria', 'dilip'],
    //     'password' => 'iloveyou',
    // ]);

    // print_r($users);

    // $users = $db->updateBy('users', [
    //     'username' => ['ria', 'dilip']
    // ],[
    //     'password' => 'bhaskar'
    // ]);

    $db->close();
}
?>
