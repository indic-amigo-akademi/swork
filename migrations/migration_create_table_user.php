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

    $db->insert(
        'users',
        [
            [
                'username' => "bobby",
                'password' => 'iloveyou',
            ],
            [
                'username' => 'ria',
                'password' => 'iloveyou',
            ],
        ],
        true
    );

    $db->close();
}
?>
