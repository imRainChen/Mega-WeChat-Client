<?php

namespace app\models\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WechatTemplate;

/**
 * WechatTemplateSearch represents the model behind the search form about `app\models\WechatTemplate`.
 */
class WechatTemplateSearch extends WechatTemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tmpl_key', 'title', 'template',], 'safe'],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WechatTemplate::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tmpl_key', $this->tmpl_key])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'template', $this->template]);

        return $dataProvider;
    }
}
