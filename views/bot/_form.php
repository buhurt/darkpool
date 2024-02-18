<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bot\BotSettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bot-settings-form">

    <?php
    $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lightning')->checkbox() ?>

    <?= $form->field($model, 'include_words')->textarea(['rows' => 10]) ?>
    <br>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php
    ActiveForm::end(); ?>

</div>
