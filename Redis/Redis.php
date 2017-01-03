<?php
$redis = new Swoole\Redis;
$redis->connect('172.17.0.3', 6379, function (Swoole\Redis $redis, $result) {
    $redis->set('test_key', 'value', function (Swoole\Redis $redis, $result) {
        $redis->get('test_key', function (Swoole\Redis $redis, $result) {
            var_dump($result);
        });
    });
});

$client = new Swoole\Http\Client('127.0.0.1', 80);
$client->setHeaders(['User-Agent' => 'swoole-http-client']);
$client->setCookies(['test' => 'value']);

$client->post('/dump.php', ['test' => 'abc'], function (Swoole\Http\Client $client) {
    var_dump($client->body);
    $client->get('/index.php', function (Swoole\Http\Client $client) {
        var_dump($client->cookies);
        var_dump($client->headers);
    });
});