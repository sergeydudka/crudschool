<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 04.08.2018
 * Time: 18:58
 */

namespace crudschool\common\edition;

use \crudschool\modules\editions\models\Edition as Editions;
use yii\base\BaseObject;
use yii\web\NotFoundHttpException;

class Edition extends BaseObject {
	const DEFAULT_EDITION_URL = 'ru';
	
	/** @var EditionModel self::$currentEdition */
	protected static $currentEdition;
	
	public function init() {
		parent::init();
	}
	
	public function __clone() {
		return self::getInstance();
	}
	
	public static function getInstance() {
		return self::$currentEdition;
	}
	
	private static function setEdition($edition = self::DEFAULT_EDITION_URL) {
		if (!($edition instanceof Editions)) {
			$edition = Editions::find()->where(['url' => $edition])->limit(1)->one();
			if (!$edition) {
				$edition = Editions::find()->where(['url' => self::DEFAULT_EDITION_URL])->limit(1)->one();
			}
		}
		
		self::$currentEdition = $edition;
		
		\Yii::$app->getUrlManager()->setBaseUrl(
			$edition->url == self::DEFAULT_EDITION_URL ? \Yii::$app->getUrlManager()->getBaseUrl() : \Yii::$app->getUrlManager()->getBaseUrl() . '/' . $edition->url . '/');
		
		return (bool)$edition;
	}
	
	public function getEdition() {
		if (self::$currentEdition === NULL) {
			$this->setEdition();
		}
		return self::$currentEdition;
	}
	
	public static function parse($url) {
		$parts = explode('/', str_replace(\Yii::$app->getHomeUrl(), '', trim($url, '/')));
		if (empty($parts)) {
			return self::DEFAULT_EDITION_URL;
		}
		
		if ($parts[0] == self::DEFAULT_EDITION_URL) {
			$url = \Yii::$app->getHomeUrl() . strtr($url, [self::DEFAULT_EDITION_URL . '/' => '']);
			\Yii::$app->response->redirect($url, 301, false);
		}
		
		if (self::setEdition($parts[0])) {
			return self::$currentEdition->url;
		}
	}
	
	public function __get($name) {
		if (self::$currentEdition) {
			$getter = 'get' . $name;
			if (self::$currentEdition->hasAttribute($name)) {
				return self::$currentEdition->getAttribute($name);
			} elseif (method_exists(self::$currentEdition, $getter)) {
				return $this->$getter();
			}
		}
		
		return parent::__get($name);
	}
}