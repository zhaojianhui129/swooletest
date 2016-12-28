<?php
//执行异步任务
$serv = new swoole_server("0.0.0.0", 9505);

//设置异步任务的工作进程数量
$serv->set(['task_worker_num' => 4]);

$serv->on('receive', function (swoole_server $serv, $fd, $from_id, $data) {
    //投递异步任务
    $task_id = $serv->task($data);
    echo "Dispath AsyncTask: id=$task_id\n";
});

//处理异步任务
$serv->on('task', function (swoole_server $serv, $task_id, $from_id, $data){
    echo "New AsyncTask[id=$task_id]".PHP_EOL;
    //返回任务的结果
    $serv->finish("$data -> OK");
});

//处理异步任务的结果
$serv->on('finish', function (swoole_server $serv, $task_id, $data) {
    echo "AsyncTask[$task_id] Finish: $data".PHP_EOL;
});

$serv->start();