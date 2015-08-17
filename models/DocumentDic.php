<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_dic".
 *
 * @property integer $template_id
 * @property string $index_js_name
 * @property string $index_display_name
 *
 * @property Template $template
 */
class DocumentDic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_dic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id', 'index_js_name', 'index_display_name'], 'required'],
            [['template_id'], 'integer'],
            [['index_js_name', 'index_display_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'template_id' => 'Template ID',
            'index_js_name' => 'Index Js Name',
            'index_display_name' => 'Index Display Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }
}
