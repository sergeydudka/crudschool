<?php

namespace crudschool\modules\users\models;

use crudschool\behaviors\TimestampBehavior;
use crudschool\models\BaseModel;
use crudschool\models\relationship\UserGroupRelationshipField;
use crudschool\models\RelationshipModel;
use yii\rest\CreateAction;
use yii\rest\IndexAction;
use yii\rest\UpdateAction;

/**
 * This is the model class for table "user".
 *
 * @property int    $user_id
 * @property int    $user_group_id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int    $status
 * @property string $created_at
 * @property string $updated_at
 */
class User extends RelationshipModel {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user';
    }

    public function behaviors() {
        return [
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class
            ]
        ];
    }

    /**
     * @return void
     */
    public function init() {
        parent::init();

        $this->setHiddenFields(IndexAction::class, 'auth_key');
        $this->setHiddenFields(CreateAction::class, 'auth_key');
        $this->setHiddenFields(UpdateAction::class, 'auth_key');

        $this->setHiddenFields(IndexAction::class, 'password_hash');
        $this->setHiddenFields(CreateAction::class, 'password_hash');
        $this->setHiddenFields(UpdateAction::class, 'password_hash');

        $this->setHiddenFields(IndexAction::class, 'password_reset_token');
        $this->setHiddenFields(CreateAction::class, 'password_reset_token');
        $this->setHiddenFields(UpdateAction::class, 'password_reset_token');

        $this->setHiddenFields(IndexAction::class, 'password_reset_token');
        $this->setHiddenFields(CreateAction::class, 'password_reset_token');
        $this->setHiddenFields(UpdateAction::class, 'password_reset_token');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'user_group_id'], 'required'],
            [['status', 'user_group_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'user_id'              => $this->t('user_id'),
            'user_group_id'        => $this->t('user_group_id'),
            'username'             => $this->t('username'),
            'auth_key'             => $this->t('auth_key'),
            'password_hash'        => $this->t('password_hash'),
            'password_reset_token' => $this->t('password_reset_token'),
            'email'                => $this->t('email'),
            'status'               => $this->t('status'),
            'created_at'           => $this->t('created_at'),
            'updated_at'           => $this->t('updated_at'),
        ];
    }

    public static function relationships() {
        return [
            'user_group_id' => new UserGroupRelationshipField(),
        ];
    }
}
