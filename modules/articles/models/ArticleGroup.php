<?php

namespace crudschool\modules\articles\models;

use crudschool\behaviors\HTMLEncodeBehavior;
use crudschool\behaviors\TimestampBehavior;
use crudschool\behaviors\AliasBehavior;
use crudschool\models\relationship\ArticleCategoryRelationshipField;
use crudschool\models\relationship\DifficultRelationshipField;
use crudschool\models\relationship\UserRelationshipField;
use crudschool\models\RelationshipModel;
use crudschool\behaviors\BlameableBehavior;

/**
 * This is the model class for table "article_group".
 *
 * @property int    $article_group_id
 * @property int    $parent_id
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property int    $created_by
 * @property int    $updated_by
 * @property string $required
 * @property int    $alias_id
 */
class ArticleGroup extends RelationshipModel {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'article_group';
    }

    /**
     * @return array
     */
    public function behaviors() {
        return [
            TimestampBehavior::class  => [
                'class' => TimestampBehavior::class,
            ],
            HTMLEncodeBehavior::class => [
                'class'      => HTMLEncodeBehavior::class,
                'attributes' => [
                    'description',
                    'title',
                ],
            ],
            BlameableBehavior::class  => [
                'class' => BlameableBehavior::class,
            ],
            AliasBehavior::class      => [
                'class' => AliasBehavior::class,
                'from'  => 'title',
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
            'article_group_id'    => $this->t('article_group_id'),
            'parent_id'           => $this->t('parent_id'),
            'article_category_id' => $this->t('article_category_id'),
            'title'               => $this->t('title'),
            'description'         => $this->t('description'),
            'created_at'          => $this->t('created_at'),
            'updated_at'          => $this->t('updated_at'),
            'created_by'          => $this->t('created_by'),
            'updated_by'          => $this->t('updated_by'),
            'difficult_id'        => $this->t('difficult_id'),
            'required'            => $this->t('required'),
            'alias_id'            => $this->t('alias_id'),
        ];
    }

    /**
     * @return array
     */
    public static function relationships() {
        return [
            'difficult_id'        => new DifficultRelationshipField(Difficult::TYPE_ARTICLE_GROUP_DIFFICULT),
            'article_category_id' => new ArticleCategoryRelationshipField(),
            'parent_id'           => new ArticleCategoryRelationshipField(),
            'created_by'          => new UserRelationshipField(),
            'updated_by'          => new UserRelationshipField(),
        ];
    }
}
