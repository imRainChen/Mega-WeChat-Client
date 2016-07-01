<?php
/**
 * Built-in Web server
 *
 * @package Swoole
 */
class swoole_http_server extends swoole_server
{
    /**
     * Enable fill of the $GLOBALS variables with GET, POST, COOKIE data
     *
     * @param int $flag HTTP_GLOBAL_ALL, HTTP_GLOBAL_GET, HTTP_GLOBAL_POST, HTTP_GLOBAL_COOKIE
     * @param int $request_flag
     */
    function setGlobal($flag, $request_flag = 0) {}
}
