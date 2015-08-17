<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
/**
 * This is the model class for table "document".
 *
 * @property integer $id
 * @property integer $template_id
 * @property string $name
 * @property integer $index
 * @property string $data
 * @property string $create_time
 * @property string $update_time
 *
 * @property Template $template
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id', 'name', 'index', 'data'], 'required'],
            [['template_id', 'index'], 'integer'],
            [['data'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'template_id' => 'Template ID',
            'name' => 'Name',
            'index' => 'Index',
            'data' => 'Data',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }

    public function beforeSave($insert)
    {
        $this->update_time = date('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }

    public function getInfo()
    {
        return array(
            "id" => $this->id,
            "name" => $this->name,
            "index" => $this->index,
            "data" => $this->data,
            "create_time" => $this->create_time,
            "update_time" => $this->update_time, 
            "uri" => Url::to(["document/viewdoc", "id" => $this->id])

        );
    }
}
