<?php
//创建Web服务器
$http = new swoole_http_server("0.0.0.0", 9503);

$http->on('request', function (swoole_http_request $request, swoole_http_response $response){
    var_dump($request->get, $request->post);
    $response->header("Content-Type", "text.html; charset=utf-8");
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});

$http->start();