<?php

namespace app\controllers;

use Yii;
use app\models\Equipo;
use app\models\Comentario;
use app\models\EquipoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use app\models\User;


/**
 * EquipoController implements the CRUD actions for Equipo model.
 */
class EquipoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [ 'access' => [
            'class' => AccessControl::className(), 
            'only' => ['view', 'create'],
            'rules' => [
                [
                'actions' => ['create', 'view'],
                'allow' => true,
                'roles' => ['@'],
                'matchCallback' => function ($rule, $action) {
                    return User::isUserAdmin(Yii::$app->user->identity->id); 
                },
            ], 
            [
                'actions' => ['view'],
                'allow' => true,
                'roles' => ['@'],
                'matchCallback' => function ($rule, $action) {
                    return User::isUserSimple(Yii::$app->user->identity->id); 
                },
            ], 
            ], 
        ],
        
            'verbs' => [
                'class' => VerbFilter::className(), 'actions' => [
                'logout' => ['post'], ],
            ], 
        ];
    }

    /**
     * Lists all Equipo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EquipoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Equipo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        /*
        $dataProvider = new ActiveDataProvider([
            'query' => Comentario::find()
            ->leftJoin('tbl_usuario', 'tbl_comentario.idusuario =tbl_usuario.id')
            ->where(['idequipo' => $id])
            ->asArray(),

        ]);
        */

       

        $query = new Query;
        $query	->select(['*'])  
                ->from('tbl_comentario')
                ->join(	'LEFT OUTER JOIN', 
                        'tbl_usuario',
                        'tbl_comentario.idusuario =tbl_usuario.id'
                    ); 
        $command = $query->createCommand();
        $data = $command->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['username', 'texto'],
            ],
        ]);
        
        

        return $this->render('view', [
            'model' => $this->findModel($id),'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Equipo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Equipo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Equipo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Equipo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Equipo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Equipo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Equipo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}