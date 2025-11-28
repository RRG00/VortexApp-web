<?php

namespace backend\controllers;

use common\Models\User;
use app\Models\UserSearch;
use frontend\models\VerifyEmailForm;
use InvalidArgumentException;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller; 
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use backend\Models\SignupForm;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                                'allow' => true,
                                'actions' => ['index', 'create', 'view', 'update', 'delete'],
                                'roles' => ['admin', 'organizer'],
                            ],
                        ],
                ]
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
    $model = new SignupForm();

    $auth = Yii::$app->authManager;
    $roles = $auth->getRoles();

    $roleList = [];
    foreach ($roles as $role) {
        $roleList[$role->name] = $role->name;
    }

    if ($this->request->isPost && $model->load(Yii::$app->request->post())) {
        $model -> password = Yii::$app->getSecurity()->generateRandomString(10);

        $user = $model->signup();
        if (!$user instanceof \common\models\User) {
            Yii::$app->session->setFlash('error', 'Erro ao criar o utilizador.');
            return $this->render('create', [
                                'model' => $model,
                                'roleList' => $roleList
                                ]);
        }
       return $this->redirect(['view', 'id' => $user->id]);
    }

    return $this->render('create', [
        'model' => $model,
        'roleList' => $roleList 
    ]);

    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $auth = Yii::$app->authManager;
        $model = $this->findModel($id);
        $roles = $auth->getRoles();
        

            $roleList = [];
            foreach ($roles as $role) {
                $roleList[$role->name] = $role->name;
            }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {

            $auth = Yii::$app->authManager;
            $auth->revokeAll($model->id);
            

            if ($model->papel) {

                $role = $auth->getRole($model->papel);
                if ($role) {
                    $auth->assign($role, $model->id);

                }
    
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

       return $this->render('update', [
        'model' => $model,
        'roleList' => $roleList,
        ]);
    }

    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->verifyEmail()) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
