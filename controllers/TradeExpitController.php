<?php

namespace app\controllers;

use app\models\MoexTicker;
use app\models\search\TradeExpitSearch;
use app\models\TradeExpit;
use app\models\TradeExpitVisible;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * TradeExpitController implements the CRUD actions for TradeExpit model.
 */
class TradeExpitController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'set-visible', 'hide-ticker'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all TradeExpit models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TradeExpitSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TradeExpit model.
     * @param string $ticker ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($ticker)
    {
        $searchModel = new TradeExpitSearch();
        $dataProvider = $searchModel->searchTrades($ticker);
        $chart = TradeExpit::getDataForOneChart(MoexTicker::findOne(['ticker' => $ticker])->id)->one();

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'model' => $this->findModel($ticker),
            'chart' => $chart,
        ]);
    }

    /**
     * Deletes an existing TradeExpit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $ticker): ?TradeExpit
    {
        if (($model = TradeExpit::findOne(['ticker' => $ticker])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Скрыть значение с графика
     */
    public function actionSetVisible(int $id, bool $hide = true): Response
    {
        $model = TradeExpit::findOne(['id' => $id]);
        if ($model !== null) {
            $model->setVisible($hide);
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Скрыть тикер со страницы графиков
     * @param $ticker
     * @param bool $hide
     * @return Response
     * @throws StaleObjectException
     */
    public function actionHideTicker($ticker, bool $hide = true): Response
    {
        if ((new TradeExpitVisible)->setHide($ticker, $hide)) {
            return $this->redirect(\Yii::$app->request->referrer);
        }
        return $this->redirect(['/']);
    }
}
