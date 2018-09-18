<?php

namespace crudschool\modules\articles\models;

use crudschool\behaviors\HTMLEncodeBehavior;
use crudschool\behaviors\TimestampBehavior;
use crudschool\behaviors\AliasBehavior;
use crudschool\interfaces\AngularModelInterface;
use crudschool\models\relationship\ArticleCategoryRelationshipField;
use crudschool\models\relationship\ArticleGroupRelationshipField;
use crudschool\models\relationship\DifficultRelationshipField;
use crudschool\models\relationship\UserRelationshipField;
use crudschool\models\RelationshipModel;
use crudschool\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;
use yii\rest\CreateAction;
use yii\rest\IndexAction;
use yii\rest\UpdateAction;

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
 * @property int $alias_id
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
			],
			AliasBehavior::class => [
				'class' => AliasBehavior::class,
				'from' => 'title'
			],
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
			[['created_by', 'updated_by', 'parent_id', 'difficult_id', 'alias_id'], 'integer'],
			[['title'], 'string', 'max' => 256],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'article_group_id' => 'Article Group ID',
			'parent_id' => 'Parent',
			'article_category_id' => 'Article Category',
			'title' => 'Title',
			'description' => 'Description',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'created_by' => 'Created By',
			'updated_by' => 'Updated By',
			'difficult_id' => 'Difficult',
			'required' => 'Required',
			'alias_id' => 'Alias',
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
	/*public static function getDropdown() {
		return ArrayHelper::map(self::find()->asArray(true)->all(), 'article_group_id', 'title');
	}*/
	
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
			'difficult_id' => new DifficultRelationshipField(),
			'article_category_id' => new ArticleCategoryRelationshipField(),
			'parent_id' => new ArticleCategoryRelationshipField(),
			'created_by' => new UserRelationshipField(),
			'updated_by' => new UserRelationshipField(),
		];
	}
}
