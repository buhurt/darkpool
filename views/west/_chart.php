<?php

use app\models\west\WestTicker;
use dosamigos\chartjs\ChartJs;
use yii\helpers\Html;
use yii\web\JsExpression;

/** @var WestTicker $model */
$class = '';
$action = 'add';
$tooltip = 'В избранное';
if (!empty($model->result)) {
    $result = Yii::$app->formatter->asInteger($model->getResult());
    $avg_deals = Yii::$app->formatter->asInteger($model->getAvgDeals());
}
if ($model->is_favorites) {
    $class = '-fill added';
    $action = 'delete';
    $tooltip = 'Из избранного';
}
?>
<div class="card mt-3 shadow-sm <?= ($model->getActual()) ? 'border border-3 border-danger' : ''; ?>">
    <div class="card-header h5" style="transform: rotate(0);">
        <?= Html::a($model->getTicker(), ['/west/view', 'ticker' => $model->getTicker()], ['class' => '', 'target' => '_blank']) . " | 
            <span data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"$model->type\">
                " . Html::a($model->short_name, "https://ru.tradingview.com/chart/?symbol=$model->ticker", ['target' => '_blank']) . "
            </span>"; ?>
        <?= (!empty($model->getResult())) ? " | Процент превышения: $result% | Среднее: $avg_deals" : null; ?>
        <?= Html::a('<i class="bi bi-star' . $class . '"></i>',
            [''],
            [
                'class' => 'float-end favorites',
                'data-bs-toggle' => 'tooltip',
                'title' => $tooltip,
                'data-id' => $model->getId(),
                'data-action' => $action,
            ]) ?>
    </div>
    <div class="card-body">
        <p class="card-text">
            <?= ChartJs::widget([
                'type' => 'bar',
                'clientOptions' => [
                    'maintainAspectRatio' => false,
                    'tooltips' => [
                        'callbacks' => [
                            // @todo https://stackoverflow.com/questions/46962841/how-to-modify-tooltips-in-yii2-using-dosamigos-chartjs-chartjs
                            'label' => new JsExpression("function(t, d) {
                     var label = d.labels[t.index];
                     var price = d.price[t.index];
                     var notional = d.notional[t.index];
                     var data = d.datasets[t.datasetIndex].data[t.index];
                     return ' Всего бумаг (шт): ' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, \" \") + ' | Сумма: ' + notional.toString().replace(/\B(?=(\d{3})+(?!\d))/g, \" \") + '$ | Цена: ' + price;
              }"),
                        ],
                    ],
                ],
                'options' => [
                    'height' => 300,
                    'scales' => [
                        'yAxes' => [
                            'ticks' => [
                                'min' => 0,
                                'beginAtZero' => true,
                            ],
                        ],
                    ],
                ],
                'data' => [
                    'notional' => explode(',', $model->getAllNotional()),
                    'price' => explode('|', $model->getPrice()),
                    'labels' => explode(',', $model->getTradeDate()),
                    'datasets' => [
                        [
                            'label' => "Всего бумаг(шт)",
                            'backgroundColor' => explode('|', $model->getColor()),
                            'borderColor' => explode('|', $model->getColor()),
                            'pointBackgroundColor' => explode('|', $model->getColor()),
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => explode('|', $model->getColor()),
                            'data' => explode(',', $model->getAllVolume()),
                        ],
                    ],
                ],
            ]);
            ?>
        </p>
    </div>
</div>