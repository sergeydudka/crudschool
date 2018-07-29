<?php

namespace crudschool\modules\users\controllers;

use crudschool\api\ApiController;

/**
 * Default controller for the `adminmenu` module
 */
class UserController extends ApiController {
	public $modelClass = 'crudschool\modules\users\models\User';
}
