<?php

namespace frontend\components;

use Yii;

class DigalleryApplication extends \yii\web\Application
{
    /**
     * @inheritdoc
     */
    protected function bootstrap()
    {
        parent::bootstrap();
        
        Yii::setAlias('@uploads', '@webroot/uploads');
        Yii::setAlias('@uploads-images', '@uploads/images');
        Yii::setAlias('@uploads-thumbs-mini', '@uploads/thumbs/mini');
        Yii::setAlias('@uploads-thumbs-small', '@uploads/thumbs/small');
        Yii::setAlias('@uploads-thumbs-preview', '@uploads/thumbs/preview');   
    }
}

