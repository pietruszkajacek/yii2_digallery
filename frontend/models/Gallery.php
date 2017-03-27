<?php

namespace frontend\models;

use yii\db\ActiveRecord;
use frontend\components\TaggableCategoryBehavior;
use frontend\components\GalleryQuery;
use common\models\User;
use yii\behaviors\TimestampBehavior;

class Gallery extends ActiveRecord
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
        'ul' => 'ulubione'
    ];
    
    public static $pageSize = 18;

    public $galleryImages = [];
    
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
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
                ->viaTable('{{%gallery_tag_assn}}', ['gallery_id' => 'id']);
    }

    public function getCategory()
    {
        return $this->hasOne(GalleryNestedSetCategory::className(), ['id' => 'category_id']);
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function find()
    {
        return new GalleryQuery(get_called_class());
    }

    public static function getGalleries($filter, $order, $category = null, $userId = 0, $tags = [])
    {
        if (!array_key_exists($filter, Gallery::$timeFilter)) {
            throw new InvalidParamException('Filter param not exist');
        }
        
        if (!array_key_exists($order, Gallery::$sortOrder)) {
            throw new InvalidParamException('Order param not exist');
        }
        
        $sub_query = Gallery::find()
                //->select('image.*')
                ->where(['hidden' => 0]);
        
        if ($category !== null) {
            $galleryClass = Gallery::className(); 
            $model = new $galleryClass();
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
            $from = $now - Gallery::$timeFilter[$filter]['sec'];
            $sub_query->andWhere(['between', 'created_at', $from, $now]);
        }
        
        return $sub_query;
    }

}
