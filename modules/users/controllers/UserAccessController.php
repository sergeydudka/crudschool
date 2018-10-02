<?php

namespace crudschool\modules\users\controllers;

use crudschool\api\ApiController;
use yii\filters\VerbFilter;


class UserAccessController extends ApiController {
	public $modelClass = 'crudschool\modules\users\models\UserAccess';

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
