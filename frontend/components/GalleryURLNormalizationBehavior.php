<?php

namespace frontend\components;

use yii\base\Behavior;
use frontend\models\Gallery;
use yii\web\NotFoundHttpException;

/**
 * GalleryURLNormalizationBehavior
 * 
 * @author Jacek Pietruszka <pietruszka.jacek@gmail.com>
 */

class GalleryURLNormalizationBehavior extends Behavior
{
    public function normalizeURL($pathInfo, $matches) {
        $r = explode('/', $pathInfo);
        $route = '/' . $r[0] . '/' . $r[1] . '/';
        
        // gdy '/' wystepuje na końcu url to przekieruj na ten sam adres tylko bez końcowego '/'
        if (isset($matches[4]) && $matches[4] === '/') {
            \Yii::$app->response->redirect($route . $matches[2] . '-' . $matches[3], 302)->send();
            return false;
        }
        
        // usuwanie zer wiodących w id galerii - przekierowanie bez zer wiodących w id galerii
        if (preg_match('%^([0]+)([0-9]+)%', $matches[3], $galleryId)) {
            \Yii::$app->response->redirect($route . $matches[2] . '-' . $galleryId[2], 301)->send();
            return false;
        }
        
        // sprawdzenie czy galeria pod wskazanym id występuje w bazie oraz
        // sprawdzenie czy tytuł galerii z url odpowiada temu zapisanemu,
        // jeśli nie odpowiada to przekieruj na adres z prawidłową nazwą
        $gallery = Gallery::findOne($matches[3]);

        if (is_null($gallery)) {
            throw new NotFoundHttpException('Galeria nie została znaleziona.');
        }
        
        if ($gallery->name !== $matches[2]) {
            \Yii::$app->response->redirect($route . $gallery->name . '-' . $gallery->id, 301)->send();
            return false;
        }
        
        return true;
    }
}