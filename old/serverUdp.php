<?php
//创建UDP服务器
//创建Server对象，监听 127.0.0.1：9502端口,类型为SWOOLE_SOCK_UDP
$serv = new swoole_server("0.0.0.0", 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

//监听数据发送事件
$serv->on('Packet', function (swoole_server $serv, $data, swoole_client $clientInfo){
    $serv->sendto($clientInfo['address'], $clientInfo['port'], "Tcp ".$data);
    var_dump($clientInfo);
});

//启动服务器
$serv->start();