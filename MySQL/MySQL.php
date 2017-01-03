<?php
$db = new Swoole\MySQL;
$server = [
    'host'     => '172.17.0.2',
    'user'     => 'root',
    'password' => '123456',
    'database' => 'phalconCms',
];

$db->connect($server, function ($db, $result) {
    $db->query("show tables", function (Swoole\MySQL $db, $result){
        if ($result === false){
            var_dump($db->error, $db->errno);
        } elseif ($result === true){
            var_dump($db->affected_rows, $db->insert_id);
        } else{
            var_dump($result);
            $db->close();
        }
    });
});