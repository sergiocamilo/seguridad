<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegisterForm;
use yii\widgets\ActiveForm;
use app\models\Usuario;
use yii\web\Html;
use yii\helpers\Url;
use app\controllers\User;


class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome(); 
        }
        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (User::isUserAdmin(Yii::$app->user->identity->id)){ 
                //Es un ejemplo, pero podria redireccionar a una vista de administraor.
                return $this->redirect(["site/index"]); 
            }
            else {
                return $this->redirect(["site/index"]); }
            } 
        else{
            return $this->render('login', ['model' => $model,]);
        } 
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
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

    private function randKey($str='', $long=0) {
        $key = null;
        $str = str_split($str); $start = 0;
        $limit = count($str)-1; 
        for($x=0; $x<$long; $x++) {
          $key .= $str[rand($start, $limit)]; 
        }
        return $key; 
    }

    public function actionConfirm()
    {
        $table = new Usuario;
        if (Yii::$app->request->get())
        {
            $id = $_GET["id"];
            $authKey = $_GET["authKey"];
            if ((int) $id)
            {
                $model = $table->find()->where("id=:id", [":id" => $id])->andWhere("authKey=:authKey", [":authKey" => $authKey]);
                if ($model->count() == 1)
                {
                    $activar = Usuario::findOne($id);
                    $activar->activate = 1;
                    if ($activar->update())
                    {
                        echo "Registro llevado a cabo correctamente, redireccionando ...";
                        echo "<meta http-equiv='refresh' content='8; ".Url::toRoute("/site/login")."'>";
                    }
                    else
                    {
                        echo "Ha ocurrido un error al realizar el registro, redireccionando ...";
                        echo "<meta http-equiv='refresh' content='8; ".Url::toRoute("/site/login")."'>";
                    }
                }
                else
                {
                    return $this->redirect(["site/login"]);
                }
            }
            else
            {
                return $this->redirect(["site/login"]);
            }
        }
    }



    public function actionRegister()
    {
        $model = new RegisterForm;
        $msg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()))
        {
            if($model->validate())
            {
                $table = new Usuario;
                $table->username = $model->username;
                $table->email = $model->email;
                $table->password = crypt($model->password, Yii::$app->params["salt"]);
                $table->authKey = $this->randKey("abcdef0123456789", 200);
                $table->accessToken = $this->randKey("abcdef0123456789", 200);

                //Si el registro es guardado correctamente
                if ($table->save())
                {
                    $user = $table->find()->where(["email" => $model->email])->one();
                    $id = urlencode($user->id);
                    $authKey = urlencode($user->authKey); 
                    $subject = "Confirmar registro";
                    $body = "<h1>Haga click en el siguiente enlace para finalizar tu registro</h1>";
                    $body .= "<a href='http://localhost:8888/futbolpika/web/index.php/site/confirm?id=".$id."&authKey=".$authKey."'>Confirmar</a>";
                    //Enviamos el correo
                    Yii::$app->mailer->compose()
                        ->setTo($user->email)
                        ->setFrom([Yii::$app->params["adminEmail"] => Yii::$app->params["title"]])
                        ->setSubject($subject)
                        ->setHtmlBody($body)
                        ->send();
                    $model->username = null;
                    $model->email = null;
                    $model->password = null;
                    $model->password_repeat = null;

                    $msg = "Ahora sólo falta que confirmes tu registro en tu cuenta de correo";
                }
                else
                {
                    $msg = "Ha ocurrido un error al llevar a cabo tu registro";
                }
            }
            else
            {
                $model->getErrors();
            }
        }
        return $this->render("register", ["model" => $model, "msg" => $msg]);
    }

}
