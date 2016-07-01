<?php
/**
 * Swoole develop stub
 *
 * @package Swoole
 */

/**
 * @see \swoole_server::set
 *
 * @param \swoole_server $serv
 * @param $arguments
 */
function swoole_server_set(\swoole_server $serv, array $arguments) {}

/**
 * @see \swoole_server::__construct
 *
 * @param string $host
 * @param int $port
 * @param int $mode SWOOLE_BASE, SWOOLE_THREAD, SWOOLE_PROCESS, SWOOLE_PACKET
 * @param int $sock_type SWOOLE_SOCK_TCP, SWOOLE_SOCK_TCP6, SWOOLE_SOCK_UDP, SWOOLE_SOCK_UDP6, SWOOLE_SOCK_UNIX_DGRAM, SWOOLE_SOCK_UNIX_STREAM, If you want use ssl just or (|) your current socket type with SWOOLE_SSL
 */
function swoole_server_create($host, $port, $mode = SWOOLE_PROCESS, $sock_type = SWOOLE_SOCK_TCP) {}

/**
 * @see \swoole_server::addlistener
 *
 * @param \swoole_server $serv
 * @param string $host
 * @param int $port
 * @return void
 */
function swoole_server_addlisten(\swoole_server $serv, $host = '127.0.0.1', $port = 9502) {}

/**
 * @see \swoole_server::addtimer
 *
 * @param \swoole_server $serv
 * @param int $interval
 * @return bool
 */
function swoole_server_addtimer(\swoole_server $serv, $interval) {}

/**
 * @see \swoole_server::handler
 *
 * @param \swoole_server $serv
 * @param string $event_name
 * @param callable $event_callback_function
 * @return bool
 */
function swoole_server_handler(\swoole_server $serv, $event_name, $event_callback_function) {}

/**
 * @see \swoole_server::start
 *
 * @param \swoole_server $serv
 * @return bool
 */
function swoole_server_start(\swoole_server $serv) {}

/**
 * @see \swoole_server::reload
 *
 * @param \swoole_server $serv
 * @return void
 */
function swoole_server_reload(\swoole_server $serv) {}

/**
 * @see \swoole_server::shutdown
 *
 * @param \swoole_server $serv
 * @return void
 */
function swoole_server_shutdown(\swoole_server $serv) {}

/**
 * @see \swoole_server::close
 *
 * @param \swoole_server $serv
 * @param int $fd
 * @param int $from_id
 * @return bool
 */
function swoole_server_close(\swoole_server $serv, $fd, $from_id = 0) {}

/**
 * @see \swoole_server::send
 *
 * @param \swoole_server $serv
 * @param int $fd
 * @param string $data
 * @param int $from_id
 * @return bool
 */
function swoole_server_send(\swoole_server $serv, $fd, $data, $from_id = 0) {}

/**
 * @see \swoole_server::connection_info
 *
 * @param \swoole_server $serv
 * @param int $fd
 * @return array on success or false on failure.
 */
function swoole_connection_info(\swoole_server $serv, $fd) {}

/**
 * @see \swoole_server::connection_list
 *
 * @param \swoole_server $serv
 * @param int $start_fd
 * @param int $pagesize
 * @return array on success or false on failure
 */
function swoole_connection_list(\swoole_server $serv, $start_fd = 0, $pagesize = 10) {}

/**
 * change current process name
 *
 * Same that cli_set_process_title but works with PHP 5.2 and above
 *
 * @see swoole_process::name
 *
 * @param string $name
 * @return void
 */
function swoole_set_process_name($name) {}

/**
 * Add a event listener for a socket
 *
 * Can be used with swoole_server or swoole_client
 * callable can be a string, an object + method, static class methods or anonymous function, when an event appends, the callback is called
 *
 * @param int $sock file descriptor for the socket
 * @param callable $read_callback
 * @param callable $write_callback
 * @param $flag
 * @return bool
 */
function swoole_event_add($sock, $read_callback = NULL, $write_callback = NULL, $flag = NULL) {}

/**
 * Modify a socket event settings
 *
 * You can modify the read / write callback and the event type
 *
 * @param int $sock
 * @param callable $read_callback
 * @param callable $write_callback
 * @param null $flag
 */
function swoole_event_set($sock, $read_callback = NULL, $write_callback = NULL, $flag = NULL) {}

/**
 * Removed an events
 *
 * must be used after swoole_event_add
 *
 * @param int $sock
 * @return bool
 */
function swoole_event_del($sock) {}

/**
 * 退出事件轮询
 *
 * @return void
 */
function swoole_event_exit() {}

/**
 * Asynchronous write
 *
 * @param mixed $socket
 * @param string $data
 */
function swoole_event_write($socket, $data) {}

