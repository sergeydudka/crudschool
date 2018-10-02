<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 02.09.2018
 * Time: 18:43
 */

namespace crudschool\models\relationship;


use crudschool\models\RelationshipField;
use crudschool\modules\articles\models\Difficult;

class DifficultRelationshipField extends RelationshipField {
	public function __construct($difficultType) {
		parent::__construct([
			'model' => Difficult::class,
			'field' => 'difficult_id',
            'type' => self::HAS_ONE_REL,
			'label' => 'title',
			'method' => 'difficult',
			'params' => [$difficultType],
		]);
	}
}