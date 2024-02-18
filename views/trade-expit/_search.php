<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\TradeExpitSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="trade-expit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'trade_date') ?>

    <?= $form->field($model, 'trade_day') ?>

    <?= $form->field($model, 'ticker_id') ?>

    <?= $form->field($model, 'ticker') ?>

    <?php // echo $form->field($model, 'emitent_name') ?>

    <?php // echo $form->field($model, 'reg_num') ?>

    <?php // echo $form->field($model, 'period_execution') ?>

    <?php // echo $form->field($model, 'deals_count') ?>

    <?php // echo $form->field($model, 'deals_volume') ?>

    <?php // echo $form->field($model, 'count_paper') ?>

    <?php // echo $form->field($model, 'max_price') ?>

    <?php // echo $form->field($model, 'min_price') ?>

    <?php // echo $form->field($model, 'est_price') ?>

    <?php // echo $form->field($model, 'big_deals') ?>

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
