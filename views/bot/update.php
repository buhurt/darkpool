<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bot\BotSettings */

$this->title = 'Редактирование настроек бота';
$this->params['breadcrumbs'][] = ['label' => 'Бот', 'url' => ['/bot']];
$this->params['breadcrumbs'][] = ['label' => 'Редактировать'];
?>
<div class="bot-settings-update">
    <div class="card shadow-sm mt-3 mb-3">
        <div class="card-body">
            <h3><?= Html::encode($this->title) ?></h3>
            <hr>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
