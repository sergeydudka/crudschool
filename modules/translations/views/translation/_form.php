<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model crudschool\modules\translations\models\Translation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="translation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->dropDownList([ 'app' => 'App', 'comon' => 'Comon', 'js' => 'Js', 'model' => 'Model', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ru-RU')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'uk-UA')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'en-US')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
