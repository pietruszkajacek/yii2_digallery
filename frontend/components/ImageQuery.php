<?php

namespace frontend\components;

use frontend\components\TaggableCategoryQueryBehavior;

class ImageQuery extends \yii\db\ActiveQuery
{
    public function behaviors()
    {
        return [
            TaggableCategoryQueryBehavior::className(),
        ];
    }
}