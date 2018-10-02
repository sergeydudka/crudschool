<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 02.09.2018
 * Time: 12:14
 */

namespace crudschool\models;


use crudschool\interfaces\AngularViewInterface;
use yii\rest\IndexAction;
use yii\rest\ViewAction;

class BaseModel extends ActiveModel implements AngularViewInterface {
    protected $hiddenFields = [];
    protected $fields;

    public function init() {
        parent::init();
        /* ActionIndex hiddenFields*/
        $this->setHiddenFields(IndexAction::class, 'description');

        /* ViewAction hiddenFields*/
        $this->setHiddenFields(ViewAction::class, 'created_by');
        $this->setHiddenFields(ViewAction::class, 'updated_by');
        $this->setHiddenFields(ViewAction::class, 'language_id');
        $this->setHiddenFields(ViewAction::class, 'created_at');
        $this->setHiddenFields(ViewAction::class, 'updated_at');

        /* CreateAction hiddenFields*/
        /*$this->setHiddenFields(CreateAction::class, 'created_by');
        $this->setHiddenFields(CreateAction::class, 'updated_by');
        $this->setHiddenFields(CreateAction::class, 'language_id');
        $this->setHiddenFields(CreateAction::class, 'created_at');
        $this->setHiddenFields(CreateAction::class, 'updated_at');*/

        /* UpdateAction hiddenFields*/
        /*$this->setHiddenFields(UpdateAction::class, 'created_by');
        $this->setHiddenFields(UpdateAction::class, 'updated_by');
        $this->setHiddenFields(UpdateAction::class, 'language_id');
        $this->setHiddenFields(UpdateAction::class, 'created_at');
        $this->setHiddenFields(UpdateAction::class, 'updated_at');*/
    }

    /**
     * @return array
     */
    public function fields() {
        $actionName = get_class(\Yii::$app->controller->action);
        if ($this->hasHiddenFields($actionName)) {
            return array_diff(array_keys($this->attributes), $this->getHiddenFields($actionName));
        }
        return parent::fields();
    }

    /**
     * @param string $actionName
     * @return array|mixed
     */
    public function getHiddenFields($actionName) {
        return $this->hiddenFields[$actionName] ?? [];
    }

    /**
     * @param string $actionName
     * @param string $fieldName
     */
    public function setHiddenFields($actionName, $fieldName) {
        if (!isset($this->hiddenFields[$actionName])) {
            $this->hiddenFields[$actionName] = [];
        }
        $this->hiddenFields[$actionName][] = $fieldName;
    }

    /**
     * @param string $actionName
     * @return bool
     */
    public function hasHiddenFields($actionName) {
        return !empty($this->hiddenFields[$actionName]);
    }

    /**
     * @param string $message
     * @return string
     */
    protected function t($message) {
        $translate = \Yii::t('model', $message);
        return $translate ? $translate : $message;
    }
}