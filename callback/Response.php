<?php
/**
 * Created by PhpStorm.
 * User: rain1
 * Date: 2016/2/3
 * Time: 16:16
 */
namespace callback;

class Response
{
    /**
     * 处理API响应成功和失败的数据返回格式
     * beforeSend事件的回调函数
     * @param $event
     */
    public static function responseFormat($event) {
        $response = $event->sender;
        if ($response->format == 'json' && $response->data !== null && $response->isSuccessful == true) {
            $response->data = [
                'success' => true,
                'data' => $response->data,
            ];
            $response->statusCode = 200;
        } else if ($response->format == 'json' && isset($response->data['code']) && $response->data['message']) {
            $response->data = [
                'success' => false,
                'errCode' => $response->data['code'],
                'errMsg' => $response->data['message']
            ];
            $response->statusCode = 200;
        }

    }
}