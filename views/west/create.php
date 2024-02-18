<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\west\WestDarkpool */

$this->title = 'Create West Darkpool';
$this->params['breadcrumbs'][] = ['label' => 'West Darkpools', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="west-darkpool-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
