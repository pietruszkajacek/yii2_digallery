<?php

namespace frontend\components;

use yii;

class User extends \yii\web\User
{
    public function init()
    {
        parent::init();
    }

    public function getMaturity()
    {
        if (is_null(Yii::$app->session['maturity'])) {
            $this->maturity = 0;
        }
        
        return Yii::$app->session['maturity'];
    }

    public function setMaturity($value)
    {
        Yii::$app->session['maturity'] = $value;
    }

}
