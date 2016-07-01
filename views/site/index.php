<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Mega-WeChat-Client';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Mega-WeChat</h1>

        <p class="lead">You have successfully created your Mega-WeChat-Client DEMO</p>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>微信模板</h2>

                <p>支持自定义消息变量，使用JSON存储微信模板内容。</p>
                <p>方便运营随时改随时用。</p>

                <p><a class="btn btn-default" href="<?= Url::to(['/wechat-template']) ?>">Template Go &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>微信模板消息任务</h2>
                <ul style="margin-left: -20px">
                <li>支持向任意用户发送模板消息。</li>
                <li>支持广播形式发送所有用户。</li>
                <li>支持任务分批执行。</li>
                <li>支持任务开始，暂停，重试，终止和删除。</li>
                </ul>
                <p><a class="btn btn-default" href="<?= Url::to(['/task']) ?>">Task Go &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Mega-WeChat</h2>

                <p>Mega-Wechat是一款发送微信模板消息的服务，基于Swoole网络框架实现。
                    支持大量的消息发送，并发执行发送模板消息接口，整个发送过程按照先来先服务的队列执行。
                    支持定制模板消息，随时改随时用。</p>

                <p><a class="btn btn-default" href="https://github.com/imRainChen/Mega-Wechat">Mega-WeChat Go &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
