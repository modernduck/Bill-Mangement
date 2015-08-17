<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "template".
 *
 * @property integer $id
 * @property string $name
 * @property string $preset_json
 * @property string $main_path
 * @property string $create_time
 * @property string $update_time
 *
 * @property Document[] $documents
 */
class Template extends \yii\db\ActiveRecord
{

    public $html_file;
    public $js_file;
    public $css_file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'preset_json'], 'required'],
            [['html_file'], 'file','skipOnEmpty' => true, 'extensions' => 'html, htm'],
            [['js_file'], 'file', 'skipOnEmpty' => true ],
            [['css_file'], 'file', 'skipOnEmpty' => true ],
            [['preset_json', 'main_path'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'preset_json' => 'Preset Json',
            'main_path' => 'Main Path',
            'html_file' => 'HTML',
            'js_file' => 'Java Script',
            'css_file' => 'Css',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }


    public function upload($file_name)
    {

        
        if ($this->validate() && FileHelper::createDirectory("templates/".$file_name, 0777) ) {
            $this->html_file->saveAs('templates/' . $file_name . '/index.' . $this->html_file->extension);
            $this->js_file->saveAs('templates/' . $file_name . '/main.js');
            $this->css_file->saveAs('templates/' . $file_name . '/style.css');
            $this->main_path = $file_name;
            return true;
        } else {

            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['template_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        $this->update_time = date('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }
}
