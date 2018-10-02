<?php

namespace crudschool\modules\editions\models;

use crudschool\models\BaseModel;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "language".
 *
 * @property int    $edition_id
 * @property string $url
 * @property string $code
 * @property string $title
 * @property string $flag
 */
class Edition extends BaseModel {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'edition';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['url', 'code', 'title', 'language'], 'required'],
            [['url'], 'string', 'max' => 3],
            [['code'], 'string', 'max' => 50],
            [['title'], 'string', 'max' => 256],
            [['edition_id'], 'integer'],
            [['flag'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * @return bool
     */
    public function upload() {
        if ($this->validate()) {
            $path = 'uploads/' . $this->flag->baseName . '.' . $this->flag->extension;
            /** @var UploadedFile $flag */
            $flag = $this->flag;
            if ($this->flag->saveAs($path)) {
                $this->flag = $path;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'edition_id' => $this->t('edition_id'),
            'language'   => $this->t('language'),
            'url'        => $this->t('url'),
            'code'       => $this->t('code'),
            'title'      => $this->t('title'),
            'flag'       => $this->t('flag'),
        ];
    }

    /**
     * @return array
     */
    public static function getDropdown() {
        return ArrayHelper::map(self::find()->asArray(true)->all(), 'language_id', 'title');
    }
}