/**
 * Get an asynchronous MySQLi socket file descriptor
 *
 * If you want to use asynchronous MySQL, you need to compile swoole with --enable-async-mysql
 * swoole_get_mysqli_sock does not support mysqlnd driver, so php5.4+ versions is not support
 *
 * @param mysqli $db
 * @return int
 */
function swoole_get_mysqli_sock(\mysqli $db) {}

/**
 * @see \swoole_server::task
 *
 * @param \swoole_server $serv
 * @param string $data
 * @return int  $task_id
 */
function swoole_server_task(\swoole_server $serv, $data) {}

/**
 * @see \swoole_server::taskwait
 *
 * @param string $task_data
 * @param float $timeout
 * @return string
 */
function swoole_server_taskwait($task_data, $timeout = 0.5) {}


/**
 * @see \swoole_server::finish
 *
 * @param \swoole_server $serv
 * @param string $response
 * @return void
 */
function swoole_server_finish(\swoole_server $serv, $response) {}

/**
 * @see \swoole_server::addtimer
 *
 * @param $interval
 * @param callable $callback
 * @return int
 */
function swoole_timer_add($interval, $callback) {}

/**
 * @see \swoole_server::deltimer
 *
 * @param $interval
 */
function swoole_timer_del($interval) {}

/**
 * @see \swoole_server::after
 *
 * @param int $ms
 * @param callable $callback
 * @param $user_param
 * @return int
 */
function swoole_timer_after($ms, $callback, $user_param = null) {}

/**
 * @see \swoole_server::clearAfter
 *
 * @param $timer_id
 * @return bool
 */
function swoole_timer_clear($timer_id) {}

/**
 * @see \swoole_server::tick
 *
 * @param int $ms
 * @param callable $callback
 * @param null $params
 * @return int
 */
function swoole_timer_tick($ms, $callback, $params = null) {}


/**
 * Event polling
 *
 * Lower than version 5.4, you need add swoole_event_wait function at the end of your PHP script in order starts event polling.
 * 5.4 or later you do not need to add this function.
 *
 * @return void
 */
function swoole_event_wait() {}

/**
 * Get swoole extended version number, such as 1.6.10
 *
 * @return string
 */
function swoole_version() {}

/**
 * The standard Unix Errno error code into an error message
 *
 * @param int $errno
 * @return string
 */
function swoole_strerror($errno) {}

/**
 * Get the last error, equivalent to C / C ++ the errno variable.
 *
 * @return int
 */
function swoole_errno() {}

/**
 * Get all ip address on all network interface
 *
 * Currently only IPv4 addresses returned and will filter out the local address 127.0.0.1.
 *
 * Result is an associative array where the interface name is the key.
 *
 * ```php
 * array("eth0" => "192.168.1.100")
 * ```
 *
 * @return array
 */
function swoole_get_local_ip() {}

/**
 * Asynchronous read file contents
 *
 * This function will return immediately after the call.
 * The callback function must accept 2 arguments ($filename, $content)
 *
 * swoole_async_readfile will copy all the contents of the file into memory, it can not be used to read large files.
 * If you want to read large files, use swoole_async_read.
 * THis function read max 4M and is limited by SW_AIO_MAX_FILESIZE
 *
 * @param string $filename
 * @param mixed $callback
 */
function swoole_async_readfile($filename, $callback) {}

/**
 * Asynchronous write files
 *
 * This function will return immediately after the call.
 * The callback function must accept 1 argument ($filename)
 *
 * THis function write max 4M and is limited by SW_AIO_MAX_FILESIZE
 *
 * @param string $filename
 * @param string $content
 * @param callable $callback
 */
function swoole_async_writefile($filename, $content, $callback) {}

/**
 * Asynchronous read file
 *
 * This function will return immediately after the call.
 * The callback function must accept 2 arguments ($filename, $content)
 *
 * This function can be used to read large file since the data is chunk with $trunk_size unlike swoole_async_readfile and will not take up too much memory
 *
 * On the callback function, you can return true / false, to control whether to continue to read the next part
 * Return true, continue to read
 * Return false, stop reading and close the file
 *
 * @param string $filename
 * @param mixed $callback
 * @param int $trunk_size
 * @return bool
 */
function swoole_async_read($filename, $callback, $trunk_size = 8192) {}

/**
 * Asynchronous write files
 *
 * This function will return immediately after the call.
 * The callback function must accept 1 argument ($filename)
 *
 * This fonction can be used to write large files, but you need to specify the $offset in order to write it
 *
 * @param string $filename
 * @param string $content
 * @param int $offset
 * @param mixed $callback
 *
 * @return bool
 */
function swoole_async_write($filename, $content, $offset, $callback = NULL) {}

