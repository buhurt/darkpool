<?php

/* @var $this yii\web\View */

use app\models\Sync;
use yii\grid\GridView;

$this->title = 'Синхронизации';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <div class="card shadow-sm mt-3 mb-3">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'name',
                        'value' => function ($data) {
                            return Sync::getName($data->name);
                        },
                    ],
                    'date_start:datetime',
                    'date_end:datetime',
                    [
                        'attribute' => 'status',
                        'value' => function ($data) {
                            return Sync::getStatusName($data->status);
                        },
                    ],
                    'result',
                ],
            ]); ?>
        </div>
    </div>
</div>
