<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 28.07.2018
 * Time: 14:44
 */

namespace crudschool\behaviors;


use crudschool\users\models\User;
use yii\db\ActiveRecord;

class BlameableBehavior extends \yii\behaviors\BlameableBehavior {
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreated() {
		/* @var $owner ActiveRecord */
		$owner = $this->owner;
		return $owner->hasOne(User::class, ['user_id' => 'created_by']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUpdated() {
		/* @var $owner ActiveRecord */
		$owner = $this->owner;
		return $owner->hasOne(User::class, ['user_id' => 'updated_by']);
	}
}