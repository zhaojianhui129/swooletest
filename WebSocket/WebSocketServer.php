<?php
class WebSocketServer
{
    public static $instance;
    public $server;

    public function __construct()
    {
        $this->server = new Swoole\WebSocket\Server('0.0.0.0', 9503);
        //WebSocket建立连接后进行握手,置onHandShake回调函数后不会再触发onOpen事件，需要应用代码自行处理
        //$this->server->on('HandShake', [$this, 'onHandShake']);
        //当WebSocket客户端与服务器建立连接并完成握手后会回调此方法
        $this->server->on('Open', [$this, 'onOpen']);
        //当服务器收到来自客户端的数据帧时会回调此函数
        $this->server->on('Message', [$this, 'onMessage']);
        //当服务器收到来自客户端的数据帧时会回调此函数。
        $this->server->on('Close', [$this, 'onClose']);

        $this->server->start();
    }

    /**
     * WebSocket建立连接后进行握手
     * @param swoole_http_request $request
     * @param swoole_http_response $response
     */
    public function onHandShake(swoole_http_request $request, swoole_http_response $response)
    {

    }

    /**
     * 当WebSocket客户端与服务器建立连接并完成握手后会回调此函数
     * @param swoole_websocket_server $server
     * @param swoole_http_request $request
     */
    public function onOpen(swoole_websocket_server $server, swoole_http_request $request)
    {
        echo "连接已打开[$request->fd]:\n";
        $server->push($request->fd, '欢迎，您已连接！');
    }

    /**
     * 当服务器收到来自客户端的数据帧时会回调此函数
     * @param swoole_server $server
     * @param swoole_websocket_frame $frame 包含了客户端发来的数据帧信息
     */
    public function onMessage(swoole_websocket_server $server, swoole_websocket_frame $frame)
    {
        echo "接收消息：". $frame->data."\n";
        $content = [];
        $msg = $frame->data;
        switch ($msg){
            case 'time':
                $content['content'] = '现在时间是：'.date("Y-m-d H:i:s");
                $server->push($frame->fd, json_encode($content));
                break;
            case 'close':
                $content['content'] = '您已申请关闭';
                $server->close($frame->fd);
                break;
            default:
                $content['content'] = 'Hello, world!';
                $server->push($frame->fd, json_encode($content));
        }
    }

    /**
     * TCP客户端连接关闭后，在worker进程中回调此函数
     * @param swoole_server $server
     * @param int $fd
     * @param int $fromId
     */
    public function onClose(swoole_websocket_server $server, int $fd, int $fromId)
    {
        echo "连接已关闭[$fd]\n";
        $server->push($fd, "您已关闭连接，欢迎下次连接.");
    }

    /**
     * 初始化实例
     * @return WebSocketServer
     */
    public static function getInstance()
    {
        if (!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }
}

WebSocketServer::getInstance();