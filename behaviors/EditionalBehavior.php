<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 23.09.2018
 * Time: 20:47
 */

namespace crudschool\behaviors;

use crudschool\common\url\Request;
use crudschool\models\ActiveModel;
use crudschool\models\BaseModel;
use yii\base\Behavior;

class EditionalBehavior extends Behavior {
    /* @var BaseModel $owner */
    public $owner;

    public function events() {
        return [
            ActiveModel::EVENT_BEFORE_FIND => 'addEditionToQuery',
        ];
    }
    
    public function addEditionToQuery() {
        if (!$this->owner->hasProperty('edition_id')) {
            return null;
        }

        /* @var Request $request */
        $request = \Yii::$app->request;
        $edition = $request->getEdition();

        $query = $this->owner::getCurrentQuery();
        $query->andWhere(['edition_id' => $edition->edition_id]);
    }
}