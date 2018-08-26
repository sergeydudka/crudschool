<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 22.07.2018
 * Time: 14:56
 */

namespace crudschool\behaviors;


use crudschool\modules\alias\models\Alias;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\Inflector;

class AliasBehavior extends Behavior {
	/* @var $owner ActiveRecord */
	
	const ALIAS_MODEL = Alias::class;
	/* @var $alias Alias */
	private $alias;
	
	private $aliasField = 'alias';
	public $from;
	
	public function events() {
		return [
			ActiveRecord::EVENT_BEFORE_VALIDATE => 'setAlias',
			ActiveRecord::EVENT_AFTER_INSERT => 'saveAlias',
			ActiveRecord::EVENT_AFTER_FIND => 'getAlias',
		];
	}
	
	public function init() {
		parent::init();
	}
	
	public function getRefID() {
		/* @var $owner BaseActiveRecord */
		$owner = $this->owner;
		return $owner->getPrimaryKey();
	}
	
	public function saveAlias() {
		if ($this->alias) {
			$this->alias->ref_id = $this->owner->getPrimaryKey(false);
			$this->alias->save();
		}
	}
	
	public function setAlias() {
		$aliasID = $this->owner->alias_id;
		$code = trim($this->getPostData());
		if (!$code && $this->from && $this->owner[$this->from]) {
			$code = \crudschool\common\helpers\Transliteration::text($this->owner[$this->from], '-');
			
			$code = strtolower($code);
		}
		$alias = null;
		
		if ($aliasID) {
			$alias = Alias::findOne(['alias_id' => $aliasID]);
		} elseif ($code) {
			$alias = Alias::findOne(['code' => $code, 'ref_model' => get_class($this->owner)]);
			if ($alias) {
				$this->owner->addError($this->aliasField, "Code $code has already been taken by model " . get_class($this->owner) . ".");
				return false;
			}
		}
		
		if ($alias && $code) {
			$alias->code = $code;
		} elseif ($code) {
			$alias = $this->createAlias($code);
		}
		
		$alias->ref_id = $this->owner->getPrimaryKey(false);
		
		$result = $alias->save();
		
		if (!$result) {
			$this->owner->addError($this->aliasField, implode('<br>', $alias->getFirstErrors()));
			return false;
		}
		
		$this->owner->alias_id = $alias->alias_id;
		
		$this->alias = $alias;
		
		return true;
	}
	
	public function createAlias($code) {
		return Alias::setAlias($this->getRefID(), get_class($this->owner), $code);
	}
	
	public function getAlias() {
		return Alias::findOne(['alias_id' => $this->owner->alias_id])->code;
	}
	
	private function getPostData() {
		$post = \Yii::$app->request->post();
		$className = (new \ReflectionClass($this->owner))->getShortName();
		return $post[$className][$this->aliasField] ?? NULL;
	}
}