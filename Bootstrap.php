<?php

namespace crudschool\bootstrap;

use yii\base\Application;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface {
	/**
	 * Bootstrap method to be called during application bootstrap stage.
	 *
	 * @param Application $app the application currently running
	 */
	public function bootstrap($app) {
		foreach ($this->getModules() as $module) {
			$app->setModule($module, ['class' => 'crudschool\\modules\\' . $module . '\\Module']);
		}
	}
	
	private function getModules() {
		return array_diff(scandir(__DIR__ . DIRECTORY_SEPARATOR . 'modules'), ['.', '..']);
	}
}
