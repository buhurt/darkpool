<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\forms\ForgotForm $model
 */

$this->title = Yii::t('user', 'Forgot password');
?>
<div class="user-default-forgot">

    <div class="card mt-3" style="width: 25rem; margin: auto">
        <div class="card-body">
            <h1 class="card-title text-center"><?= Html::encode($this->title) ?></h1>

            <?php if ($flash = Yii::$app->session->getFlash('Forgot-success')): ?>

                <div class="alert alert-success">
                    <p><?= $flash ?></p>
                </div>

            <?php else: ?>

                <div class="row">
                    <div class="col-lg-12">
                        <?php $form = ActiveForm::begin(['id' => 'forgot-form']); ?>
                        <?= $form->field($model, 'email') ?>
                        <div class="form-group mt-3">
                            <div class="d-grid gap-2">
                                <?= Html::submitButton(Yii::t('user', 'Submit'), ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>
