<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\forms\LoginForm $model
 */

$this->title = Yii::t('user', 'Login');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-login">
    <div class="card mt-3" style="width: 25rem; margin: auto">
        <div class="card-body">
            <h1 class="card-title text-center"><?= Html::encode($this->title) ?></h1>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-12 control-label'],
                ],

            ]); ?>

            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'rememberMe', [
                'template' => "{label}<div class=\"col-lg-offset-2 col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
            ])->checkbox() ?>

            <div class="form-group mt-3">
                <div class="d-grid gap-2">
                    <?= Html::submitButton(Yii::t('user', 'Login'), ['class' => 'btn btn-primary']) ?>
                    <div class="d-flex justify-content-between flex-wrap">
                        <!--                        --><?php //echo Html::a(Yii::t("user", "Register"), ["/user/register"], ['class' => 'text-uppercase']) ?>
                        <!--                        --><?php //echo Html::a(Yii::t("user", "Forgot password") . "?", ["/user/forgot"], ['class' => 'text-uppercase']) ?>
                        <!--            --><?php //echo Html::a(Yii::t("user", "Resend confirmation email"), ["/user/resend"]) ?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

            <?php if (Yii::$app->get("authClientCollection", false)): ?>
                <div class="col-lg-offset-2 col-lg-10">
                    <?= yii\authclient\widgets\AuthChoice::widget(['baseAuthUrl' => ['/user/auth/login']]) ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
