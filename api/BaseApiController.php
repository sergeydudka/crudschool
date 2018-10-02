<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 05.08.2018
 * Time: 11:47
 */

namespace crudschool\api;

use crudschool\api\data\DataSelect;
use crudschool\common\helpers\JSONHelper;
use crudschool\common\url\Request;
use crudschool\modules\editions\models\Edition;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;
use yii\data\DataFilter;
use yii\rest\ActiveController;
use \Yii;

class BaseApiController extends ActiveController {
    public $dataFilter;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {
        parent::init();
    }

    /**
     * @return Edition
     * @throws \yii\base\InvalidConfigException
     */
    public function getEdition() {
        /**
         * @var Request $request;
         */
        $request =  \Yii::$app->request;
        return $request->getEdition();
    }

    /**
     * @return array
     */
    public function actions() {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'indexDataProvider'];

        return $actions;
    }

    /**
     * @param $action
     * @param $filter
     * @return BaseObject
     * @throws \yii\base\InvalidConfigException
     */
    public function indexDataProvider($action, $filter) {
        $requestParams = Yii::$app->getRequest()->getQueryParams();

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;
        $model = new $this->modelClass();

        $dataSelect = new DataSelect();
        $select = false;
        if ($dataSelect->parse($requestParams)) {
            $select = $dataSelect->build($model);
        }

        $dataFilter = new DataFilter();
        $filter = false;
        $filterParams = JSONHelper::parse($requestParams[$dataFilter->filterAttributeName], true);
        if ($filterParams && $dataFilter->load([$dataFilter->filterAttributeName => $filterParams], '')) {
            $dataFilter->setSearchModel($model);
            $filter = $dataFilter->build();
        }


        $query = $modelClass::find();

        if ($select && !empty($select)) {
            $query->select($select);
        }

        if ($filter && !empty($filter)) {
            $query->andWhere($filter);
        }

        return Yii::createObject([
            'class'      => ActiveDataProvider::className(),
            'query'      => $query,
            'pagination' => ['params' => $requestParams],
            'sort'       => ['params' => $requestParams],
        ]);
    }
}