<?php

namespace frontend\components;

use yii\base\Object;
use Yii;
use frontend\components\NestedSetCategoryPath;
use frontend\models\ImageNestedSetCategory;
use yii\base\InvalidParamException;

/**
 * Nested set category path
 */
class ImageNestedSetCategoryPath extends NestedSetCategoryPath
{

   public function findCategoryByShortName($shortName)
    {
        return ImageNestedSetCategory::findAll(['short_name' => $shortName]);
    }
    
    public function findCategoryById($id)
    {
        return ImageNestedSetCategory::findOne($id);
    }
}
