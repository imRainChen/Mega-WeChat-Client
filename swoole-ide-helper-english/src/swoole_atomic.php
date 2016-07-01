<?php
/**
 * Atomic operation for swoole server.
 *
 * It used shared memory and can operate beetween different process.
 * gcc based CPU atomic instructions provided, without locking.
 * Must be created before swoole_server->start in order to be used on the worker process
 *
 * @package Swoole
 */
class swoole_atomic
{
    /**
     * @param int $init_value
     */
    public function __construct($init_value) {}

    /**
     * Increment the number
     *
     * @param $add_value
     * @return int
     */
    public function add($add_value) {}

    /**
     * Decrement the number
     *
     * @param $sub_value
     * @return int
     */
    public function sub($sub_value) {}

    /**
     * Get the current value
     *
     * @return int
     */
    public function get() {}

    /**
     * Set the current value
     *
     * @param $value
     */
    public function set($value) {}

    /**
     * If the current value is equal to parameter 1
     *
     * @param int $cmp_value
     * @param int $set_value
     */
    public function cmpset($cmp_value, $set_value) {}
}
