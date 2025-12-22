<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $email;
    public $isBackendLogin = false;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            
            if (!$user) {
                $this->addError($attribute, 'Nome de usuário ou senha incorretos.');
                return;
            }
            
            // Check if user account is disabled/inactive
            if ($user->status === User::STATUS_INACTIVE || $user->status === User::STATUS_DELETED) {
                $this->addError('username', 'Esta conta foi desativada. Entre em contato com o suporte para reativá-la.');
                return;
            }
            
            // Check if user is not active
            if ($user->status !== User::STATUS_ACTIVE) {
                $this->addError('username', 'Esta conta não está ativa. Entre em contato com o suporte.');
                return;
            }
            
            // Validate password
            if (!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Nome de usuário ou senha incorretos.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();

            // Double-check user status before login
            if ($user && !$user->isActive()) {
                $this->addError('username', 'Esta conta foi desativada. Entre em contato com o suporte para reativá-la.');
                return false;
            }

            // Check backend access if it's a backend login
            if ($user && $this->isBackendLogin) {
                if (!Yii::$app->authManager->checkAccess($user->id, 'accessBackend')) {
                    $this->addError('username', 'Sua conta não possui permissão para acessar o Backend.');
                    return false;
                }
            }

            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            // Find user regardless of status to show appropriate error message
            $this->_user = User::findOne(['username' => $this->username]);
        }

        return $this->_user;
    }
}