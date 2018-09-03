<?php

namespace crudschool\modules\users\models;

use crudschool\models\BaseModel;
use Yii;
use yii\rest\CreateAction;
use yii\rest\IndexAction;
use yii\rest\UpdateAction;

/**
 * This is the model class for table "user".
 *
 * @property int $user_id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class User extends BaseModel {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'user';
	}
	
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
			[['username', 'auth_key', 'password_hash', 'email'], 'required'],
			[['status'], 'integer'],
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
			'user_id' => 'User ID',
			'username' => 'Username',
			'auth_key' => 'Auth Key',
			'password_hash' => 'Password Hash',
			'password_reset_token' => 'Password Reset Token',
			'email' => 'Email',
			'status' => 'Status',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		];
	}
}
