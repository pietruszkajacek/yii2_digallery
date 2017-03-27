<?php

namespace frontend\models;

class ImageWhoFavorite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image_who_favorite}}';
    }
}