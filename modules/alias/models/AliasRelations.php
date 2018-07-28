<?php

namespace crudschool\modules\alias\models;

use Yii;

/**
 * This is the model class for table "alias_relations".
 *
 * @property int $alias_id
 * @property int $rel_id
 */
class AliasRelations extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'alias_relations';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['alias_id', 'rel_id'], 'required'],
			[['alias_id', 'rel_id'], 'integer'],
			[['alias_id'], 'unique'],
			[['rel_id'], 'unique'],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'alias_id' => 'Alias ID',
			'rel_id' => 'Rel Alias ID',
		];
	}
}
