<?php

/**
 * TCP Client
 */
class TcpClient
{
    public static $instance;
    //TCP客户端
    private $client;
    public function __construct()
    {
        $this->client = new Swoole\Client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
        //客户端连接服务器成功后
        $this->client->on('connect', [$this, 'onConnect']);
        //客户端收到来自于服务器端的数据时会回调此函数
        $this->client->on('receive', [$this, 'onReceive']);
        //连接服务器失败时
        $this->client->on('error', [$this, 'onError']);
        //连接被关闭时
        $this->client->on('close', [$this, 'onClose']);
        //发起网络连接
        $this->client->connect('127.0.0.1', 9501, 0.5);
    }

    /**
     * 客户端连接服务器成功后会回调此方法
     * @param swoole_client $client
     */
    public function onConnect(swoole_client $client)
    {
        $client->send("time\n");
    }
    /**
     * 连接服务器失败时会回调此方法
     * @param swoole_client $client
     */
    public function onError(swoole_client $client)
    {
        echo "连接失败.\n";
    }

    /**
     * 客户端收到来自于服务器端的数据时会回调此方法
     * @param swoole_client $client
     * @param string $data
     */
    public function onReceive(swoole_client $client, string $data)
    {
        echo "接收数据:\n".$data."\n";
    }

    /**
     * 连接被关闭时回调此方法
     * @param swoole_client $client
     */
    public function onClose(swoole_client $client)
    {
        echo "连接关闭.\n";
    }

    /**
     * 获取实例对象
     * @return TcpClient
     */
    public static function getInstance()
    {
        if (! self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }
}

TcpClient::getInstance();