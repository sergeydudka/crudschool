<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 02.09.2018
 * Time: 18:45
 */

namespace crudschool\models\relationship;


use crudschool\models\RelationshipField;
use crudschool\modules\users\models\User;

class UserRelationshipField extends RelationshipField {
	public function __construct() {
		parent::__construct([
			'model' => User::class,
			'field' => 'user_id',
			'label' => 'username',
			'method' => 'updated'
		]);
	}
}