<?php

/**
 * Create a memory table
 *
 * @package Swoole
 */
class swoole_table
{
    const TYPE_INT = 1;
    const TYPE_STRING = 2;
    const TYPE_FLOAT = 3;

    /**
     * Get a key
     *
     * @param string $key
     * @return array
     */
    function get($key) {}

    /**
     * Set a key
     *
     * @param string $key
     * @param array $array
     */
    function set($key, array $array) {}

    /**
     * Delete a key
     *
     * @param $key
     * @return bool
     */
    function del($key){}

    /**
     * Atomic increment operation, can be used for int or float column
     *
     * @param string $key
     * @param string $column
     * @param int $incrby
     * @return bool
     */
    function incr($key, $column, $incrby = 1) {}

    /**
     * Atomic decrement operation, can be used for int or float column
     *
     * @param string $key
     * @param string $column
     * @param int $decrby
     */
    function decr($key, $column, $decrby = 1) {}

    /**
     * Add column to current table
     *
     * @param string $name
     * @param int $type swoole_table::TYPE_INT, swoole_table::TYPE_STRING, swoole_table::TYPE_FLOAT
     * @param int $len
     */
    function column($name, $type, $len = 4) {}

    /**
     * Create table in the operating system memory
     *
     * @return bool
     */
    function create() {}

    /**
     * Lock the entire table
     *
     * @return bool
     */
    function lock() {}

    /**
     * Release the table locks
     *
     * @return bool
     */
    function unlock() {}
}
