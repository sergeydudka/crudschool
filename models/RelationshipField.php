<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 01.09.2018
 * Time: 19:06
 */

namespace crudschool\models;


use yii\base\BaseObject;
use yii\base\InvalidConfigException;

class RelationshipField extends BaseObject {

    const HAS_ONE_REL = 'hasOne';
    const HAS_MANY_REL = 'hasMany';

    /**
     * @var string
     */
	public $field;

    /**
     * @var string
     */
	public $label;

    /**
     * @var string
     */
	public $model;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
	public $method;

    /**
     * @var array
     */
	public $params;

    /**
     * @var array
     */
    public $config;

    /**
     * RelationshipField constructor.
     * @param array $config
     */
    public function __construct($config = []) {
		parent::__construct($config);

		if (!$this->type || ($this->type != self::HAS_ONE_REL && $this->type != self::HAS_MANY_REL)) {
		    throw new InvalidConfigException("Type must be '" . self::HAS_ONE_REL . "' or '" . self::HAS_MANY_REL . "'. $this->type present");
        }

        $this->config = $config;
    }


    /**
     * @return string
     */
    public function getField() {
		return $this->field;
	}

    /**
     * @return string
     */
    public function getLabel() {
		return $this->label;
	}

    /**
     * @return string
     */
    public function getModel() {
		return $this->model;
	}

    /**
     * @return string
     */
    public function getMethod() {
		return $this->method;
	}

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getParams() {
		return $this->params ? $this->params : [];
	}
}