/**
 * Asynchronous dns lookup
 *
 * This function will return immediately after the call.
 * The callback function must accept 2 arguments ($host, $ip)
 *
 * @param string $domain
 * @param callable $callback
 */
function swoole_async_dns_lookup($domain, $callback) {}

/**
 * Io Event loop
 *
 * Parallel processing of swoole_client using a select IO event loop
 *
 * $read, $write, $error must be a reference array and must contains swoole_client object.
 * On success, it will return the number of events, and modify the $read / $write / $error array.
 * Use foreach loop through the array, and then execute $item->recv / $item->send to send and receive data.
 * Calling $item->close() or unset($item) will close the socket.
 *
 * @param array $read readable file descriptor
 * @param array $write writable file descriptor
 * @param array $error error file descriptor
 * @param float $timeout timeout in second that accept float values
 * @return int yjer
 */
function swoole_client_select(array &$read, array &$write, array &$error, $timeout) {}

/**
 * current Swoole version
 */
define('SWOOLE_VERSION', '1.7.20');

/**
 * swoole_server & swoole_client : ssl
 */
define('SWOOLE_SSL', 5);

/**
 * swoole_server mode : use the base model, code directly executed in Reactor
 */
define('SWOOLE_BASE', 1);

/**
 * swoole_server mode : using threads models, code execution in thread
 */
define('SWOOLE_THREAD', 2);

/**
 * swoole_server mode : use process models, code execution in different process
 */
define('SWOOLE_PROCESS', 3);

/**
 * swoole_server type : Create tcp socket
 */
define('SWOOLE_TCP', 1);
/**
 * swoole_server type : Create tcp socket
 */
define('SWOOLE_SOCK_TCP', 1);

/**
 * swoole_server type : Create tcp ipv6 socket
 */
define('SWOOLE_TCP6', 2);
/**
 * swoole_server type : Create tcp ipv6 socket
 */
define('SWOOLE_SOCK_TCP6', 3);

/**
 * swoole_server type : Create udp socket
 */
define('SWOOLE_UDP', 3);
/**
 * swoole_server type : Create udp socket
 */
define('SWOOLE_SOCK_UDP', 2);

/**
 * swoole_server type : Create udp ipv6 socket
 */
define('SWOOLE_UDP6', 4);
/**
 * swoole_server type : Create udp ipv6 socket
 */
define('SWOOLE_SOCK_UDP6', 4);

/**
 * swoole_server type : Create unix dgram
 */
define('SWOOLE_UNIX_DGRAM', 5);
/**
 * swoole_server type : Create unix dgram
 */
define('SWOOLE_SOCK_UNIX_DGRAM', 5);

/**
 * swoole_server type : Create unix stream
 */
define('SWOOLE_UNIX_STREAM', 6);
/**
 * swoole_server type : Create unix stream
 */
define('SWOOLE_SOCK_UNIX_STREAM', 6);


/**
 * swoole_http_server : setGlobal all
 */
define('HTTP_GLOBAL_ALL', 1);

/**
 * swoole_http_server : setGlobal GET
 */
define('HTTP_GLOBAL_GET', 2);

/**
 * swoole_http_server : setGlobal POST
 */
define('HTTP_GLOBAL_POST', 4);

/**
 * swoole_http_server : setGlobal COOKIE
 */
define('HTTP_GLOBAL_COOKIE', 8);


/**
 * swoole_websocket_server : ??
 */
define('WEBSOCKET_OPCODE_TEXT', 1);


/**
 * swoole_client : Sync client
 */
define('SWOOLE_SOCK_SYNC', 0);

/**
 * swoole_client : Async client
 */
define('SWOOLE_SOCK_ASYNC', 1);

/**
 * ?? : Async client
 */
define('SWOOLE_SYNC', 0);

/**
 * ?? : Async client
 */
define('SWOOLE_ASYNC', 1);

/**
 * swoole_client : Keepalive Connection
 */
define('SWOOLE_KEEP', 512);

/**
 * swoole_client : ??
 */
define('SWOOLE_PACKET', 0x10);




/**
 * swoole_lock : create a file lock
 */
define('SWOOLE_FILELOCK', 2);

/**
 * swoole_lock : create a mutex
 */
define('SWOOLE_MUTEX', 3);

/**
 * swoole_lock : create a read-write lock
 */
define('SWOOLE_RWLOCK', 1);

/**
 * swoole_lock : create spin locks
 */
define('SWOOLE_SPINLOCK', 5);
/**
 * swoole_lock : Create Semaphore
 */
define('SWOOLE_SEM', 4);

/**
 *
 */
define('SWOOLE_EVENT_WRITE', 1);
/**
 *
 */
define('SWOOLE_EVENT_READ', 2);
