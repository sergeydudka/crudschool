<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 04.08.2018
 * Time: 18:38
 */

namespace crudschool\common\url;


use crudschool\common\helpers\EditionHelper;
use crudschool\modules\editions\models\Edition;
use crudschool\modules\languages\common\Language;

class Request extends \yii\web\Request {
  private $pathInfo = '';
  private $parsedUrl = '';
  private $config = [];
  private $parts = [];
  private $edition;

  public function init() {
    parent::init();

    $this->config = \Yii::$app->params['edition'];
  }

  protected function resolvePathInfo() {
    $pathInfo = $this->getUrl();

    if (($pos = strpos($pathInfo, '?')) !== false) {
      $pathInfo = substr($pathInfo, 0, $pos);
    }

    $pathInfo = urldecode($pathInfo);

    // try to encode in UTF8 if not so
    // http://w3.org/International/questions/qa-forms-utf-8.html
    if (!preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]              # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
            | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
            )*$%xs', $pathInfo)) {
      $pathInfo = utf8_encode($pathInfo);
    }

    $scriptUrl = $this->getScriptUrl();
    $baseUrl = $this->getBaseUrl();
    if (strpos($pathInfo, $scriptUrl) === 0) {
      $pathInfo = substr($pathInfo, strlen($scriptUrl));
    } else {
      if ($baseUrl === '' || strpos($pathInfo, $baseUrl) === 0) {
        $pathInfo = substr($pathInfo, strlen($baseUrl));
      } else {
        if (isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], $scriptUrl) === 0) {
          $pathInfo = substr($_SERVER['PHP_SELF'], strlen($scriptUrl));
        } else {
          throw new InvalidConfigException('Unable to determine the path info of the current request.');
        }
      }
    }

    if (substr($pathInfo, 0, 1) === '/') {
      $pathInfo = substr($pathInfo, 1);
    }

    $this->pathInfo = $pathInfo;

    $this->resolveEdition();
    $this->parsedUrl = $this->parseUrl($pathInfo);

    return (string)$this->parsedUrl;
  }

  /**
   * @param $pathInfo
   * @return string
   */
  private function parseUrl($pathInfo) {
    $url = $this->getEditionUrl();
    if (strpos($pathInfo, $url) === 0) {
      $pathInfo = trim(substr($pathInfo, strlen($url)), '/');
      $this->setBaseUrl($this->getBaseUrl() . '/' . $url);
    }
    return $pathInfo;
  }

  /**
   * @return string
   */
  public function getParsedUrl() {
    return $this->parsedUrl;
  }

  /**
   * @throws \yii\base\ExitException
   * @throws \yii\base\InvalidConfigException
   */
  private function resolveEdition() {
    $this->parts = array_filter(explode('/', $this->pathInfo));
    
    if (empty($this->parts)) {
      $this->edition = EditionHelper::getDefaultEdition();
    } else {
      if ($this->parts[0] == $this->getDefaultEditionUrl()) {
        unset($this->parts[0]);
        $redirectUrl = $this->getBaseUrl() . '/' . implode('/', $this->parts);
        \Yii::$app->response->redirect($redirectUrl, 301, FALSE);
        \Yii::$app->end(301);
      }

      if (strlen($this->parts[0]) > $this->config['maxLength']) {
        $this->edition = EditionHelper::getDefaultEdition();
      } else {
        $this->edition = EditionHelper::getEditionByUrl($this->parts[0]);
      }
    }
  }

  /**
   * @return Edition
   * @throws \yii\base\InvalidConfigException
   */
  public function getEdition() {
    return $this->edition;
  }

  /**
   * @return string
   */
  public function getEditionUrl() {
    return $this->edition->url;
  }

  /**
   * @return string
   */
  public function getDefaultEditionUrl() {
    return $this->config['default'];
  }

  /**
   * @param string $url
   * @return string
   */
  public function removeEditionFromUrl($url) {
    $parts = explode('/', $url);
    $parts = array_diff($parts, [$this->getEditionUrl()]);
    return implode('/', $parts);
  }

}