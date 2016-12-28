<?php
//创建WebSocket服务器
//创建websocket服务器对象，监听0.0.0.0:9504端口
$ws = new swoole_websocket_server("0.0.0.0", 9504);

//监听WebSocket链接打开事件
$ws->on('open', function (swoole_websocket_server $ws, swoole_http_request $request) {
    var_dump($request->fd, $request->get, $request->server);
    $ws->push($request->fd, "Hello, welcome\n");
});

//监听WebSocket消息事件
$ws->on('message', function (swoole_websocket_server $ws, $frame) {
    echo "Message: {$frame->data}\n";
    $ws->push($frame->fd, "server:{$frame->data}");
});

//监听WebSocket连接关闭事件
$ws->on('close', function (swoole_websocket_server $ws, $fd) {
    echo "client-{$fd} is closed \n";
});

$ws->start();