<?php
/**
 * Created by PhpStorm.
 * User: rain1
 * Date: 2016/4/28
 * Time: 10:55
 */

namespace Swoole\Util;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log
{
    const DEFAULT_LOG_NAME = 'swoole';

    private static $logHandlers;

    /**
     * Add log handlers to tailor logging for your use case. Default logging
     * is the Monolog default, a Monolog StreamHandler('php://stderr', static::DEBUG)
     * Use Monolog NullHandler to disable all logging.
     * @param $handlers
     */
    public static function setLogHandlers($handlers)
    {
        self::$logHandlers = $handlers;
    }

    /**
     * Get the current log handler array
     * @return mixed
     */
    public static function getLogHandlers()
    {
        return self::$logHandlers;
    }

    /**
     * Returns the logger for standard logging in the library
     * @return Logger
     */
    public static function getLogger()
    {
        if (!self::$logHandlers) {
            $logHandler = new StreamHandler(Config::get('setting.log_path') . '/' . date('Y-m-d') . '.log', \Monolog\Logger::INFO);
            self::setLogHandlers([$logHandler]);
        }
        return new Logger(self::DEFAULT_LOG_NAME, self::$logHandlers);
    }
}