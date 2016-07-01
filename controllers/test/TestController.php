<?php
/**
 * Created by PhpStorm.
 * User: rain1
 * Date: 2016/1/14
 * Time: 14:02
 */

namespace app\controllers\test;

use Yii;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        echo "Test this Controller in sub file";
    }
}