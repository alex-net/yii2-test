<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\ContactForm;
use yii\helpers\Html;
use app\models\User;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
            'ulogin'=>[
                'class' => \rmrevin\yii\ulogin\AuthAction::className(),
                'successCallback' => [$this, 'uloginenter'],
                'errorCallback' => function($data) {Yii::error($data['error']);},
            ],
        ];
    }

    public function beforeAction($a)
    {
        // отключить проверку csrf для post зварса от ulogin = всё портит 
        if ($a->className() == \rmrevin\yii\ulogin\AuthAction::className()) {
            $this->enableCsrfValidation = false;
        }
        if (parent::beforeAction($a)) {
            return true;
        }
        return false;
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
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /** регистрация ... */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest)  {
            return $this->goHome();
        }
       
        $reg=new RegisterForm();
        if (Yii::$app->request->isPost && $reg->load(Yii::$app->request->post()) && $reg->register()) {
            return $this->goHome();
        }

       return $this->render('register', ['model' => $reg]); 
            
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

    // список юзерей   
    public function actionUsers()
    {
        $u=new \yii\data\ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return  $this->render('users-list', ['p' => $u]);
    }
    // генерация ссылки редактрования юзера ..
    public static function userlink($m, $k, $i, $c){
        return Html::a($m->{$c->attribute}, ['site/edit', 'uid' => $m->id]);
    }

    // страница редактирования юзера .. 
    public function actionEdit($uid)
    {
        $u = User::find()->where(['id' => $uid])->limit(1)->one();
        if (!$u) {
            $this->goBack();
        }
        $f = new \app\models\EditForm(['_user' => $u]);

        if (Yii::$app->request->isPost && $f->save(Yii::$app->request->post(), $u)) {
            return $this->redirect(['site/users']);
        }

        return $this->render('user-edit', ['model' => $f]);
    }

    public function uloginenter($attrs)
    {
        $type = Yii::$app->request->get('type', '');
        
        switch($type) {
            case 'register':
                $u = User::UloginRegister($attrs['network'] . '-' . $attrs['uid']);
                break;
            case 'login':
                $u = User::findByUsername($attrs['network'] . '-' . $attrs['uid']);
                // юзер пришел не из соцсети .. - не пущать !
                if ($u && !$u->fromulogin) {
                    unset($u);
                }
        }
        if (!empty($u)) {
            Yii::$app->user->login($u);
        }
        $this->goHome();
    }
}
