<?php

use app\models\west\WestDarkpool;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\west\WestDarkpool */
/* @var $ticker app\models\west\WestTicker */

$this->title = $ticker->ticker ?? '';
YiiAsset::register($this);
?>
<div class="trade-expit-view">
    <?php if ($model !== null) : ?>
        <?= $this->render('_chart', [
            'model' => $model,
        ]) ?>
    <?php endif; ?>
    <?php if ($model !== null) : ?>
        <div class="card shadow-sm mt-3 mb-3">
            <div class="card-body">
                <?php $form = ActiveForm::begin(['action' => '/west/volume-boundary']); ?>
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <?= $form->field($modelVolume, 'value')->textInput(['placeholder' => 'Верхняя граница объёмов'])->label(false) ?>
                        <?= $form->field($modelVolume, 'ticker')->hiddenInput(['value' => $model->ticker])->label(false) ?>
                        <?= $form->field($modelVolume, 'ticker_id')->hiddenInput(['value' => $model->id])->label(false) ?>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <div class="form-group">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="card shadow-sm mt-3 mb-3">
        <div class="card-body">
            <?php if ($model !== null) : ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => [
                        'class' => 'table table-sm table-hover table-bordered',
                    ],
                    'rowOptions' => static function (WestDarkpool $data) {
                        $isCancelled = null;
                        $isHide = null;
                        if ($data->isIsCancelled()) {
                            $isCancelled = 'table-danger';
                        }//'background: #ffe8e8;';
                        if ($data->isHide()) {
                            $isHide = 'table-warning';
                        }//'background-color:#f8d7da;';
                        return [
                            'class' => $isCancelled . ' ' . $isHide,
                            'title' => 'Message: ' . $data->message,
                            'data-bs-toggle' => 'tooltip',
                        ];
                    },
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'trade_date',
                            'value' => function ($data) {
                                return Yii::$app->formatter->asDate($data->trade_date, 'php:d.m.Y') . ' ' . Yii::$app->formatter->asTime($data->trade_time, 'php:H:i:s');
                            },
                        ],
                        'ticker',
                        'deals_volume:integer',
                        [
                            'attribute' => 'price',
                            'format' => 'raw',
                            'value' => static function ($data) {
                                return Yii::$app->formatter->asCurrency($data->price, 'USD');
                            },
                        ],
                        [
                            'attribute' => 'notional',
                            'format' => 'raw',
                            'value' => static function ($data) {
                                return Yii::$app->formatter->asCurrency($data->notional, 'USD', [NumberFormatter::FRACTION_DIGITS => 0]);
                            },
                        ],
                        [
                            'attribute' => 'percent_avg_30_day',
                            'format' => 'raw',
                            'value' => static function ($data) {
                                return Yii::$app->formatter->asDecimal($data->percent_avg_30_day * 1000, 1);
                            },
                        ],
                        'deals_avg_30_day:integer',
                        [
                            'class' => ActionColumn::class,
                            'header' => 'Действия',
                            'template' => '{update}',
                            'headerOptions' => ['width' => '7%'],
                            'buttons' => [
                                'update' => static function ($url, $model) {
                                    if ($model->is_hide) {
                                        $icon = '<i class="bi bi-check-lg"></i>';
                                        $url = ['/west/set-visible', 'id' => $model->id, 'hide' => 0];
                                        $title = 'Вернуть на график';
                                    } else {
                                        $icon = '<i class="bi bi-eye-slash-fill"></i>';
                                        $url = ['/west/set-visible', 'id' => $model->id, 'hide' => 1];
                                        $title = 'Скрыть с графика';
                                    }
                                    return Html::a($icon, $url, [
                                        'class' => 'btn-action-grid',
                                        'data-bs-toggle' => 'tooltip',
                                        'title' => $title,
                                    ]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            <?php else : ?>
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading"><?= $ticker->getTicker() ?></h4>
                    <hr>
                    Данных по <strong><?= $ticker->getShortName() ?></strong> не загружалось!
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
