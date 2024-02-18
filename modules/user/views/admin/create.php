<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\Profile $profile
 */

$this->title = Yii::t('user', 'Create {modelClass}', [
    'modelClass' => 'User',
]);
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('user', 'Users'),
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
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