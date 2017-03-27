<?php

namespace frontend\models;

use frontend\models\NestedSetCategory;

class ImageNestedSetCategory extends NestedSetCategory
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image_nested_set_category}}';
    }
}