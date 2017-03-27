<?php

namespace frontend\models;

class ImageTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag}}';
    }
}