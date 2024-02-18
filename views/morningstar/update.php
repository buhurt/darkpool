<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\morningstar\MorningstarData */

$this->title = 'Редактировать: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Morningstar', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="morningstar-data-update">
    <div class="card shadow-sm mt-3 mb-3">
        <div class="card-body">
            <h1><?= Html::encode($this->title) ?></h1>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
