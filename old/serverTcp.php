<?php
//创建TCP服务器
//创建Server对象，监听127.0.0.1：9501端口
$serv = new swoole_server("0.0.0.0", 9501);

//监听连接进入时间
$serv->on('connect', function($serv, $fd){
   echo "Client: Connect:\n"; 
});

//监听数据发送事件
$serv->on('receive', function($serv, $fd, $from_id, $data){
    $serv->send($fd, "Server: ".$data);
});

//监听连接关闭事件
$serv->on('close', function($serv, $fd){
    echo "Client: Close.\n";
});

//启动服务器
$serv->start();