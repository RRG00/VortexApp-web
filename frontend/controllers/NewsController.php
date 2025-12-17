<?php

namespace frontend\controllers;

use yii\web\Controller;

class NewsController extends Controller
{
    public function actionIndex()
    {
        $apiUrl = 'http://news.hardcoded.cloud/rtp/news ';

        $json = file_get_contents($apiUrl);
        $dados = json_decode($json, true);
        $noticias = $dados['items'] ?? [];

        return $this->render('index', [
            'noticias' => $noticias,
        ]);
    }
}
