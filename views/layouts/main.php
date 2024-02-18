<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\assets\AppAsset;
use app\models\search\TradeExpitSearch;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;

$searchModel = new TradeExpitSearch();
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="color-scheme" content="dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <meta name="theme-color" content="#eeeeee" media="(prefers-color-scheme: dark)">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="<?= Yii::$app->homeUrl; ?>"><?= Yii::$app->name; ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <?php if (!Yii::$app->user->isGuest) : ?>
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <?= Html::a('ММВБ', '/', ['class' => 'nav-link px-3']) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a('Облигациии (ММВБ)', '/site/obligation', ['class' => 'nav-link px-3']) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a('Запад', '/west/index', ['class' => 'nav-link px-3']) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a('Загрузить файл', '/west/upload', ['class' => 'nav-link px-3']) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a('Синхронизации', '/site/sync', ['class' => 'nav-link px-3']) ?>
                    </li>
                    <?php if (Yii::$app->user->can('admin')) : ?>
                        <li class="nav-item">
                            <?= Html::a('Пользователи', '/user/admin', ['class' => 'nav-link px-3']) ?>
                        </li>
                    <?php endif; ?>
                </ul>
                <?php $form = ActiveForm::begin([
                    'action' => ['/site/search'],
                    'method' => 'get',
                ]); ?>
                <?= $form->field($searchModel, 'ticker_search')->textInput([
                    'class' => 'form-control form-control-dark w-100',
                    'autocomplete' => 'off',
                    'placeholder' => 'Поиск по тикеру',
                ])->label(false) ?>
                <?php ActiveForm::end(); ?>
            <?php else : ?>
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <?= Html::a('Регистрация', '/user/register', ['class' => 'nav-link px-3']) ?>
                    </li>
                </ul>
            <?php endif; ?>
            <div class="d-flex navbar-nav">
                <div class="nav-item text-nowrap">
                    <?= Yii::$app->user->isGuest ? Html::a('Вход', ['/user/login'], ['class' => 'nav-link px-3']) : Html::a(Html::beginForm(['/user/logout'], 'post') . Html::submitButton('Выход (' . Yii::$app->user->displayName . ')', [
                            'class' => 'btn btn-link nav-link px-3',
                            'data-method' => 'post',
                        ]) . Html::endForm()); ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container-fluid">
        <div class="row">
            <!--            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">-->
            <!--                <div class="position-sticky pt-3">-->
            <!--                    <ul class="nav flex-column">-->
            <!--                        <li class="nav-item">-->
            <!--                            <a class="nav-link active" aria-current="page" href="/">-->
            <!--                                <span data-feather="home"></span>-->
            <!--                                Главная | Дашборд-->
            <!--                            </a>-->
            <!--                        </li>-->
            <!--                        <li class="nav-item">-->
            <!--                            <a class="nav-link" href="#">-->
            <!--                                <span data-feather="file"></span>-->
            <!--                                Тикеры-->
            <!--                            </a>-->
            <!--                        </li>-->
            <!--                    </ul>-->
            <!--                </div>-->
            <!--            </nav>-->

            <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>

            </main>
        </div>
    </div>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
