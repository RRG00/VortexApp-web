<?php

namespace common\models;

use yii\base\Model;
use yii\web\UploadedFile;
use common\models\User;
use common\models\Images;
use Yii;

class UpdateUserForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $user;

    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['username', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username', 
                'filter' => function($query) {
                    $query->andWhere(['!=', 'id', $this->user->id]);
                },
                'message' => 'Este nome de usuário já está em uso.'
            ],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email',
                'filter' => function($query) {
                    $query->andWhere(['!=', 'id', $this->user->id]);
                },
                'message' => 'Este email já está em uso.'
            ],
            [['current_password', 'new_password', 'confirm_password'], 'string'],
            ['new_password', 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'new_password', 
                'message' => 'As senhas não coincidem.'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Nome de Usuário',
            'email' => 'Email',
            'current_password' => 'Senha Atual',
            'new_password' => 'Nova Senha',
            'confirm_password' => 'Confirmar Nova Senha',
            'imageFile' => 'Imagem de Perfil',
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return false;
        }

        // Validate current password if trying to change password
        if (!empty($this->new_password)) {
            if (empty($this->current_password)) {
                $this->addError('current_password', 'Por favor, insira sua senha atual.');
                return false;
            }
            
            if (!Yii::$app->security->validatePassword($this->current_password, $this->user->password_hash)) {
                $this->addError('current_password', 'Senha atual incorreta.');
                return false;
            }
            
            // Set new password
            $this->user->setPassword($this->new_password);
            $this->user->generateAuthKey();
        }

        // Update user fields
        $this->user->username = $this->username;
        $this->user->email = $this->email;

        // Handle image upload  
        if ($this->imageFile) {
            $imagename = Yii::$app->security->generateRandomString();
            $extension = $this->imageFile->extension;
            $filename = $imagename . '.' . $extension;

            $uploadPath = Yii::getAlias('@frontend/web/uploads/');

            // Delete old image if exists
            $oldImage = Images::find()->where(['id_user' => $this->user->id])->one();
            if ($oldImage) {
                $oldFile = $uploadPath . $oldImage->path;
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
                $oldImage->delete();
            }

            // Save new image file
            if (!$this->imageFile->saveAs($uploadPath . $filename)) {
                $this->addError('imageFile', 'Falha ao fazer upload da imagem.');
                return false;
            }

            // Save image record
            $image = new Images();
            $image->id_user = $this->user->id;
            $image->path = $filename;
            $image->extension = $extension;

            if (!$image->save()) {
                Yii::error('Failed to save image: ' . json_encode($image->errors));
                $this->addError('imageFile', 'Falha ao salvar informações da imagem.');
                return false;
            }
        }

        return $this->user->save(false); // false to skip validation since we already validated
    }
}