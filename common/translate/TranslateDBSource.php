<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 04.09.2018
 * Time: 21:59
 */

namespace crudschool\common\translate;

use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\i18n\DbMessageSource;

class TranslateDBSource extends DbMessageSource {
	
	public $sourceMessageTable = 'translation';
	
	protected function loadMessagesFromDb($category, $language) {
		if (!$language || is_array($language)) {
			$language = \Yii::$app->get('edition')->code;
		}
		
		$mainQuery = (new Query())->select(['code', 'category', 'translate' => "IFNULL(`$language`, IFNULL(`$this->sourceLanguage`, `code`))"])
			->from($this->sourceMessageTable)
			->where([
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
	
	public static function format() {}
}