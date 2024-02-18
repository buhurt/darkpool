<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\west\search\WestDarkpoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'West Darkpools';
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = 'Загрузка';
?>
<div class="west-darkpool-index">

    <div class="card shadow-sm mt-3 mb-3">
        <div class="card-body">
            <?php $form = ActiveForm::begin(['action' => ['upload'], 'options' => ['enctype' => 'multipart/form-data']]) ?>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <?= $form->field($model, 'csvFile')->fileInput()->label('Загрузка файла');
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group btn-toolbar pull-right">
                        <?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
