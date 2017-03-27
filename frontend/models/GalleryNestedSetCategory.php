<?php

namespace frontend\models;

use frontend\models\NestedSetCategory;

class GalleryNestedSetCategory extends NestedSetCategory
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gallery_nested_set_category}}';
    }   
}