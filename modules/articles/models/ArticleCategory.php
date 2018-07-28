<?php

namespace crudschool\modules\articles\models;

use common\behaviors\HTMLEncodeBehavior;
use common\behaviors\TimestampBehavior;
use common\models\RelationshipModel;
use modules\users\models\User;
use \common\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;
use common\behaviors\AliasBehavior;

/**
 * This is the model class for table "article_category".
 *
 * @property int $article_category_id
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $difficult_id
 */
class ArticleCategory extends RelationshipModel {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'article_category';
	}
	
	public function behaviors() {
		return [
			TimestampBehavior::class => [
				'class' => TimestampBehavior::class,
			],
			HTMLEncodeBehavior::class => [
				'class' => HTMLEncodeBehavior::class,
				'attributes' => [
					'description', 'title'
				]
			],
			BlameableBehavior::class => [
				'class' => BlameableBehavior::class,
			],
			AliasBehavior::class => [
				'class' => AliasBehavior::class
			]
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['description'], 'string'],
			[['created_at', 'updated_at'], 'safe'],
			[['created_by', 'updated_by'], 'integer'],
			[['title'], 'string', 'max' => 256],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'article_category_id' => 'Article Category ID',
			'title' => 'Title',
			'description' => 'Description',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'created_by' => 'Created By',
			'updated_by' => 'Updated By',
		];
	}
	
	/**
	 * @return array
	 */
	public static function getDropdown() {
		return ArrayHelper::map(self::find()->asArray(true)->all(), 'article_category_id', 'title');
	}
	
	public static function relationships() {
		return [
			'created_by' => 'created',
			'updated_by' => 'updated',
			'alias_id' => 'alias'
		];
	}
}
