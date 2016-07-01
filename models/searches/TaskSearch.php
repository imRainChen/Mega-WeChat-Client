<?php

namespace app\models\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Task;

/**
 * TaskSearch represents the model behind the search form about `app\models\Task`.
 */
class TaskSearch extends Task
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'pid', 'send_to_type', 'total', 'success', 'fail', 'file_index', 'status', 'created_at', 'updated_at'], 'integer'],
            [['tmpl_key', 'task_name', 'file', 'information'], 'safe'],
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
        $query = Task::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'task_id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'task_id' => $this->task_id,
            'pid' => $this->pid,
            'send_to_type' => $this->send_to_type,
            'total' => $this->total,
            'success' => $this->success,
            'fail' => $this->fail,
            'file_index' => $this->file_index,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tmpl_key', $this->tmpl_key])
            ->andFilterWhere(['like', 'task_name', $this->task_name])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'information', $this->information])
            ->active();

        return $dataProvider;
    }
}
