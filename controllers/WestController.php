<?php

namespace app\controllers;

use app\models\west\search\WestDarkpoolSearch;
use app\models\west\UploadWest;
use app\models\west\WestDarkpool;
use app\models\west\WestFavorites;
use app\models\west\WestTicker;
use app\models\west\WestVolumeBoundary;
use app\services\sync\WestSyncService;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * WestController implements the CRUD actions for WestDarkpool model.
 */
class WestController extends Controller
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
                            'actions' => ['index', 'view', 'upload', 'favorites-add', 'favorites-delete', 'volume-boundary', 'set-visible'],
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
     * Lists all WestDarkpool models.
     *
     * @param string $page
     * @return string
     */
    public function actionIndex()
    {
        $lastActualDay = WestDarkpool::getLastActualDate();
        $searchModel = new WestDarkpoolSearch();
        $searchModel->startDate = $searchModel->startDate ?? date('d.m.Y');
        $dataProvider = $searchModel->search($this->request->queryParams);
        $params = $this->request->queryParams;
        if (isset($params['page']) || isset($params['WestDarkpoolSearch']['page'])) {
            $page = $params['page'] ?? $params['WestDarkpoolSearch']['page'];
        } else {
            $page = 'new';
        }
        return $this->render('index', [
            'page' => $page,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'prevDay' => $lastActualDay,
        ]);
    }

    /**
     * Displays a single TradeExpit model.
     * @param string $ticker ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(string $ticker): string
    {
        $searchModel = new WestDarkpoolSearch();
        $dataProvider = $searchModel->searchTrades($ticker);
        $ticker = WestTicker::findOne(['ticker' => $ticker]);
        $chart = WestDarkpool::getDataForOneChart($ticker->getId())->one();
        $modelVolume = WestVolumeBoundary::findOne(['ticker_id' => $ticker->getId(), 'created_by' => Yii::$app->user->id]) ?? new WestVolumeBoundary(
        );

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'model' => $chart,
            'ticker' => $ticker,
            'modelVolume' => $modelVolume,
        ]);
    }

    /**
     * Finds the WestDarkpool model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return WestDarkpool the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($ticker)
    {
        if (($model = WestDarkpool::findOne(['ticker' => $ticker])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new WestDarkpool model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new WestDarkpool();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing WestDarkpool model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing WestDarkpool model.
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
     * Загрузка файла
     * @return string
     */
    public function actionUpload()
    {
        $model = new UploadWest();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (isset($post['UploadWest'])) {
                $model->csvFile = UploadedFile::getInstance($model, 'csvFile');
                if ($model->upload()) {
                    if (WestSyncService::runFromFile($model->getFileName())) {
                        Yii::$app->session->setFlash('success', 'Данные загружены');
                    } else {
                        Yii::$app->session->setFlash('error', 'Данные не загружены');
                    }
                }
            }
        }
        return $this->render('upload', [
            'model' => $model,
        ]);
    }

    /**
     * Добавление тикера в избранное
     * @return bool
     */
    public function actionFavoritesAdd(): bool
    {
        $post = Yii::$app->request->post();
        if (WestFavorites::add($post['id'])) {
            return true;
        }

        return false;
    }

    /**
     * Удаление тикера из избранного
     * @return bool
     * @throws StaleObjectException
     */
    public function actionFavoritesDelete(): bool
    {
        $post = Yii::$app->request->post();
        if (WestFavorites::deleteFavorites($post['id'])) {
            return true;
        }

        return false;
    }

    /**
     * Реактирование верхней границы объемов
     * @return Response
     */
    public function actionVolumeBoundary(): Response
    {
        $post = Yii::$app->request->post();
        $model = WestVolumeBoundary::findOne(['ticker_id' => $post['WestVolumeBoundary']['ticker_id'], 'created_by' => Yii::$app->user->id]
        ) ?? new WestVolumeBoundary();
        $model->load($post);
        $model->save();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Скрыть значение с графика
     * @param $id
     * @param bool $hide
     * @return Response
     */
    public function actionSetVisible($id, bool $hide = true): Response
    {
        $model = WestDarkpool::findOne(['id' => $id]);
        if ($model !== null) {
            $model->setVisible($hide);
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }
}
