<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 02.09.2018
 * Time: 18:45
 */

namespace crudschool\models\relationship;


use crudschool\models\RelationshipField;
use crudschool\modules\users\models\UserGroup;

class UserGroupRelationshipField extends RelationshipField {
	public function __construct() {
		parent::__construct([
			'model' => UserGroup::class,
			'field' => 'user_group_id',
            'type' => self::HAS_ONE_REL,
			'label' => 'user group',
			'method' => 'userGroup'
		]);
	}
}