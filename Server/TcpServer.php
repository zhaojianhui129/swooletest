<?php

/**
 * TCP Server
 */
class TcpServer
{
    public static $instance;

    //服务对象
    private $server;

    public function __construct()
    {
        //try{
            //创建swoole_server对象
            $this->server = new Swoole\Server('0.0.0.0', 9501);
            //设置
            $this->server->set([
                'worker_num' => 8,//工作进程数量
                //'daemonize' => true,//是否作为守护进程
            ]);
            //监听连接进入事件
            $this->server->on('connect', [$this, 'onConnect']);
            //监听数据发送事件
            $this->server->on('receive', [$this, 'onReceive']);
            //监听连接关闭事件
            $this->server->on('close', [$this, 'onClose']);

            $this->server->start();
        /*}catch (){

        }*/
    }

    /**
     * 有新的连接进入时，在worker进程中回调。
     * @param swoole_server $server
     * @param int $fd
     */
    public function onConnect(swoole_server $server,int $fd)
    {
        echo "客户端:链接.\n";
    }

    /**
     * 接收到数据时回调此函数,发生在worker进程中。
     * @param swoole_server $server
     * @param int $fd
     * @param int $fromId
     * @param string $data
     */
    public function onReceive(swoole_server $server, int $fd, int $fromId, string $data)
    {
        switch ($data){
            case 'time':
                $data = "现在时间时：".date("Y-m-d H:i:s");
                break;
            case 'stop':
                $this->server->shutdown();
                break;
            case 'reload':
                $this->server->reload();
                break;
            case 'status':
                $data = "当前服务信息:".var_export($this->server->stats());
                break;
            default:
                $data = "你发送的内容是：".$data;
        }
        $server->send($fd, $data);
        $server->close($fd);
    }

    /**
     * TCP客户端连接关闭后,在worker进程中回调此函数
     * @param swoole_server $server
     * @param int $fd
     * @param int $fromId
     */
    public function onClose(swoole_server $server, int $fd, int $fromId)
    {
        echo "客户端：关闭\n";
    }

    /**
     * 获取实例对象
     * @return TcpServer
     */
    public static function getInstance()
    {
        if (! self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }
}

TcpServer::getInstance();