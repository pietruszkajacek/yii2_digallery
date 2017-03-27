<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\NestedSetCategoryPath;
use yii\helpers\Url;

//use frontend\components\TagWithCategory;
use frontend\models\Image;

//use frontend\models\ImageNestedSetCategory;
/**
 * Browse controller
 */
class BrowseController extends Controller
{

    public function actions()
    {
        return [
            'stop18confirm' => 'frontend\actions\Stop18ConfirmAction',
        ];
    }
}
