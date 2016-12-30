<?php

/**
 * HttpServer
 */
class HttpServer
{
    public static $instance;
    public $server;

    public function __construct()
    {
        //初始化http服务
        $this->server = new Swoole\Http\Server('0.0.0.0', 9502);
        //配置
        $this->server->set([
            'worker_num' => 100,//设置启动的worker进程数
            'daemonize' => false,//守护进程化
            'max_request' => 1000,//设置worker进程的最大任务数
            'dispatch_mode' => 1,//数据包分发策略
            'upload_tmp_dir' => '/tmp/swooleupload',
        ]);
        //此事件在worker进程/task进程启动时发生
        $this->server->on('workerStart', [$this, 'onWorkerStart']);
        //绑定请求事件
        $this->server->on('request', [$this, 'onRequest']);

        $this->server->start();
    }

    /**
     * 此事件在worker进程/task进程启动时发生
     * @param swoole_server $server
     * @param int $workerId
     */
    public function onWorkerStart(swoole_server $server, int $workerId)
    {
        echo "启动work进程[$workerId].\n";
    }

    /**
     * 请求处理
     * @param swoole_http_request $request
     * @param swoole_http_response $response
     */
    public function onRequest(swoole_http_request $request, swoole_http_response $response)
    {
        /*var_dump($request->get);
        var_dump($request->post);
        var_dump($request->cookie);
        var_dump($request->files);
        var_dump($request->header);
        var_dump($request->server);*/

        $response->cookie("User", "Swoole");
        $response->header("X-Server", "Swoole");
        $response->end("<h1>Hello Swoole!</h1>");
    }

    /**
     * 初始化实例
     * @return HttpServer
     */
    public static function getInstance()
    {
        if (!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }
}

HttpServer::getInstance();