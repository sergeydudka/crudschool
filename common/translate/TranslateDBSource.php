<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 04.09.2018
 * Time: 21:59
 */

namespace crudschool\common\translate;

use crudschool\modules\translations\models\Translation;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\i18n\DbMessageSource;
use yii\i18n\MissingTranslationEvent;
use yii\web\Request;

class TranslateDBSource extends DbMessageSource {
  private $_messages = [];

  public $sourceMessageTable = 'translation';

  /**
   * @throws \yii\base\InvalidConfigException
   */
  public function init() {
    parent::init();
    $this->on(self::EVENT_MISSING_TRANSLATION, [$this, 'onMissingTranslation']);
  }

  /**
   * @param string $category
   * @param string $language
   * @return array
   * @throws \yii\base\InvalidConfigException
   * @throws \yii\db\Exception
   */
  protected function loadMessagesFromDb($category, $language) {
    if (!$language || is_array($language)) {
      $language = \Yii::$app->request->getEdition()->code;
    }

    if (!$language || $language == $this->sourceLanguage) {
      $translateQuery = "IFNULL(`$this->sourceLanguage`, `code`)";
    } else {
      $translateQuery = "IFNULL(`$language`, IFNULL(`$this->sourceLanguage`, `code`))";
    }

    $mainQuery = (new Query())->select([
      'code',
      'category',
      'translate' => $translateQuery,
    ])->from($this->sourceMessageTable)->where([
      'category' => $category,
    ]);

    /*$fallbackLanguage = substr($language, 0, 2);
    $fallbackSourceLanguage = substr($this->sourceLanguage, 0, 2);

    if ($fallbackLanguage !== $language) {
      $mainQuery->union($this->createFallbackQuery($category, $language, $fallbackLanguage), true);
    } elseif ($language === $fallbackSourceLanguage) {
      $mainQuery->union($this->createFallbackQuery($category, $language, $fallbackSourceLanguage), true);
    }*/

    $messages = $mainQuery->createCommand($this->db)->queryAll();
    return ArrayHelper::map($messages, 'code', 'translate');
  }

  public function onMissingTranslation(MissingTranslationEvent $event): MissingTranslationEvent {
    $model = new Translation();

    $model->category = $event->category;
    $model->code = $event->message;
    $model->save(false); // TODO: разобраться почему неправильно работает валидация

    $event->translatedMessage = $event->message;
    return $event;
  }

  /**
   * @param string $category
   * @param string $message
   * @param string $language
   * @return bool|string
   */
  public function translateMessage($category, $message, $language) {
    $key = $language . '/' . $category;
    if (!isset($this->_messages[$key])) {
      $this->_messages[$key] = $this->loadMessages($category, $language);
    }
    
    if (isset($this->_messages[$key][$message]) && $this->_messages[$key][$message] !== '') {
      return $this->_messages[$key][$message];
    } else if (isset($this->_messages[$key][$message]) && $this->_messages[$key][$message] === '') {
      return $this->_messages[$key][$message] = $message;
    } else if ($this->hasEventHandlers(self::EVENT_MISSING_TRANSLATION)) {
      $event = new MissingTranslationEvent([
        'category' => $category,
        'message'  => $message,
        'language' => $language,
      ]);
      $this->trigger(self::EVENT_MISSING_TRANSLATION, $event);
      if ($event->translatedMessage !== null) {
        return $this->_messages[$key][$message] = $event->translatedMessage;
      }
    }

    return $this->_messages[$key][$message] = $message;
  }

    /**
     * Formats a message using [[MessageFormatter]].
     *
     * @param string $message the message to be formatted.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`).
     * @return string the formatted message.
     */
    public function format($message, $params, $language)
    {
        $params = (array) $params;
        if ($params === []) {
            return $message;
        }

        if (preg_match('~{\s*[\w.]+\s*,~u', $message)) {
            $formatter = $this->getMessageFormatter();
            $result = $formatter->format($message, $params, $language);
            if ($result === false) {
                $errorMessage = $formatter->getErrorMessage();
                Yii::warning("Formatting message for language '$language' failed with error: $errorMessage. The message being formatted was: $message.", __METHOD__);

                return $message;
            }

            return $result;
        }

        $p = [];
        foreach ($params as $name => $value) {
            $p['{' . $name . '}'] = $value;
        }

        return strtr($message, $p);
    }
}