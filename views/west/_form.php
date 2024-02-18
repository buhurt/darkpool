<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\west\WestDarkpool */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="west-darkpool-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'trade_date')->textInput() ?>

    <?= $form->field($model, 'trade_time')->textInput() ?>

    <?= $form->field($model, 'ticker_id')->textInput() ?>

    <?= $form->field($model, 'ticker')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deals_volume')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'notional')->textInput() ?>

    <?= $form->field($model, 'percent_avg_30_day')->textInput() ?>

    <?= $form->field($model, 'message')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'security_type_id')->textInput() ?>

    <?= $form->field($model, 'deals_avg_30_day')->textInput() ?>

    <?= $form->field($model, 'float')->textInput() ?>

    <?= $form->field($model, 'earnings_date')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
