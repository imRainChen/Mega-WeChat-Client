<?php
/**
 * Http response object
 *
 * @package Swoole
 */
class swoole_http_response
{
    /**
     * End Http response, send HTML content
     *
     * @param string $html
     */
    public function end($html = '') {}

    /**
     * Send Http chunk of data to the browser
     *
     * @param string $html
     */
    public function write($html) {}

    /**
     * Add a header
     *
     * @param string $key
     * @param string $value
     */
    public function header($key, $value) {}

    /**
     * Set a cookie
     *
     * @param string $key
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     */
    public function cookie($key, $value, $expire = 0, $path='/', $domain='', $secure=false, $httponly=false) {}

    /**
     * Set Http Status Code, such as 404, 501, 200
     *
     * @param int $code
     */
    public function status($code) {}

    /**
     * Set http compression format
     *
     * @param int $level
     */
    public function gzip($level = 1) {}
}
