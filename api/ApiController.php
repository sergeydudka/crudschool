<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 01.07.2018
 * Time: 11:02
 */

namespace crudschool\api;

use crudschool\common\helpers\AccessHelper;
use crudschool\modules\languages\helpers\SystemLanguage;
use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\grid\SerialColumn;
use yii\rest\ActiveController;
use yii\rest\Serializer;
use yii\web\ForbiddenHttpException;
use yii\web\Response;


abstract class ApiController extends BaseApiController {
    public $user;

    /**
     * ApiController constructor.
     * @param string $id
     * @param        $module
     * @param array  $config
     */
    public function __construct($id, $module, array $config = []) {
        parent::__construct($id, $module, $config);
        $this->user = \Yii::$app->getUser()->getIdentity();
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\base\ExitException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (\Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            \Yii::$app->getResponse()->data = [];
            \Yii::$app->end();
        }

        return true;
    }
}