<?php

namespace frontend\models;

use yii\db\ActiveRecord;
use frontend\components\TaggableCategoryBehavior;
use frontend\components\ImageQuery;
use common\models\User;
use yii\behaviors\TimestampBehavior;


class Image extends ActiveRecord
{
    public static $timeFilter = [
        10 => ['desc' => '8 godz.', 'sec' => 28800],
        11 => ['desc' => '24 godz.', 'sec' => 86400],
        12 => ['desc' => '3 dni', 'sec' => 259200],
        13 => ['desc' => '1 tydzień', 'sec' => 604800],
        14 => ['desc' => '1 miesiac', 'sec' => 2592000],
        0 => ['desc' => 'wszystkie'],
    ];
    
    public static $sortOrder = [
        'dd' => ['desc' => 'data dodania', 'attrs' => 'submitted'],
        'oc' => 'ocena',
        'ul' => 'ulubione',
    ];
    
    public static $pageSize = 18;
       
    public function behaviors()
    {
        return [
            'taggable' => [
                'class' => TaggableCategoryBehavior::className(),
            // 'tagNamesAsArray' => false,
            // 'tagRelation' => 'tags',
            // 'tagNameAttribute' => 'name',
            //'tagFrequencyAttribute' => false,
            ],
            TimestampBehavior::className(),
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function getTags()
    {
        return $this->hasMany(ImageTag::className(), ['id' => 'tag_id'])
                ->viaTable('{{%image_tag_assn}}', ['image_id' => 'id']);
    }
    
    public function getWhoFavorite() {
        return $this->hasMany(ImageWhoFavorite::className(), ['image_id' => 'id']);
    }
    
    public function getCategory()
    {
        return $this->hasOne(ImageNestedSetCategory::className(), ['id' => 'category_id']);
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function find()
    {
        return new ImageQuery(get_called_class());
    }

    public static function getImages($filter, $order = null, $category = null, $tags = [], $userId = 0)
    {
        if (!array_key_exists($filter, Image::$timeFilter)) {
            throw new InvalidParamException('Filter param not exist');
        }
        
        if (!is_null($order) && !array_key_exists($order, Image::$sortOrder)) {
            throw new InvalidParamException('Order param not exist');
        }
        
        $sub_query = Image::find()
                //->select('image.*')
                ->where(['hidden' => 0]);
        
        if ($category !== null) {
            $imageClass = Image::className(); 
            $model = new $imageClass();
            $categoryClass = $model->getRelation('category')->modelClass;
    
            $sub_query
                    ->joinWith(['category'], false, 'INNER JOIN')
                    ->andWhere(['between', $categoryClass::tableName() . '.' . 'lft', $category->lft, $category->rgt]);
        }

        if ($userId !== 0) {
            $sub_query->andWhere(['user_id' => $userId]);
        }
        
        if ($filter !== 0) {
            $now = time();
            $from = $now - Image::$timeFilter[$filter]['sec'];
            $sub_query->andWhere(['between', 'created_at', $from, $now]);
        }
        
        if (!empty($tags)) {
            $sub_query->allTagValues($tags);
        }
        
        if ($order === 'dd') {
            $query = Image::find()
                    ->from(['images_tags_filtered' => $sub_query])
                    ->orderBy(['images_tags_filtered.created_at' => SORT_DESC]);
        } elseif ($order === 'ul') {
            $query = Image::find()
                    ->select('*, COUNT(*) AS fav_counter')
                    ->from(['images_tags_filtered' => $sub_query])
                    //->leftJoin('image_who_favorite', ['images_tags_filtered.id' => 'image_who_favorite.image_id'] );
                    ->joinWith(['whoFavorite'], false, 'LEFT JOIN')
                    ->groupBy('images_tags_filtered.id')
                    ->orderBy(['fav_counter' => SORT_DESC, 'image_who_favorite.image_id' => SORT_DESC, 'images_tags_filtered.created_at' => SORT_DESC]);
        } elseif ($order === 'oc') {
            $query = Image::find()
                    ->select('*, AVG(rate) AS average')
                    ->from(['images_tags_filtered' => $sub_query])
                    ->leftJoin('image_evaluation', ['images_tags_filtered.id' => 'image_evaluation.image_id'] )
                    ->groupBy('images_tags_filtered.id')
                    ->orderBy(['average' => SORT_DESC, 'image_evaluation.image_id' => SORT_DESC, 'images_tags_filtered.created_at' => SORT_DESC]);
        } else {
            $query = $sub_query;
        }

        return $query;
    }

}
