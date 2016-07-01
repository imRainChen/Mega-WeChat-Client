<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Task]].
 *
 * @see Task
 */
class TaskQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere([Task::tableName().'.is_delete' => Task::NOT_DELETED]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return Task[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Task|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}