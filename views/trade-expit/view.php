<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model app\models\TradeExpit */

$this->title = $model->getTicker();
YiiAsset::register($this);
?>
<div class="trade-expit-view">
    <div class="card shadow-sm mt-3 mb-3">
        <div class="card-body">
            <p>
                <?= ($model->isHide()) ? Html::a('<i class="bi bi-eye-fill"></i>', ['/trade-expit/hide-ticker', 'ticker' => $model->getTicker(), 'hide' => false], ['data-bs-toggle' => 'tooltip', 'title' => 'Вернуть в список']) : Html::a('', ['/trade-expit/hide-ticker', 'ticker' => $model->getTicker()], ['class' => 'btn btn-close', 'aria-label' => 'Close', 'data-bs-toggle' => 'tooltip', 'title' => 'Скрыть']) ?>
                <strong><?= $model->getEmitentName() ?></strong> <?= $model->getRegNum() ? ' | <strong>Номер гос. регистрации:</strong>' . $model->getRegNum() : null ?>
            </p>
            <?= $this->render('/site/_chart', [
                'model' => $chart,
            ]) ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-sm table-hover table-bordered',
                ],
                'rowOptions' => static function ($data) {
                    if ($data['is_hide']) {
                        return ['class' => 'table-danger'];
                    }
                },
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'trade_date:date',
                    'deals_count',
                    'deals_volume:currency',
                    'count_paper:integer',
                    'max_price:currency',
                    'min_price:currency',
                    'est_price:currency',
                    [
                        'class' => ActionColumn::class,
                        'header' => 'Действия',
                        'template' => '{update}',
                        'headerOptions' => ['width' => '7%'],
                        'buttons' => [
                            'update' => static function ($url, $model) {
                                if ($model->is_hide) {
                                    $icon = '<i class="bi bi-check-lg"></i>';
                                    $url = ['/trade-expit/set-visible', 'id' => $model->id, 'hide' => 0];
                                    $title = 'Вернуть на график';
                                } else {
                                    $icon = '<i class="bi bi-eye-slash-fill"></i>';
                                    $url = ['/trade-expit/set-visible', 'id' => $model->id, 'hide' => 1];
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

        </div>
    </div>
</div>
