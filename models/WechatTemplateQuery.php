<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[WechatTemplate]].
 *
 * @see WechatTemplate
 */
class WechatTemplateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return WechatTemplate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return WechatTemplate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}