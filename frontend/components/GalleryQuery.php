<?php

namespace frontend\components;

use frontend\components\TaggableCategoryQueryBehavior;

class GalleryQuery extends \yii\db\ActiveQuery
{
    public function behaviors()
    {
        return [
            TaggableCategoryQueryBehavior::className(),
        ];
    }
}