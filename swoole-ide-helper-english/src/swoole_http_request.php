<?php
/**
 * Http request object
 *
 * @package Swoole
 */
class swoole_http_request
{
    /**
     * QueryString
     *
     * @var array
     */
    public $get;

    /**
     * PostData
     *
     * @var array
     */
    public $post;

    /**
     * Headers
     *
     * @var array
     */
    public $header;

    /**
     * Server variable
     *
     * @var array
     */
    public $server;

    /**
     * Cookie
     *
     * @var array
     */
    public $cookie;

    /**
     * Files uploaded
     *
     * @var array
     */
    public $files;

    /**
     * File descriptor
     *
     * @var int
     */
    public $fd;

    /**
     * Get original form POST data (the non urlencoded-form)
     *
     * @return string
     */
    public function rawContent() {}
}
