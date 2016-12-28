<?php
//创建异步TCP客户端

$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);

//注册连接成功回调
$client->on('connect', function (swoole_client $cli) {
    $cli->send("hello world\n");
});

//注册数据接收回调
$client->on("receive", function (swoole_client $cli, $data){
    echo "Received: ".$data."\n";
});

//注册连接失败回调
$client->on("error", function (swoole_client $cli){
    echo "Connect failed\n";
});

//注册链接关闭回调
$client->on("close", function(swoole_client $cli){
    echo "Connection close\n";;
});

//发起连接
$client->connect("172.17.0.4", 9501, 0.5);