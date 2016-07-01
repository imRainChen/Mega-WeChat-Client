<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $uid
 * @property string $username
 * @property string $password
 * @property string $nickname
 * @property integer $gender
 * @property string $openid
 * @property string $reg_time
 * @property integer $status
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'nickname', 'openid'], 'required'],
            [['gender', 'reg_time', 'status'], 'integer'],
            [['username', 'nickname'], 'string', 'max' => 16],
            [['password'], 'string', 'max' => 32],
            [['openid'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'username' => 'Username',
            'password' => 'Password',
            'nickname' => 'Nickname',
            'gender' => 'Gender',
            'openid' => 'Openid',
            'reg_time' => 'Reg Time',
            'status' => '状态（-1-删除，0-禁止登陆，1-正常）',
        ];
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
