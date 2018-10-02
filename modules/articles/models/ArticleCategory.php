<?php

namespace crudschool\modules\articles\models;

use crudschool\behaviors\HTMLEncodeBehavior;
use crudschool\behaviors\TimestampBehavior;
use crudschool\behaviors\BlameableBehavior;
use crudschool\behaviors\AliasBehavior;
use crudschool\models\relationship\AliasRelationshipField;
use crudschool\models\relationship\UserRelationshipField;
use crudschool\models\RelationshipModel;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article_category".
 *
 * @property int    $article_category_id
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property int    $created_by
 * @property int    $updated_by
 * @property int    $difficult_id
 * @property int    $alias_id
 */
class ArticleCategory extends RelationshipModel {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'article_category';
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
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'alias_id'], 'integer'],
            [['title'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'article_category_id' => $this->t('article_category_id'),
            'title'               => $this->t('title'),
            'description'         => $this->t('description'),
            'created_at'          => $this->t('created_at'),
            'updated_at'          => $this->t('updated_at'),
            'created_by'          => $this->t('created_by'),
            'updated_by'          => $this->t('updated_by'),
            'alias_id'            => $this->t('alias_id'),
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
            'created_by' => new UserRelationshipField(),
            'updated_by' => new UserRelationshipField(),
            'alias_id'   => new AliasRelationshipField(),
        ];
    }
}
