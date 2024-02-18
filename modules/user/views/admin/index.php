<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\user\Module $module
 * @var app\modules\user\models\search\UserSearch $searchModel
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\Role $role
 */

$module = $this->context->module;
$user = $module->model("User");
$role = $module->model("Role");

$this->title = Yii::t('user', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <div class="card shadow-sm mt-3 mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h5 class="card-title text-uppercase"><?= Html::encode($this->title) ?></h5>
                <p>
                    <?= Html::a('<i class="bi bi-gear"></i>', ['/bot'], ['class' => 'btn btn-outline-warning btn-sm']) ?>
                    <?= Html::a('<i class="bi bi-person-plus"></i>', ['create'], ['class' => 'btn btn-outline-success btn-sm']) ?>
                </p>
            </div>
            <?php
            Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'profile.full_name',
                    'username',
                    'logged_in_at:datetime',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
            <?php Pjax::end(); ?>

        </div>
    </div>
</div>
