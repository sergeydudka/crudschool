<?php

namespace crudschool\modules\articles\models;

use crudschool\behaviors\AliasBehavior;
use crudschool\behaviors\HTMLEncodeBehavior;
use crudschool\behaviors\LanguageBehavior;
use crudschool\behaviors\TimestampBehavior;
use crudschool\models\relationship\AliasRelationshipField;
use crudschool\models\relationship\ArticleGroupRelationshipField;
use crudschool\models\relationship\DifficultRelationshipField;
use crudschool\models\relationship\UserRelationshipField;
use crudschool\models\RelationshipField;
use crudschool\models\RelationshipModel;
use crudschool\modules\alias\models\Alias;
use crudschool\modules\languages\models\Language;
use crudschool\behaviors\BlameableBehavior;
use crudschool\modules\users\models\User;
use yii\rest\CreateAction;
use yii\rest\IndexAction;
use yii\rest\UpdateAction;

/**
 * This is the model class for table "article".
 *
 * @property int $article_id
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $status
 * @property int $article_group_id
 * @property int $language_id
 * @property int $created_by
 * @property int $updated_by
 * @property int $difficult_id
 * @property int $alias_id
 */
class Article extends RelationshipModel {
	
	const STATUS_DEFAULT = 'waiting';
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'article';
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
				'class' => AliasBehavior::class,
				'from' => 'title'
			]
		];
	}
	
	public function init() {
		if ($this->status === null) {
			$this->status = self::STATUS_DEFAULT;
		}
		
		/*if ($this->language_id === null) {
			$this->language_id = \Yii::$app->language;
		}*/
		parent::init();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['description', 'status', 'title'], 'required'],
			[['description', 'status'], 'string'],
			[['created_at', 'updated_at'], 'safe'],
			[['created_by', 'updated_by', 'article_group_id', 'difficult_id', 'language_id', 'alias_id'], 'integer'],
			[['title'], 'string', 'max' => 512],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'article_id' => 'Article ID',
			'title' => 'Title',
			'description' => 'Description',
			'created_at' => 'Date Create',
			'updated_at' => 'Date Update',
			'created_by' => 'Created By',
			'updated_by' => 'Updated By',
			'status' => 'Status',
			'article_group_id' => 'Article Group',
			'difficult_id' => 'Difficult',
			'language_id' => 'Language',
			'alias_id' => 'Alias',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticleGroup() {
		return $this->hasOne(ArticleGroup::class, ['article_group_id' => 'article_group_id']);
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
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLanguage() {
		return $this->hasOne(Language::class,
			[
				'language_id' => 'language_id'
			]
		);
	}
	
	public static function relationships() {
		return [
			'difficult_id' => new DifficultRelationshipField(),
			'article_group_id' => new ArticleGroupRelationshipField(),
			'created_by' => new UserRelationshipField(),
			'updated_by' => new UserRelationshipField(),
			'alias_id' => new AliasRelationshipField(),
		];
	}
}
