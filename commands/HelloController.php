<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Test\Apple;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        $arr = [
            'aaa',
            'aaa',
            'aaa',

        ];
        $this->taskSliceFile($arr, 5);
    }


    private function taskSliceFile($dataList, $limit = 10000)
    {
        $count = count($dataList);
        if ($count <= $limit) {

        }
        $pageCount = (int)(($count + $limit - 1) / $limit);
        $_dataList = [];
        for($i = 0; $i < $pageCount; $i++)
        {
            $offset = $i * $limit;
            $_dataList[] = array_slice($dataList, $i, $limit);
        }

        var_dump($_dataList);
    }

}
