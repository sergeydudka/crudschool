<?php

namespace crudschool\modules\editions;

/**
 * adminmenu module definition class
 */
class Module extends \crudschool\api\Module {
	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'crudschool\modules\editions\controllers';
	public $defaultRoute = 'edition';
}
