<?php

use dosamigos\chartjs\ChartJs;
use yii\helpers\Html;
use yii\web\JsExpression;

?>
<div class="card mb-3 shadow-sm <?= ($model->actual == 1) ? 'border border-3 border-danger' : ''; ?>">
    <div class="card-header h5" style="transform: rotate(0);">
        <?= Html::a($model->ticker, ['/trade-expit/view', 'ticker' => $model->ticker], ['class' => '', 'target' => '_blank']); ?>
        <?= Html::a('', ['/trade-expit/hide-ticker', 'ticker' => $model->ticker], ['class' => 'btn btn-close float-end', 'aria-label' => 'Close', 'data-bs-toggle' => 'tooltip', 'title' => 'Скрыть']) ?>
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
                            // @todo https://stackoverflow.com/questions/68744665/chartjs-show-hide-data-individually-instead-of-entire-dataset-on-bar-line-char ?????
                            'label' => new JsExpression("function(t, d) {
                                 var label = d.labels[t.index];
                                 var price = d.price[t.index];
                                 var data = d.datasets[t.datasetIndex].data[t.index];
                                 return ' Всего (₽): ' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, \" \") + ' | Цена: ' + price;
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
                    'price' => explode('|', $model->price),
                    'labels' => explode(',', $model->trade_date),
                    'datasets' => [
                        [
                            'label' => "Всего (₽)",
                            'backgroundColor' => explode('|', $model->color),
                            'borderColor' => explode('|', $model->color),
                            'pointBackgroundColor' => explode('|', $model->color),
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => explode('|', $model->color),
                            'data' => explode(',', $model->all_total),
                        ],
//                        [
//                            'label' => "Много (₽)",
//                            'backgroundColor' => "rgba(255,119,0,0.4)",
//                            'borderColor' => "rgba(255,119,0,1)",
//                            'pointBackgroundColor' => "rgba(255,119,0,1)",
//                            'pointBorderColor' => "#fff",
//                            'pointHoverBackgroundColor' => "#fff",
//                            'pointHoverBorderColor' => "rgba(255,119,0,1)",
//                            'data' => $bigMoney[$ticker],
//                        ],
                    ],
                ],
            ]);
            ?>
        </p>
    </div>
</div>