<?php

namespace backend\controllers;

use backend\models\Customer;
use backend\models\search\CustomerSearch;
use Throwable;
use Yii;
use yii\base\Model;
use yii\bootstrap5\Html;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
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
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'batch-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Customer models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param int $id ID
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): array
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Szczegóły Klienta #'.$id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                ];
            }
        }

        throw new NotFoundHttpException(Yii::$app->params['message.pageNotFound']);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, table will be reloaded.
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionCreate(): array
    {
        $request = Yii::$app->request;
        $model = new Customer();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Dodawanie Klienta',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Zapisz', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }

            if ($model->load($request->post()) && $model->save()) {
                return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
            }

            return [
                'title' => 'Dodawanie Klienta',
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Zapisz', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }

        throw new NotFoundHttpException(Yii::$app->params['message.pageNotFound']);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, table will be reloaded.
     * @param int $id ID
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): array
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Edytowanie Klienta #'.$id,
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Zapisz', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }

            if ($model->load($request->post()) && $model->save()) {
                return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
            }

            return [
                'title' => 'Edytowanie Klienta #'.$id,
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Zapisz', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }

        throw new NotFoundHttpException(Yii::$app->params['message.pageNotFound']);
    }

    /**
     * Batch update for Customer models.
     * @return string|Response
     */
    public function actionBatchUpdate()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->indexBy('id');

        // tylko zaznaczone
        if(Yii::$app->request->post('selection')) {
            $dataProvider->query->andWhere(['id' => Yii::$app->request->post('selection')]);
        }

        $models = $dataProvider->getModels();
        if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {
            $count = 0;
            foreach ($models as $model) {
                if ($model->save()) {
                    $count++;
                }
            }
            Yii::$app->session->setFlash('success', "Zapisanych rekordów: {$count}.");
            return $this->redirect(['batch-update']);
        }

        return $this->render('batch-update', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Deletes selected Customer models.
     * If deletion is successful, table will be reloaded.
     * @return Response
     */
    public function actionBatchDelete(): Response
    {
        $selected = null;
        if(Yii::$app->request->post('selection')) {
            $selected = ['id' => Yii::$app->request->post('selection')];
        }

        $count = Customer::deleteAll($selected);
        Yii::$app->session->setFlash('success', "Usuniętych rekordów: {$count}.");

        return $this->redirect(['batch-update']);
    }


    /**
     * Deletes an existing Customer model.
     * If deletion is successful, table will be reloaded.
     * @param int $id ID
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): array
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->delete();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        }

        throw new NotFoundHttpException(Yii::$app->params['message.pageNotFound']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Customer
    {
        if (($model = Customer::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::$app->params['message.pageNotFound']);
    }
}
