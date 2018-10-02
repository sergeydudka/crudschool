<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 02.09.2018
 * Time: 18:41
 */

namespace crudschool\models\relationship;

use crudschool\models\RelationshipField;
use crudschool\modules\alias\models\Alias;

class AliasRelationshipField extends RelationshipField {
	public function __construct() {
		parent::__construct([
			'model' => Alias::class,
			'field' => 'alias_id',
			'label' => 'code',
			'type' => self::HAS_ONE_REL,
			'method' => 'alias'
		]);
	}
}