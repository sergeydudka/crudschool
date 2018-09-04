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
use yii\i18n\DbMessageSource;

class TranslateDBSource extends DbMessageSource {
	
	public $sourceMessageTable = 'translation';
	
	protected function loadMessagesFromDb($category, $language) {
		if (!$language) {
			$language = $this->sourceLanguage;
		}
		
		//////////////
		echo '<pre>';
		var_dump(\Yii::$app->get('lang')->code);
		echo '</pre>';
		die();
		//////////////
		
		$mainQuery = (new Query())->select(['code', 'translation' => 't2.translation'])
			->from(['t1' => $this->sourceMessageTable, 't2' => $this->messageTable])
			->where([
				't1.id' => new Expression('[[t2.id]]'),
				't1.category' => $category,
				't2.language' => $language,
			]);
		
		$fallbackLanguage = substr($language, 0, 2);
		$fallbackSourceLanguage = substr($this->sourceLanguage, 0, 2);
		
		if ($fallbackLanguage !== $language) {
			$mainQuery->union($this->createFallbackQuery($category, $language, $fallbackLanguage), true);
		} elseif ($language === $fallbackSourceLanguage) {
			$mainQuery->union($this->createFallbackQuery($category, $language, $fallbackSourceLanguage), true);
		}
		
		$messages = $mainQuery->createCommand($this->db)->queryAll();
		
		return ArrayHelper::map($messages, 'message', 'translation');
	}
}