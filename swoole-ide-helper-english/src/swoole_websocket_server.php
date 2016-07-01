<?php

/**
 * Websocket server
 *
 * @package Swoole
 */
class swoole_websocket_server extends swoole_http_server
{
    /**
     * WebSocket method to push data to client
     *
     * @param resource $fd
     * @param string $data
     * @param bool $binary_data
     * @param bool $finish
     */
    public function push($fd, $data, $binary_data = false, $finish = true) {}
}
