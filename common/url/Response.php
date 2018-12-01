<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 01.12.2018
 * Time: 12:43
 */

namespace crudschool\common\url;


use crudschool\api\ApiResult;
use crudschool\common\helpers\ResponseHelper;
use yii\web\HeaderCollection;

class Response extends \yii\web\Response {
    
    public function init() {
        parent::init();
        $this->on(self::EVENT_BEFORE_SEND, [$this, 'pretierApiResponse']);
    }

    public function pretierApiResponse() {
        $exception = \Yii::$app->getErrorHandler()->exception;

        if (!$exception) {
            $data = $this->data;
        } else {
            $data = $exception;
        }
        
        if ($this->format === self::FORMAT_JSON) {
            $this->data = new ApiResult(\Yii::$app->controller, $data);
        }
    }
}