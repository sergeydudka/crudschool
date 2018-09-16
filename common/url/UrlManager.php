<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 04.08.2018
 * Time: 19:34
 */

namespace crudschool\common\url;


class UrlManager extends \yii\web\UrlManager {
  public function createUrl($params) {
    $params = (array)$params;
    $anchor = isset($params['#']) ? '#' . $params['#'] : '';
    unset($params['#'], $params[$this->routeParam]);

    $route = trim($params[0], '/');
    unset($params[0]);

    $baseUrl = $this->showScriptName || !$this->enablePrettyUrl ? $this->getScriptUrl() : $this->getBaseUrl();

    if ($this->enablePrettyUrl) {
      $cacheKey = $route . '?';
      foreach ($params as $key => $value) {
        if ($value !== NULL) {
          $cacheKey .= $key . '&';
        }
      }

      $url = $this->getUrlFromCache($cacheKey, $route, $params);
      if ($url === FALSE) {
        /* @var $rule UrlRule */
        foreach ($this->rules as $rule) {
          if (in_array($rule, $this->_ruleCache[$cacheKey], TRUE)) {
            // avoid redundant calls of `UrlRule::createUrl()` for rules checked in `getUrlFromCache()`
            // @see https://github.com/yiisoft/yii2/issues/14094
            continue;
          }
          $url = $rule->createUrl($this, $route, $params);
          if ($this->canBeCached($rule)) {
            $this->setRuleToCache($cacheKey, $rule);
          }
          if ($url !== FALSE) {
            break;
          }
        }
      }

      if ($url !== FALSE) {
        if (strpos($url, '://') !== FALSE) {
          if ($baseUrl !== '' && ($pos = strpos($url, '/', 8)) !== FALSE) {
            return substr($url, 0, $pos) . $baseUrl . substr($url, $pos) . $anchor;
          }

          return $url . $baseUrl . $anchor;
        } else if (strncmp($url, '//', 2) === 0) {
          if ($baseUrl !== '' && ($pos = strpos($url, '/', 2)) !== FALSE) {
            return substr($url, 0, $pos) . $baseUrl . substr($url, $pos) . $anchor;
          }

          return $url . $baseUrl . $anchor;
        }

        $url = ltrim($url, '/');
        return "$baseUrl/{$url}{$anchor}";
      }

      if ($this->suffix !== NULL) {
        $route .= $this->suffix;
      }
      if (!empty($params) && ($query = http_build_query($params)) !== '') {
        $route .= '?' . $query;
      }

      $route = ltrim($route, '/');
      return "$baseUrl/{$route}{$anchor}";
    }

    $url = "$baseUrl?{$this->routeParam}=" . urlencode($route);
    if (!empty($params) && ($query = http_build_query($params)) !== '') {
      $url .= '&' . $query;
    }

    return $url . $anchor;
  }
}