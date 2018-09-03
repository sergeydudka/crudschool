<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 02.09.2018
 * Time: 19:21
 */

namespace crudschool\models\relationship;


use crudschool\models\RelationshipField;
use crudschool\modules\articles\models\ArticleCategory;

class ArticleCategoryRelationshipField extends RelationshipField {
	public function __construct() {
		parent::__construct([
			'model' => ArticleCategory::class,
			'field' => 'article_category_id',
			'label' => 'title',
			'method' => 'articleCategory'
		]);
	}
}