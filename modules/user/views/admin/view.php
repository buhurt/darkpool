<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\User $user
 */

$this->title = $user->username;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('user', 'Users'),
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <div class="row">
        <div class="col-sm-9">
            <div class="card shadow-sm mt-3 mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h5 class="card-title text-uppercase"><?= Html::encode($this->title) ?> | <?= Html::encode($user->profile->full_name) ?></h5>
                        <p>
                            <?= Html::a('<i class="bi bi-pencil"></i>', [
                                'update',
                                'id' => $user->id,
                            ], ['class' => 'btn btn-outline-dark btn-sm']) ?>
                            <?= Html::a('<i class="bi bi-trash"></i>', [
                                'delete',
                                'id' => $user->id,
                            ], [
                                'class' => 'btn btn-outline-danger btn-sm',
                                'data' => [
                                    'confirm' => Yii::t('user', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </p>
                    </div>
                    <?= DetailView::widget([
                        'model' => $user,
                        'attributes' => [
                            'id',
                            'role_id',
                            'status',
                            'email:email',
                            'username',
                            'profile.full_name',
                            'logged_in_ip',
                            'logged_in_at:datetime',
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>

                </div>
            </div>
            <?php if (!empty($user->favorites)) : ?>
                <div class="card shadow-sm mt-3 mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h5 class="card-title text-uppercase"><i class="bi bi-star-fill added"></i> Избранное</h5>
                        </div>
                        <div class="row row-cols-1 row-cols-md-6 g-4">
                            <?php foreach ($user->favorites as $favorite) : ?>
                                <div class="col">
                                    <?= Html::a('
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title"> ' . $favorite->tickerInfo->ticker . '</h6>
                                            <p class="card-text"><small class="text-muted"> ' . Yii::$app->formatter->asDatetime($favorite->created_at) . '</small></p>
                                        </div>
                                    </div>
                                ', [
                                        '/west/view',
                                        'ticker' => $favorite->tickerInfo->ticker,
                                    ], ['target' => '_blank']) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-sm-3">
            <div class="list-group shadow-sm mt-3 mb-3">
                <?php foreach ($user->authLog as $log) : ?>
                    <a href="#" class="list-group-item list-group-item-action" aria-current="true">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?= $log->ip; ?></h5>
                            <small><?= Yii::$app->formatter->asDatetime($log->created_at, 'php:d.m.Y H:i'); ?></small>
                        </div>
                        <p class="mb-1"><?= $log->location; ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
