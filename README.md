Mega-WeChat-Client
==========
这是Mega-WeChat的Client Demo，主要的功能是发送模板消息任务，支持自定义模板内容，指定发送用户或群发所有用户。该项目是基于Yii实现，Client使用Swoole。

二话不说，先上效果图：

![这里写图片描述](https://note.youdao.com/yws/api/personal/file/WEB436c8ffaecd9e873feb8f7cfff03ffa2?method=download&shareKey=6be71e4741189afb23fb65f81645fa29)

功能特性
----------
 - 发送微信模板消息
 - 多任务发送支持
 - 自定义用户模板消息内容
 - 群发所有用户或指定用户
 - 实时进度监控，支持进度控制（开始，暂停）

介绍
----------

###环境要求
 - php5.6+
 - Swoole1.8.2+
 - Mysql
 - Composer

###安装

**第一步**
安装PHP，需要5.6以上版本。由于服务端的队列用了SPL函数和PHP新特性的语法

**第二步**
安装Mysql，什么版本都可以。

> yum install mysql


**第三步**
安装swoole扩展前必须保证系统已经安装了下列软件

> php-5.3.10 或更高版本
> gcc-4.4 或更高版本
> make
> autoconf 

下载地址
[https://github.com/swoole/swoole-src/releases](https://github.com/swoole/swoole-src/releases)
[http://pecl.php.net/package/swoole](http://pecl.php.net/package/swoole)
[http://git.oschina.net/matyhtf/swoole](http://git.oschina.net/matyhtf/swoole)

**第四步**
初始化Yii并安装composer依赖，进入到项目根目录。

> cd Mega-WeChat-Client
> php composer.phar global require "fxp/composer-asset-plugin:~1.1.1
> php composer.phar install
> ./init

需要导入数据库表，表结构在**mega-db.sql**

**第五步**
安装Mega-WeChat服务端
详细教程在github：https://github.com/imRainChen/Mega-Wechat

###配置
该项目基于Yii框架，配置的方式与Yii相同。

* 设置数据库组件配置信息

```php
vi config/db.php

[
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=mega',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8mb4',
    'tablePrefix'   => 'mega_',
];
```

* 设置Mega-Wechat配置信息

```php
vi config/params.php

'mega'  =>  [
    'host' => '127.0.0.1',
    'port' => '9501',
    'mysql'    =>  [
        'dsn' => 'mysql:host=127.0.0.1;dbname=mega',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
        'tablePrefix'   => 'mega_',
    ],
]
```

###注意事项

 1. 如若出现任务执行失败，请检查Mega-WeChat服务是否启动。
 2. 检查runtime目录存放日志的权限，必须要有读写权限。
 3. 若还有什么问题请咨询我

贡献
----------
如果有什么建议欢迎联系，[也可发布问题和反馈。](https://github.com/imRainChen/Mega-Wechat/issues)

Email：chenjiarong448@qq.com
