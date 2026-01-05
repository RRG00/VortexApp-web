<?php

namespace common\models;

use yii\base\Model;
use yii\web\UploadedFile;
use common\models\Equipa;
use common\models\Images;
use Yii;

class UpdateTeamForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $images;
    public $equipa;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    public function update()
    {
        // Validar nome da equipa
        if (!preg_match('/^[A-Za-z0-9]+$/', $this->equipa->nome)) {
            $this->equipa->addError('nome', 'O nome da equipa só pode conter letras (A-Z) e números (0-9).');
            return null;
        }

        if (!$this->validate() || !$this->equipa->validate()) {
            return null;
        }

        $equipa = Equipa::findOne($this->equipa->id);
        $equipa->nome = $this->equipa->nome;

        // Handle image upload  
        if ($this->imageFile) {
            $imagename = Yii::$app->security->generateRandomString();
            $extension = $this->imageFile->extension;
            $filename = $imagename;

            $uploadPath = Yii::getAlias('@frontend/web/uploads/');

            $oldImage = Images::find()->where(['id' => $equipa->id])->one();
            if ($oldImage) {
                $oldFile = $uploadPath . DIRECTORY_SEPARATOR . $oldImage->path;
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
                $oldImage->delete();
            }

            $this->imageFile->saveAs($uploadPath . $filename);

            $image = new Images();
            $image->id_equipa = $equipa->id;
            $image->path = $filename;
            $image->extension = $extension;

            if (!$image->save()) {
                Yii::error('Failed to save image: ' . json_encode($image->errors));
                return null;
            }
        }

        return $equipa->save();
    }
}
