<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%wechat_template}}".
 *
 * @property string $tmpl_key
 * @property string $title
 * @property string $template
 * @property integer $created_on
 * @property integer $updated_on
 */
class WechatTemplate extends \app\models\Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wechat_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tmpl_key', 'template', 'title'], 'required'],
            [['template'], 'string', 'max' => 4024],
            [['created_at', 'updated_at'], 'integer'],
            [['tmpl_key'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tmpl_key' => '模板key',
            'title' => '模板标题',
            'template' => '模板内容',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @inheritdoc
     * @return WechatTemplateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WechatTemplateQuery(get_called_class());
    }
}
