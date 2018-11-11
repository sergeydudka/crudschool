<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 08.09.2018
 * Time: 10:43
 */

namespace crudschool\api;


use crudschool\models\RelationshipModel;
use yii\base\Arrayable;
use yii\db\ActiveRecord;
use yii\db\ColumnSchema;
use yii\db\Expression;
use yii\db\ExpressionInterface;
use yii\rest\CreateAction;
use yii\rest\DeleteAction;
use yii\rest\UpdateAction;
use yii\validators\EachValidator;
use yii\validators\Validator;

class FieldCollection implements Arrayable {
    public $fields = [];
    public $model;

    private $schema;
    private $relationships;
    private $labels;
    private $displayFields;
    private $validators;

    const DISALLOW_ACTIONS = [
        UpdateAction::class,
        CreateAction::class,
        DeleteAction::class,
    ];

    public function __construct(ActiveRecord $model) {
        $actionName = get_class(\Yii::$app->controller->action);

        $isOptionsRequest = \Yii::$app->getRequest()->isOptions;

        if (!$isOptionsRequest && $this->isDisallowAction($actionName)) {
            return;
        }

        $this->model = $model;
        $this->schema = $model->getTableSchema();
        $this->relationships = ($model instanceof RelationshipModel) ? $model->relationships() : [];
        $this->labels = $model->attributeLabels();
        $this->displayFields = $model->fields();

        foreach ($this->schema->columns as $key => $column) {
            $this->addField($key, $column);
        }
    }

    /**
     * @param $action
     * @return bool
     */
    public function isDisallowAction($action)
    : bool {
        $actionName = (is_object($action)) ? get_class($action) : $action;
        return in_array($action, self::DISALLOW_ACTIONS);
    }

    /**
     * @return array
     */
    public function fields()
    : array {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function extraFields()
    : array {
        return [];
    }

    /**
     * @return array
     */
    public function getFields()
    : array {
        return $this->fields;
    }

    /**
     * @param       string $key
     * @param ColumnSchema $column
     */
    public function addField($key, ColumnSchema $column)
    : void {
        /** var yii\db\mysql\ColumnSchema $column */
        $field = [];
        $field['rel'] = $this->relationships[$key] ?? false;
        $field['label'] = $this->labels[$key] ?? false;
        $field['required'] = $this->model->isAttributeRequired($column->name);
        $field['type'] = $column->type;
        $field['options'] = null;

        if ($field['rel'] && !$column->enumValues) {
            $rel = $field['rel']->getModel();
            if (is_callable([$rel, 'getDropdown'])) {
                $options = call_user_func_array([$rel, 'getDropdown'], $field['rel']->getParams());
                if (!$field['required']) {
                    $options = array_merge([0 => \Yii::t('app', 'not_set')], $options);
                }
                $field['options'] = $this->convertOptions($options);
                $field['type'] = 'list';
            } else {
                $field['options'] = null;
                $field['type'] = 'search';
            }
        } else {
            if ($column->enumValues) {
                $options = array_combine($column->enumValues, $column->enumValues);
                if (!$field['required']) {
                    $options = array_merge([0 => \Yii::t('app', 'not_set')], $options);
                }
                $field['options'] = $this->convertOptions($options);
                $field['type'] = 'list';
            } else {
                $field['options'] = null;
            }
        }


        $field['display'] = in_array($key, $this->displayFields);
        $field['name'] = $column->name;
        $field['allowNull'] = $column->allowNull;
        $field['defaultValue'] = $this->getFieldDefaultValue($column);
        $field['size'] = $column->size;
        //$field['precision'] = $column->precision;
        $field['isPrimaryKey'] = $column->isPrimaryKey;
        $field['comment'] = $column->comment;
        $field['order'] = count($this->fields);

        $this->fields[$key] = $field;
    }

    /**
     * @param $column
     * @return false|null|string
     */
    private function getFieldDefaultValue($column) {
        if ($column->defaultValue instanceof ExpressionInterface) {
            return (new \yii\db\Query)->select($column->defaultValue)->scalar();
        }

        return $column->defaultValue;
    }

    /**
     * @param array $fields
     * @param array $expand
     * @param bool  $recursive
     * @return array
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    : array {
        return $fields ? array_diff($this->getFields(), $fields) : $this->getFields();
    }

    /**
     * @param $options
     * @return array
     */
    private function convertOptions($options)
    : array {
        $result = [];
        foreach ($options as $key => $value) {
            $result[] = ['key' => $key, 'value' => $value];
        }
        return $result;
    }
}