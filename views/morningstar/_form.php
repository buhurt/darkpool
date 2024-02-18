<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\morningstar\MorningstarData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="morningstar-data-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <?= $form->field($model, 'name')->textInput() ?>
        </div>
        <div class="col-md-6 col-xs-12">
            <?= $form->field($model, 'apikey')->textInput(['value' => 'lstzFDEOhfFNMLikKa0am9mgEKLBl49T']) ?>
        </div>
        <div class="col-md-12 col-xs-12">
            <?= $form->field($model, 'url')->textInput(['value' => 'https://api-global.morningstar.com/sal-service/v1/stock/ownership/v1/????????????/OwnershipData/mutualfund/100/data?languageId=ru&locale=ru&clientId=MDC&component=sal-components-ownership&version=3.59.1	']) ?>
        </div>
        <div class="col-md-12 col-xs-12 mt-2">
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
