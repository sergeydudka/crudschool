<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 15.09.2018
 * Time: 17:48
 */

namespace crudschool\modules\main;

class Module extends \crudschool\api\Module {
  public $controllerNamespace = 'crudschool\modules\main\controllers';
  public $defaultRoute = 'auth';
}