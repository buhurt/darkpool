<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bot\BotSettings */

$this->title = 'Create Bot Settings';
$this->params['breadcrumbs'][] = ['label' => 'Bot Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bot-settings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
