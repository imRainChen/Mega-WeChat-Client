<?php
/**
 * Created by PhpStorm.
 * User: rain1
 * Date: 2016/5/12
 * Time: 17:31
 */

namespace app\models;


use yii\helpers\ArrayHelper;

class TaskForm extends Task
{
    public $taskFile;

    public $send_to_type = Task::SEND_ALL_USERS;

    public $sliceTask = false;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['sliceTask',], 'boolean'],
        ]);
    }
}