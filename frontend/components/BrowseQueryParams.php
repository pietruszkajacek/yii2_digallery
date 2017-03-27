<?php

namespace frontend\components;

use yii\base\Object;
use frontend\models\Image;
use common\helpers\TagHelper;
use yii\base\InvalidParamException;

class BrowseQueryParams extends Object
{
    public static function normalizeFilter($filter)
    {
        $result = 0;

        if (!is_null($filter)) {
            $filter = intval($filter);
            if (array_key_exists($filter, Image::$timeFilter)) {
                $result = $filter;
            }
        }

        return $result;
    }

    public static function normalizeOrder($order)
    {
        $result = key(Image::$sortOrder);

        if (!is_null($order)) {
            if (array_key_exists($order, Image::$sortOrder)) {
                $result = $order;
            }
        }

        return $result;
    }

    public static function normalizeTags($tags)
    {
        if ((!is_null($tags)) && (!empty($tags))) {
            $result = TagHelper::splitTags($tags);
        } else {
            $result = [];
        }

        return $result;
    }

    public static function normalizePage($page)
    {
        $result = 0;
        
        if (!is_null($page)) {
            $result = intval($page);
            
            if ($page < 0) {
                throw new InvalidParamException('Nieprawidłowa strona...');
            }
        }
        
        return $result;
    }

}
