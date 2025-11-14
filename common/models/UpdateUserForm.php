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
    public $images;
    public $user;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    public function update()
    {
        if (!$this->validate() || !$this->user->validate()) {
            return null;
        }

        $user = User::findOne($this->user->id);
        $user->username = $this->user->username;
        $user->email = $this->user->email;

        // Handle image upload  
        if ($this->imageFile) {
            $imagename = Yii::$app->security->generateRandomString();
            $extension = $this->imageFile->extension;
            $filename = $imagename;

            $uploadPath = Yii::getAlias('@frontend/web/uploads/');

            $oldImage = Images::find()->where(['id_user' => $user->id])->one();
            if ($oldImage) {
                $oldFile = $uploadPath . DIRECTORY_SEPARATOR . $oldImage->path;
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
                $oldImage->delete();
            }

            $this->imageFile->saveAs($uploadPath . $filename);

            $image = new Images();
            $image->id_user = $user->id;
            $image->path = $filename;
            $image->extension = $extension;

            if (!$image->save()) {
                Yii::error('Failed to save image: ' . json_encode($image->errors));
                return null;
            }
        }

        return $user->save();
    }
}
