<?php
/**
 * Http client
 *
 * @package Swoole
 */
class swoole_client
{
    /**
     * Last error code
     *
     * @var int
     */
    public $errCode;

    /**
     * Socket file descriptor
     *
     * Example
     * ```php
     * $sock = fopen("php://fd/".$swoole_client->sock);
     * ```
     *
     * The swoole_client is a stream socket. You can call fread / fwrite / fclose functions.
     * For swoole_server, the $fd cannot use this method to convert as $fd is just a number, $fd file descriptor is part of the main process
     * $swoole_client->sock can be converted to int as the key array.
     *
     * @var int
     */
    public $sock;

    /**
     * @param int $sock_type specified socket type and supports TCP / UDP, TCP6 / UDP64
     * @param int $sync_type SWOOLE_SOCK_SYNC/SWOOLE_SOCK_ASYNC synchronous / asynchronous
     */
    public function __construct($sock_type, $sync_type = SWOOLE_SOCK_SYNC) {}

    /**
     * Connect to the remote server
     *
     * Before send / recv swoole_client_select must be used to detect if the connection is open
     *
     * @param string $host is the address of the remote server, v1.6.10+ Swoole will automatically fill the domain DNS query
     * @param int $port is the remote server port
     * @param float $timeout is network IO timeout, the unit is s, support for floating point. The default is 0.1s, ie 100ms
     * @param int $flag parameter when UDP type indicates whether udp_connect enabled. Setting this option will bind $host and $port, the UDP will discard non-designated host / port packet.
     * @return bool
     */
    public function connect($host, $port, $timeout = 0.1, $flag = 0) {}

    /**
     * Sending data to a remote server
     *
     * Parameter is a string, support for binary data.
     * On success, returned data length sent
     * On failure, return false and sets $swoole_client->errCode
     *
     * @param string $data
     * @return bool|int
     */
    public function send($data) {}

    /**
     * Sent data to any ip:port, only support UDP / UDP6
     *
     * @param $ip
     * @param $port
     * @param $data
     */
    function sendto($ip, $port, $data) {}

    /**
     * Receive data from the server
     *
     * If you set the $waitall you must set the correct $size, otherwise they will have to wait until the data length reaches $size is received
     * If you set the wrong $size, can cause recv timeout & returns false
     * Successful call returns the resulting string, failed to return false, and sets $swoole_client->errCode property
     *
     * @param int $size the maximum length of the received data
     * @param bool $waitall whether to wait for all the data $size
     * @return string|bool
     */
    public function recv($size = 65535, $waitall = false) {}

    /**
     * Close remote connection
     *
     * Automaticaly call on destruct
     *
     * @return bool
     */
    public function close() {}

    /**
     * Register asynchronous event callback function
     *
     * @param $event_name (connect, receive, error, close)
     * @param callable $callback_function
     * @return bool
     */
    public function on($event_name, $callback_function) {}

    /**
     * Is connected to the server
     *
     * @return bool
     */
    public function isConnected() {}

    /**
     * Get client socket of host:port information
     *
     * @return bool|array
     */
    public function getsockname() {}

    /**
     * Get the peername of this connection, only for UDP / UDP6
     *
     * UDP sends data to the server, response may be sent by another server
     *
     * @return bool|array
     */
    public function getpeername() {}
}
