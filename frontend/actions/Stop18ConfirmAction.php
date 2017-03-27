<?php

namespace frontend\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\web\NotFoundHttpException;

class Stop18ConfirmAction extends Action
{
    public function run()
    {
        if (!Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            Yii::$app->user->maturity = 1;

            return ["status" => 1];
        } else {
            throw new NotFoundHttpException();
        }
    }

}
