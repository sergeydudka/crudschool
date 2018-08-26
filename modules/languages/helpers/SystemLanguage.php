<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 04.08.2018
 * Time: 18:58
 */

namespace crudschool\modules\languages\helpers;

use \crudschool\modules\languages\models\Language;
use yii\base\BaseObject;

class SystemLanguage extends BaseObject {
	const DEFAULT_LANGUAGE = 'ru';
	
	/** @var LanguageModel self::$language */
	protected static $language = NULL;
	
	public function init() {
		parent::init();
	}
	
	public function __clone() {
		return self::getInstance();
	}
	
	public static function getInstance() {
		return self::$language;
	}
	
	private static function setLanguage($language = self::DEFAULT_LANGUAGE) {
		if ($language instanceof Language) {
			self::$language = $language;
		} else {
			$language = Language::find()->where(['url' => $language])->limit(1)->one();
			if ($language !== NULL) {
				self::$language = $language;
			}
		}
		
		\Yii::$app->getUrlManager()->setBaseUrl(
			$language->url == self::DEFAULT_LANGUAGE ? \Yii::$app->getUrlManager()->getBaseUrl() : \Yii::$app->getUrlManager()->getBaseUrl() . '/' . $language->url . '/');
		
		return (bool)$language;
	}
	
	public function getLanguage() {
		if (self::$language === NULL) {
			$this->setLanguage();
		}
		return self::$language;
	}
	
	public static function parseLanguage($url) {
		$parts = explode('/', str_replace(\Yii::$app->getHomeUrl(), '', trim($url, '/')));
		if (empty($parts)) {
			return self::DEFAULT_LANGUAGE;
		}
		
		if ($parts[0] == self::DEFAULT_LANGUAGE) {
			$url = \Yii::$app->getHomeUrl() . strtr($url, [self::DEFAULT_LANGUAGE . '/' => '']);
			\Yii::$app->response->redirect($url, 301, false);
		}
		
		if (self::setLanguage($parts[0])) {
			return self::$language->url;
		}
	}
}