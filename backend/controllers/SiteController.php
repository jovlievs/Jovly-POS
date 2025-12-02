<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    protected function redirectByRole()
    {
        /** @var User|null $user */
        $user = Yii::$app->user->identity;

        if (!$user instanceof User) {
            return $this->redirect(['login']);
        }

        if ($user->isAdminOrManager()) {
            return $this->redirect(['index']); // backend dashboard
        }

        if ($user->isCashier()) {
            return $this->redirect(['pos/index']); // POS (New Sale)
        }

        return $this->redirect(['login']);
    }


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
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
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user->isCashier()) {
            // Cashier backend dashboardda qolmasin, darrov POS ga yuboramiz
            return $this->redirectByRole();
        }

        // Admin / Manager uchun dashboard
        return $this->render('index');
    }



    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirectByRole();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirectByRole();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
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
}
