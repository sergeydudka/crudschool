<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 04.08.2018
 * Time: 18:58
 */

namespace crudschool\common\url;

use crudschool\common\helpers\EditionHelper;
use crudschool\modules\editions\models\Edition;
use yii\base\BaseObject;

class UrlResolver extends BaseObject {
  private $currentUrl;
  private $edition;
  private $config;

  const DEFAULT_EDITION_URL = 'ru';

  /**
   * @throws \yii\base\InvalidConfigException
   */
  public function init():void {
    parent::init();

    $this->config = \Yii::$app->params['edition'];

    $this->currentUrl = trim(\Yii::$app->getRequest()->getUrl(), '/');
    $parts = explode('/', str_replace(\Yii::$app->getHomeUrl(), '', $this->currentUrl));

    if (empty($parts)) {
      $this->edition = EditionHelper::getDefaultEdition();
    } else {
      if ($parts[0] == self::DEFAULT_EDITION_URL) {
        $url = \Yii::$app->getHomeUrl() . strtr($url, [self::DEFAULT_EDITION_URL . '/' => '']);
        \Yii::$app->response->redirect($url, 301, FALSE);
      }

      if (strlen($parts[0]) > $this->config['maxLength']) {
        $this->edition = EditionHelper::getDefaultEdition();
      } else {
        $this->edition = EditionHelper::getEditionByUrl($parts[0]);
      }
    }

    \Yii::$app->getUrlManager()->setBaseUrl(\Yii::$app->getUrlManager()->getBaseUrl() . '/' . $this->edition->url . '/');
  }

  /**
   * @return Edition
   * @throws \yii\base\InvalidConfigException
   */
  public function getEdition():Edition {
    return $this->edition;
  }

  /**
   * @return string
   */
  public function getEditionUrl():string {
    return $this->edition->url;
  }

  /**
   * @param string $name
   * @return mixed
   * @throws \yii\base\UnknownPropertyException
   */
  public function __get($name) {
    $getter = 'get' . $name;
    if ($this->edition->hasAttribute($name)) {
      return $this->edition->getAttribute($name);
    } else {
      if (method_exists($this->edition, $getter)) {
        return $this->edition->$getter();
      }
    }

    return parent::__get($name);
  }
}