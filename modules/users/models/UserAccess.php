<?php

namespace crudschool\modules\users\models;
use crudschool\behaviors\BlameableBehavior;
use crudschool\behaviors\TimestampBehavior;
use crudschool\models\relationship\UserRelationshipField;
use crudschool\models\RelationshipModel;


/**
 * This is the model class for table "user_access".
 *
 * @property int    $id
 * @property int    $user_group_id
 * @property string $entity_name
 * @property string $action
 * @property int    $created_by
 * @property int    $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class UserAccess extends RelationshipModel {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user_access';
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
            [['user_group_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['entity_name', 'action'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id'            => $this->t('id'),
            'user_group_id' => $this->t('user_group_id'),
            'entity_name'   => $this->t('entity_name'),
            'action'        => $this->t('action'),
            'created_by'    => $this->t('created_by'),
            'updated_by'    => $this->t('updated_by'),
            'created_at'    => $this->t('created_at'),
            'updated_at'    => $this->t('updated_at'),
        ];
    }

    public static function relationships() {
        return [
            'created_by'       => new UserRelationshipField(),
            'updated_by'       => new UserRelationshipField(),
        ];
    }
}
