<?php

namespace crudschool\modules\users\controllers;

use crudschool\api\ApiController;
use yii\filters\VerbFilter;

/**
 * Default controller for the `adminmenu` module
 */
class UserGroupController extends ApiController {
	public $modelClass = 'crudschool\modules\users\models\UserGroup';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
}
