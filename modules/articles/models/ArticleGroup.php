<?php

namespace crudschool\modules\articles\models;

use common\behaviors\HTMLEncodeBehavior;
use common\behaviors\TimestampBehavior;
use common\models\RelationshipModel;
use common\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article_group".
 *
 * @property int $article_group_id
 * @property int $parent_id
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property string $required
 */
class ArticleGroup extends RelationshipModel {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'article_group';
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
				'class' => BlameableBehavior::class
			]
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['description'], 'string'],
			[['required'], 'string'],
			[['created_at', 'updated_at'], 'safe'],
			[['created_by', 'updated_by', 'parent_id', 'difficult_id'], 'integer'],
			[['title'], 'string', 'max' => 256],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'article_group_id' => 'Article Group ID',
			'parent_id' => 'Parent ID',
			'article_category_id' => 'Article Category ID',
			'title' => 'Title',
			'description' => 'Description',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'created_by' => 'Created By',
			'updated_by' => 'Updated By',
			'difficult_id' => 'Difficult',
			'required' => 'Required',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getParent() {
		return $this->hasOne(ArticleGroup::class, ['article_group_id' => 'parent_id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticleCategory() {
		return $this->hasOne(ArticleCategory::class, ['article_category_id' => 'article_category_id']);
	}
	
	/**
	 * @return array
	 */
	public static function getDropdown() {
		return ArrayHelper::map(self::find()->asArray(true)->all(), 'article_group_id', 'title');
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDifficult() {
		return $this->hasOne(Difficult::class,
			[
				'difficult_id' => 'difficult_id'
			]
		);
	}
	
	public static function relationships() {
		return [
			'difficult_id' => 'difficult',
			'article_category_id' => 'articleCategory',
			'parent_id' => 'parent',
			'created_by' => 'created',
			'updated_by' => 'updated'
		];
	}
}
