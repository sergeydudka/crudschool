<?php

namespace crudschool\modules\translations;

/**
 * adminmenu module definition class
 */
class Module extends \crudschool\api\Module {
	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'crudschool\modules\translations\controllers';
	public $defaultRoute = 'translation';
}
