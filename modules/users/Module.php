<?php

namespace crudschool\modules\users;

/**
 * adminmenu module definition class
 */
class Module extends \yii\base\Module {
	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'crudschool\modules\users\controllers';
	public $defaultRoute = 'user';
}
