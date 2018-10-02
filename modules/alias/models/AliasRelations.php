<?php

namespace crudschool\modules\alias\models;

use crudschool\models\BaseModel;

/**
 * This is the model class for table "alias_relations".
 *
 * @property int $alias_id
 * @property int $rel_id
 */
class AliasRelations extends BaseModel {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'alias_relations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['alias_id', 'rel_id'], 'required'],
            [['alias_id', 'rel_id'], 'integer'],
            [['alias_id'], 'unique'],
            [['rel_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'alias_id' => $this->t('alias_id'),
            'rel_id'   => $this->t('rel_id'),
        ];
    }
}
