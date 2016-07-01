<?php

namespace app\models;

use app\models\User;

/**
 * This is the ActiveQuery class for [[\common\models\oc\User]].
 *
 * @see \common\models\oc\User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere([User::tableName().'.status' => User::STATUS_ACTIVE]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byId($id)
    {
        $this->andWhere([User::tableName().'.uid' => $id]);
        return $this;
    }



}