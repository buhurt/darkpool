<?php

use app\modules\user\helpers\Timezone;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\Profile $profile
 */

$this->title = Yii::t('user', 'Profile');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-profile">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="card shadow-sm mt-3 mb-3">
        <div class="card-body">
            <?php if ($flash = Yii::$app->session->getFlash("Profile-success")): ?>

                <div class="alert alert-success">
                    <p><?= $flash ?></p>
                </div>

            <?php endif; ?>

            <?php $form = ActiveForm::begin([
                'id' => 'profile-form',
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-2 control-label'],
                ],
                'enableAjaxValidation' => true,
            ]); ?>

            <?= $form->field($profile, 'full_name') ?>

            <?php
            // by default, this contains the entire php timezone list of 400+ entries
            // so you may want to set up a fancy jquery select plugin for this, eg, select2 or chosen
            // alternatively, you could use your own filtered list
            // a good example is twitter's timezone choices, which contains ~143  entries
            // @link https://twitter.com/settings/account
            ?>
            <?= $form->field($profile, 'timezone')->dropDownList(ArrayHelper::map(Timezone::getAll(), 'identifier', 'name')); ?>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>