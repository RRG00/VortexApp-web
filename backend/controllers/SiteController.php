<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use common\models\User;
use common\models\Tournament;

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
                'denyCallback' => function () {
                    return Yii::$app->response->redirect(['/site/login']);
                },
                'only' => ['login','logout', 'index'],
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'], 
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['usersDashboard'],
                    ],
                    ],
                        ],
                        'verbs' => [
                            'class' => VerbFilter::class,
                            'actions' => [
                                'logout' => ['POST'],
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



        $admins = \common\models\User::find()->where(['papel' => 'admin'])->count();
        $organizers = \common\models\User::find()->where(['papel' => 'organizer'])->count();
        $players = \common\models\User::find()->where(['papel' => 'player'])->count();
        $referees = \common\models\User::find()->where(['papel' => 'referre'])->count();

        $months = [];
        $registrations = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $months[] = date('M Y', strtotime($date));

            $count = User::find()
                ->where(['>=', 'created_at', strtotime("first day of $date")])
                ->andWhere(['<', 'created_at', strtotime("first day of $date +1 month")])
                ->count();

            $registrations[] = $count;
        }

        $tournaments = Tournament::find()->all();

        return $this->render('index', [
            'admins' => $admins,
            'organizers' => $organizers,
            'players' => $players,
            'referees' => $referees,
            'months' => $months,
            'registrations' => $registrations,
            'tournaments' => $tournaments,
        ]);
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

    $this->layout = 'blank';

    $model = new LoginForm();
    $model->isBackendLogin = true;

    if ($model->load(Yii::$app->request->post()) && $model->login()) {
        return $this->redirectByRole();
    }

    $model->password = '';

    return $this->render('login', [
        'model' => $model,
    ]);
    }

   private function redirectByRole()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser(Yii::$app->user->id);

        if (isset($roles['admin'])) {
            return $this->redirect(['site/index']);       
        }
        if (isset($roles['organizer'])) {
            return $this->redirect(['tournament/index']); 
        }
        if (isset($roles['referee'])) {
            return $this->redirect(['referee-dashboard/index']); 
        }

        return $this->goHome(); 
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
