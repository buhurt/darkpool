<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\morningstar\MorningstarData */

$this->title = 'Добавить источник';
$this->params['breadcrumbs'][] = ['label' => 'MorningStar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="morningstar-data-create">
    <div class="card shadow-sm mt-3 mb-3">
        <div class="card-body">
            <h2><?= Html::encode($this->title) ?></h2>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
    <div class="card shadow-sm mt-3 mb-3">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    'url',
                    'apikey',
                    [
                        'class' => ActionColumn::class,
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
