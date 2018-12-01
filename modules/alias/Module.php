<?php

namespace crudschool\modules\alias;

/**
 * adminmenu module definition class
 */
class Module extends \crudschool\api\Module {
	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'crudschool\modules\alias\controllers';
	public $defaultRoute = 'alias';
}
