<?php

use app\models\TradeExpit;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TradeExpitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trade Expits';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-expit-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Trade Expit', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'trade_date',
            'trade_day',
            'ticker_id',
            'ticker',
            //'emitent_name',
            //'reg_num',
            //'period_execution',
            //'deals_count',
            //'deals_volume',
            //'count_paper',
            //'max_price',
            //'min_price',
            //'est_price',
            //'big_deals',
            //'created_by',
            //'updated_by',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::class,
                'urlCreator' => static function ($action, TradeExpit $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
