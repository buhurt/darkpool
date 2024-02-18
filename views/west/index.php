<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'WEST DARKPOOL';
$newActive = $oldActive = $anomalActive = $loveActive = $filter = null;
switch ($page) {
    case 'new':
        $title = 'Сделки за последний актуальный день | ' . Yii::$app->formatter->asDate($prevDay, 'php:d.m.Y');
        $newActive = 'active';
        $filter = true;
        break;
    case 'old':
        $title = 'За последний актуальный день, сделок не было';
        $oldActive = 'active';
        break;
    case 'high':
        $title = 'Аномальные скачки торгов | ' . Yii::$app->formatter->asDate($searchModel->startDate, 'php:d.m.Y');
        $anomalActive = 'active';
        $filter = true;
        break;
    case 'favorites':
        $title = 'Избранное';
        $loveActive = 'active';
        break;
}
?>
<div class="site-index">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $title; ?></h1>
        <div class="btn-group">
            <?= Html::a('Актуальные', ['/west/index'], ['class' => 'btn btn-outline-light ' . $newActive ?? '']) ?>
            <?= Html::a('Не актуальные', [
                '/west/index',
                'page' => 'old',
            ], ['class' => 'btn btn-outline-light ' . $oldActive ?? '']) ?>
            <?= Html::a('Аномалии', [
                '/west/index',
                'page' => 'high',
            ], ['class' => 'btn btn-outline-light ' . $anomalActive ?? '']) ?>
            <?= Html::a('<i class="bi bi-star"></i>', [
                '/west/index',
                'page' => 'favorites',
            ], ['class' => 'btn btn-outline-light ' . $loveActive ?? '']) ?>
        </div>
    </div>
    <?= $filter ? $this->render('_search', ['model' => $searchModel]) : null ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_chart',
        'options' => [
            'tag' => 'div',
            'class' => 'ticker-list',
            'id' => 'ticker-list',
        ],
        'layout' => "{items}\n{pager}",
        'summary' => false,
        'emptyText' => '<p class="margin-md">Список пуст</p>',
        'emptyTextOptions' => [
            'tag' => 'p',
        ],
        'pager' => [
            'options' => [
                'class' => 'pagination pull-right',
            ],
        ],
    ]);
    ?>
</div>
