<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 02.09.2018
 * Time: 18:44
 */

namespace crudschool\models\relationship;


use crudschool\models\RelationshipField;
use crudschool\modules\articles\models\ArticleGroup;

class ArticleGroupRelationshipField extends RelationshipField {
	public function __construct() {
		parent::__construct([
			'model' => ArticleGroup::class,
			'field' => 'article_group_id',
			'label' => 'title',
			'method' => 'articleGroup'
		]);
	}
}