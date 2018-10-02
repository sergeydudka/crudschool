<?php

namespace crudschool\modules\users\models;

use crudschool\behaviors\BlameableBehavior;
use crudschool\behaviors\TimestampBehavior;
use crudschool\models\relationship\UserRelationshipField;
use crudschool\models\RelationshipModel;

/**
 * This is the model class for table "user".
 *
 * @property int    $user_group_id
 * @property string $title
 * @property string $created_at
 * @property string $updated_at
 * @property int    $created_by
 * @property int    $updated_by
 */
class UserGroup extends RelationshipModel {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user_group';
    }

    public function behaviors() {
        return [
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class,
            ],
            BlameableBehavior::class => [
                'class' => BlameableBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_group_id', 'title', 'status'], 'required'],
            [['status', 'user_group_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'user_group_id' => $this->t('user_group_id'),
            'title'         => $this->t('title'),
            'status'        => $this->t('status'),
            'created_at'    => $this->t('created_at'),
            'updated_at'    => $this->t('updated_at'),
            'created_by'    => $this->t('created_by'),
            'updated_by'    => $this->t('updated_by'),
        ];
    }

    public static function relationships() {
        return [
            'created_by'       => new UserRelationshipField(),
            'updated_by'       => new UserRelationshipField(),
        ];
    }
}
