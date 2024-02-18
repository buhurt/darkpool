<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\Profile $profile
 */

$this->title = Yii::t('user', 'Update {modelClass}: ', [
        'modelClass' => 'User',
    ]) . ' ' . $user->username;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('user', 'Users'),
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = [
    'label' => $user->username,
    'url' => [
        'view',
        'id' => $user->id,
    ],
];
$this->params['breadcrumbs'][] = Yii::t('user', 'Update');
?>
<div class="user-update">
    <div class="card shadow-sm mt-3 mb-3">
        <div class="card-body">
            <h5 class="card-title text-uppercase"><?= Html::encode($this->title) ?></h5>

            <?= $this->render('_form', [
                'user' => $user,
                'profile' => $profile,
            ]) ?>

        </div>
    </div>
</div>
