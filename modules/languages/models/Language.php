<?php

namespace crudschool\modules\languages\models;

use crudschool\models\BaseModel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "language".
 *
 * @property int $language_id
 * @property string $url
 * @property string $code
 * @property string $title
 * @property string $flag
 */
class Language extends BaseModel {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'language';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['url', 'code', 'title'], 'required'],
			[['url'], 'string', 'max' => 3],
			[['code'], 'string', 'max' => 50],
			[['title'], 'string', 'max' => 256],
			[['flag'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
		];
	}
	
	public function upload() {
		if ($this->validate()) {
			$path = 'uploads/' . $this->flag->baseName . '.' . $this->flag->extension;
			/** @var UploadedFile $flag */
			$flag = $this->flag;
			if ($this->flag->saveAs($path)) {
				$this->flag = $path;
			}
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'language_id' => 'Language ID',
			'url' => 'Url',
			'code' => 'Code',
			'title' => 'Title',
			'flag' => 'Flag',
		];
	}
	
	/**
	 * @return array
	 */
	public static function getDropdown() {
		return ArrayHelper::map(self::find()->asArray(true)->all(), 'language_id', 'title');
	}
}
