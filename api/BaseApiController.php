<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 05.08.2018
 * Time: 11:47
 */

namespace crudschool\api;

use crudschool\api\data\DataSelect;
use crudschool\common\edition\Edition;
use crudschool\common\helpers\JSONHelper;
use crudschool\interfaces\AngularModelInterface;
use crudschool\interfaces\AngularViewInterface;
use crudschool\modules\languages\helpers\SystemLanguage;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\data\DataFilter;
use yii\rest\ActiveController;
use yii\web\Controller;
use \Yii;

class BaseApiController extends ActiveController {
    public $dataFilter;
    private $requestParams;
    private $filter = [];

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {
        parent::init();
    }

    /**
     * @return Edition
     */
    public function getEdition() {
        return \Yii::$app->request->getEdition();
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

        $filter = new DataFilter();
        $filterParams = JSONHelper::parse($requestParams[$filter->filterAttributeName], true);
        if ($filterParams && $filter->load([$filter->filterAttributeName => $filterParams], '')) {
            $filter->setSearchModel($model);
            $filter = $filter->build();
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