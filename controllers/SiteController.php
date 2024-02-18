<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\MoexTicker;
use app\models\Sync;
use app\models\TradeExpit;
use app\models\west\WestTicker;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ErrorAction;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'obligation', 'about', 'sync', 'search'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $lastActualDay = TradeExpit::getLastActualDate();
        $page = Yii::$app->request->get()['page'] ?? 'new';
        $dataProvider = new ActiveDataProvider([
            'query' => TradeExpit::getDataForChart($page),
            'pagination' => false,
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'prevDay' => $lastActualDay,
            'page' => $page,
            'obligation' => false,
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionObligation()
    {
        $lastActualDay = TradeExpit::getLastActualDate();
        $page = Yii::$app->request->get()['page'] ?? 'new';
        $dataProvider = new ActiveDataProvider([
            'query' => TradeExpit::getDataForChart($page, true),
            'pagination' => false,
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'prevDay' => $lastActualDay,
            'page' => $page,
            'obligation' => true,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionSync()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Sync::find()->orderBy('id DESC'),
        ]);

        return $this->render('sync', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Поиск из шапки
     * @return Response
     */
    public function actionSearch(): Response
    {
        $search = mb_strtoupper(Yii::$app->request->get()['TradeExpitSearch']['ticker_search']);
        $model = WestTicker::findOne(['ticker' => $search]);
        if ($model === null) {
            $model = MoexTicker::findOne(['ticker' => $search]);
            if ($model !== null) {
                return $this->redirect(['/trade-expit/view', 'ticker' => $model->getTicker()]);
            }
        } else {
            return $this->redirect(['/west/view', 'ticker' => $model->getTicker()]);
        }
        return $this->redirect('/');
    }
}
