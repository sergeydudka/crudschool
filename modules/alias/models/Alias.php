<?php


namespace crudschool\modules\alias\models;

use crudschool\models\BaseModel;

/**
 * This is the model class for table "alias".
 *
 * @property int    $alias_id
 * @property int    $edition_id
 * @property int    $ref_id
 * @property string $ref_model
 * @property string $code
 */
class Alias extends BaseModel {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'alias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['alias_id', 'edition_id', 'ref_id'], 'integer'],
            [['ref_model', 'code'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'alias_id'    => $this->t('alias_id'),
            'language_id' => $this->t('language_id'),
            'ref_id'      => $this->t('ref_id'),
            'ref_model'   => $this->t('ref_model'),
            'code'        => $this->t('code'),
        ];
    }

    /**
     * @param string $ref_id
     * @param string $ref_model
     * @return array|Alias|null|\yii\db\ActiveRecord
     */
    public static function getAlias($ref_id, $ref_model) {
        $condition = [
            'ref_id'    => $ref_id,
            'ref_model' => $ref_model,
        ];
        return self::find()->where($condition)->one();
    }

    /**
     * @param string $ref_id
     * @param string $ref_model
     * @param string $code
     * @return Alias
     */
    public static function setAlias($ref_id, $ref_model, $code) {
        $model = new Alias();
        $model->ref_id = $ref_id;
        $model->ref_model = $ref_model;
        $model->code = $code;
        return $model;
    }
}
