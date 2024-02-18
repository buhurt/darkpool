<?php

use app\widgets\airdatepicker\DatePickerWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\west\search\WestDarkpoolSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="west-darkpool-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <?= $form->field($model, 'countDay')->textInput(['value' => $model->countDay ?? 30])->label('Кол-во дней для выборки'); ?>
        </div>
        <div class="col-md-3 col-xs-12">
            <?= $form->field($model, 'percent')->textInput(['value' => $model->percent ?? 100])->label('Процент превышения'); ?>
        </div>
        <div class="col-md-3 col-xs-12">
            <?= $form->field($model, 'startDate')->widget(DatePickerWidget::class, [
                'name' => 'startDate',
                'options' => [
                    'id' => 'start-search-date',
                    'autocomplete' => 'off',
                ],
                'clientOptions' => [
                    'autoClose' => true,
                    'format' => 'dd.mm.yyyy',
                    'clearButton' => 'false',
                ],
            ])->label('Стартовая дата'); ?>
        </div>
        <div class="col-md-3 col-xs-12">
            <div class="form-group" style="margin-top: 1.3rem;">
                <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?= $form->field($model, 'page')->hiddenInput(['value' => 'high'])->label(false) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
