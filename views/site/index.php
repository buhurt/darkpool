<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'DARKPOOL';
$newActive = $oldActive = null;
switch ($page) {
    case 'new':
        $title = 'Сделки за последний актуальный день | ' . Yii::$app->formatter->asDate($prevDay, 'php:d.m.Y');
        $newActive = 'active';
        $url = $obligation ? '/site/obligation' : '/site/index';
        break;
    case 'old':
        $title = 'За последний актуальный день, сделок не было';
        $oldActive = 'active';
        $url = $obligation ? '/site/obligation' : '/site/index';
        break;
}
?>
<div class="site-index">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $title; ?></h1>
        <div class="btn-group">
            <?= Html::a('Актуальные', [$url], ['class' => 'btn btn-outline-light ' . $newActive ?? '']) ?>
            <?= Html::a('Не актуальные', [
                $url,
                'page' => 'old',
            ], ['class' => 'btn btn-outline-light ' . $oldActive ?? '']) ?>
        </div>
    </div>

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
