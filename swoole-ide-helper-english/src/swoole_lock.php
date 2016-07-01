<?php
/**
 * Locking operation for swoole server.
 *
 * Each type of lock doesn't support all methods (such as read-write locks), file locks support $lock->lock_read().
 *
 * Other types of locks than file lock must be created within the parent process in order to handle locking from fork childs.
 *
 * @package Swoole
 */
class swoole_lock
{
    /**
     * @param int $type the type of lock (SWOOLE_FILELOCK, SWOOLE_MUTEX, SWOOLE_RWLOCK, SWOOLE_SPINLOCK, SWOOLE_SEM)
     * @param string $lockfile when the type is SWOOLE_FILELOCK, you must specify the path of the file lock
     */
    public function __construct($type, $lockfile = NULL) {}

    /**
     * Read Write Lock operation
     *
     * If there is another process that hold the lock, the call of this function will block execution process
     */
    public function lock() {}

    /**
     * Non-blocking lock
     *
     * The same than lock but release immediatly if there is a lock
     * Unavailable with SWOOlE_SEM semaphore
     *
     * @return bool if the lock is free
     */
    public function trylock() {}

    /**
     * Release the lock
     */
    public function unlock() {}

    /**
     * Read locking only
     *
     * lock_read method is only available in read-write lock (SWOOLE_RWLOCK) and file locks (SWOOLE_FILELOCK)
     * If there is another process that hold the read_lock, the call of this function will block execution process
     * Other processes can still get a read lock while there is no an exclusive lock with $lock->lock() or $lock->trylock ().
     */
    public function lock_read() {}

    /**
     * Non-blocking lock
     * the same than lock_read but release immediatly if there is a lock
     *
     * @return bool if the lock is free
     */
    public function trylock_read() {}
}
