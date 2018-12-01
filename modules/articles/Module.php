<?php

namespace crudschool\modules\articles;

/**
 * article module definition class
 */
class Module extends \crudschool\api\Module {
	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'crudschool\modules\articles\controllers';
	public $defaultRoute = 'article';
}
