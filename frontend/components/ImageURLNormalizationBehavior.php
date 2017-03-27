<?php

namespace frontend\components;

use yii\base\Behavior;
use frontend\models\Image;
use yii\web\NotFoundHttpException;

/**
 * ImageURLNormalizationBehavior
 * 
 * @author Jacek Pietruszka <pietruszka.jacek@gmail.com>
 */

class ImageURLNormalizationBehavior extends Behavior
{
    public function normalizeURL($pathInfo, $matches) {
        $r = explode('/', $pathInfo);
        $route = '/' . $r[0] . '/' . $r[1] . '/';
        
        // gdy '/' wystepuje na końcu url to przekieruj na ten sam adres tylko bez końcowego '/'
        if (isset($matches[4]) && $matches[4] === '/') {
            \Yii::$app->response->redirect($route . $matches[2] . '-' . $matches[3], 302)->send();
            return false;
        }
        
        // usuwanie zer wiodących w id zdjęcia - przekierowanie bez zer wiodących w id zdjęcia
        if (preg_match('%^([0]+)([0-9]+)%', $matches[3], $imageId)) {
            \Yii::$app->response->redirect($route . $matches[2] . '-' . $imageId[2], 301)->send();
            return false;
        }
        
        // sprawdzenie czy zdjęcie od wskazanym id występuje w bazie oraz
        // sprawdzenie czy nazwa zdjęcia z url odpowiada tej zapisanej,
        // jeśli nie odpowiada to przekieruj na adres z prawidłową nazwą
        $image = Image::findOne($matches[3]);

        if (is_null($image)) {
            throw new NotFoundHttpException('Praca nie została znaleziona.');
        }
        
        if ($image->file_name !== $matches[2]) {
            \Yii::$app->response->redirect($route . $image->file_name . '-' . $image->id, 301)->send();
            return false;
        }
        
        return true;
    }
}