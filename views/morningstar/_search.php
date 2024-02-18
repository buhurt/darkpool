<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\morningstar\search\MorningstarDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="morningstar-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'source_id') ?>

    <?= $form->field($model, 'fund_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'current_shares') ?>

    <?php // echo $form->field($model, 'total_shares_held') ?>

    <?php // echo $form->field($model, 'total_assets') ?>

    <?php // echo $form->field($model, 'change_amount') ?>

    <?php // echo $form->field($model, 'change_percentage') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
