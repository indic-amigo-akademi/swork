<?php

if ($db->is_live()) {
    $db->createTable(
        'plans',
        [
            ['id', 'INT(6) UNSIGNED', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            ['name', 'VARCHAR(256)', 'NOT NULL'],
            ['slug', 'VARCHAR(256)', 'NOT NULL'],
            ['users', 'LONGTEXT', 'NOT NULL'],
            ['author', 'INT(6) UNSIGNED', 'NOT NULL'],
            ['UNIQUE KEY', 'unique_board_name', '(slug)'],
            [
                'FOREIGN KEY',
                'fk_author_id',
                '(author)',
                'REFERENCES',
                '`users`(id)',
            ],
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
}
?>
