<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\Module $module
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\User $profile
 * @var string $userDisplayName
 */

$module = $this->context->module;

$this->title = Yii::t('user', 'Register');
?>
<div class="user-default-register">
    <div class="card mt-3" style="width: 30rem; margin: auto">
        <div class="card-body">
            <h1 class="card-title text-center"><?= Html::encode($this->title) ?></h1>

            <?php if ($flash = Yii::$app->session->getFlash("Register-success")): ?>

                <div class="alert alert-success">
                    <p><?= $flash ?></p>
                </div>

            <?php else: ?>

                <?php $form = ActiveForm::begin([
                    'id' => 'register-form',
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
                        'labelOptions' => ['class' => 'col-lg-12 control-label'],
                    ],
                    'enableAjaxValidation' => true,
                ]); ?>

                <?php if ($module->requireEmail): ?>
                    <?= $form->field($user, 'email') ?>
                <?php endif; ?>

                <?php if ($module->requireUsername): ?>
                    <?= $form->field($user, 'username') ?>
                <?php endif; ?>
                <?= $form->field($profile, 'full_name') ?>

                <?= $form->field($user, 'newPassword')->passwordInput() ?>


                <div class="form-group mt-3">
                    <div class="d-grid gap-2">
                        <?= Html::submitButton(Yii::t('user', 'Register'), ['class' => 'btn btn-primary']) ?>
                        <?= Html::a(Yii::t('user', 'Login'), ["/user/login"], ['class' => 'text-uppercase']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

                <?php if (Yii::$app->get("authClientCollection", false)): ?>
                    <div class="col-lg-offset-2 col-lg-10">
                        <?= yii\authclient\widgets\AuthChoice::widget([
                            'baseAuthUrl' => ['/user/auth/login'],
                        ]) ?>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </div>
</div>