<?php

namespace app\controllers;

use app\models\morningstar\MorningstarData;
use app\models\morningstar\MorningstarSource;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * MorningstarController implements the CRUD actions for MorningstarData model.
 */
class MorningstarController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
     * Lists all MorningstarData models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $sources = MorningstarSource::find()->all();
        foreach ($sources as $source) {
            $data[$source->name] = MorningstarData::find()
                ->addSelect(['source_id', 'fund_id', 'name', 'current_shares', 'date'])
//                ->addSelect(new Expression('json_agg(current_shares) current_shares2'))
//                ->addSelect(new Expression('json_agg(date) date'))
                ->andWhere(['source_id' => $source->id])
//                ->groupBy('fund_id, name, source_id')
                ->orderBy('source_id, fund_id')->all();
        }
//        $searchModel = new MorningstarDataSearch();
//        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'data' => $data,
        ]);
    }

    /**
     * Displays a single MorningstarData model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the MorningstarData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return MorningstarSource the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MorningstarSource::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new MorningstarData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MorningstarSource();
        $dataProvider = new ActiveDataProvider([
            'query' => MorningstarSource::find()->orderBy('name'),
        ]);
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['create']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing MorningstarData model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['create']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MorningstarData model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['create']);
    }
}
