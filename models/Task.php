<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%task}}".
 *
 * @property integer $task_id
 * @property string $tmpl_key
 * @property integer $pid
 * @property string $task_name
 * @property integer $send_to_type
 * @property string $file
 * @property integer $total
 * @property integer $success
 * @property integer $fail
 * @property integer $file_index
 * @property integer $status
 * @property integer $is_delete
 * @property string $information
 * @property integer $created_at
 * @property integer $updated_at
 */
class Task extends Model
{
    // 状态（0：待处理，1：处理中，2：任务完成，3：任务失败，4：中断，5：暂停, 6: 终止）
    const STATUS_PENDING = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_FINISH = 2;
    const STATUS_FAILURE = 3;
    const STATUS_INTERRUPT = 4;
    const STATUS_STOP = 5;
    const STATUS_END = 6;

    // 目标类型（0：所有用户，1：指定用户）
    const SEND_ALL_USERS = 0;
    const SEND_ASSIGN_USERS = 1;
    const SEND_ASSIGN_OPENID = 2;

    const IS_DELETED = 1;
    const NOT_DELETED = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%task}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_name', 'tmpl_key', 'send_to_type'], 'required'],
            [['send_to_type', 'total', 'success', 'fail', 'file_index', 'status', 'created_at', 'updated_at', 'is_delete'], 'integer'],
            [['task_name'], 'string', 'max' => 30],
            [['file'], 'string', 'max' => 255],
            [
                'taskFile',
                'file',
                'extensions' => 'xls, xlsx, csv',
                'maxSize' => 1024 * 1024 * 4,
                'tooBig' => '{attribute} 最大不能超过4M',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_id' => '任务编号',
            'tmpl_key' => '模板KEY',
            'task_name' => '任务名称',
            'send_to_type' => '目标类型',
            'file' => '文件路径',
            'total' => '总数',
            'success' => '成功数',
            'fail' => '失败数',
            'file_index' => '文件索引',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @inheritdoc
     * @return TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskQuery(get_called_class());
    }

    public function getStatusLabel()
    {
        $statuses = $this->getStatuses();
        return ArrayHelper::getValue($statuses, $this->status);
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => '待处理',
            self::STATUS_PROCESSING => '正在执行',
            self::STATUS_FINISH => '任务完成',
            self::STATUS_FAILURE => '任务失败',
            self::STATUS_INTERRUPT => '中断',
            self::STATUS_STOP => '暂停',
            self::STATUS_END => '终止',
        ];
    }

    public function getSendToTypeLabel()
    {
        $data = $this->getSendToTypes();
        return ArrayHelper::getValue($data, $this->send_to_type);
    }

    public static function getSendToTypes()
    {
        return [
            self::SEND_ALL_USERS => '全部用户',
            self::SEND_ASSIGN_USERS => '指定任务',
        ];
    }
}
