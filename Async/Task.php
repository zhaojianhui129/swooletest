<?php

class Task
{
    public static $instance;
    public $server;

    public function __construct()
    {
        $this->server = new Swoole\Server('127.0.0.1', 9504);
        $this->server->set([
            'task_worker_num' => 4,
        ]);
        $this->server->on('Receive', [$this, 'onReceive']);
        $this->server->on('Task', [$this, 'onTask']);
        $this->server->on('Finish', [$this, 'onFinish']);

        $this->server->start();
    }

    /**
     * @param \Swoole\Server $server swoole_server对象
     * @param int $fd TCP客户端连接的文件描述符
     * @param int $fromId TCP连接所在的Reactor线程ID
     * @param string $data 收到的数据内容，可能是文本或者二进制内容
     */
    public function onReceive(Swoole\Server $server, int $fd, int $fromId, string $data){
        $taskId = $server->task("Async");
        echo "调度异步任务:id=$taskId".PHP_EOL;
    }

    /**
     * 在task_worker进程内被调用
     * @param \Swoole\Server $server
     * @param int $taskId 任务ID
     * @param int $fromId 来自于哪个worker进程
     * @param string $data
     */
    public function onTask(Swoole\Server $server, int $taskId, int $fromId, string $data){
        echo "新任务[id=$taskId]" . PHP_EOL;
        $server->finish("$data -> OK");
    }

    /**
     * @param \Swoole\Server $server
     * @param $taskId 任务的ID
     * @param $data 任务处理的结果内容
     */
    public function onFinish(Swoole\Server $server, $taskId, $data){
        echo "定时任务[$taskId] 完成：$data" . PHP_EOL;
    }

    /**
     * 初始化实例
     * @return Task
     */
    public static function getInstance()
    {
        if (!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }
}

Task::getInstance();