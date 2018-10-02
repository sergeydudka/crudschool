<?php

namespace crudschool\modules\translations\models;

use crudschool\models\BaseModel;

/**
 * This is the model class for table "translation".
 *
 * @property int    $translation_id
 * @property string $code
 * @property string $category
 * @property string $description
 * @property string $ru-RU
 * @property string $uk-UA
 * @property string $en-US
 */
class Translation extends BaseModel {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'translation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['code', 'category'], 'required'],
            [['category', 'ru-RU', 'uk-UA', 'en-US'], 'string'],
            [['code'], 'string', 'max' => 256],
            [['description'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'translation_id' => $this->t('translation_id'),
            'code'           => $this->t('code'),
            'category'       => $this->t('category'),
            'description'    => $this->t('description'),
            'ru-RU'          => 'ru-RU',
            'uk-UA'          => 'uk-UA',
            'en-US'          => 'en-US',
        ];
    }
}
