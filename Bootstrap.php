<?php

namespace crudschool\bootstrap;

use yii\base\Application;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface {
	private $moduleList = [
		'alias',
		'articles',
		'languages',
		'menu',
		'users'
	];
	/**
	 * Bootstrap method to be called during application bootstrap stage.
	 *
	 * @param Application $app the application currently running
	 */
	public function bootstrap($app) {
		foreach ($this->moduleList as $module) {
			$app->setModule($module, ['class' => 'crudschool\\modules\\' . $module . '\\Module']);
		}
	}
}
