<?php

namespace frontend\components;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use frontend\components\TagWithCategory;

/**
 * TaggableCategoryBehavior
 * 
 * based/inspired TaggableBehavior by Alexander Kochetov <creocoder@gmail.com>
 * https://github.com/creocoder/yii2-taggable
 * 
 * @author Jacek Pietruszka <pietruszka.jacek@gmail.com>
 */

class TaggableCategoryBehavior extends Behavior
{
    /**
     * @var string the user tags relation name
     */
    public $tagRelation = 'tags';

    /**
     * @var string|false the tags model frequency attribute name
     */
    public $tagFrequencyAttribute = 'frequency';

    /**
     * @var TagWithCategory[]
     */
    private $_tagsArray;

    /**
     * @var string the tags model value attribute name
     */
    public $tagValueAttribute = 'name';
    
    /**
     * @var string the tags model category attribute name
     */    
    public $tagCategoryAttribute = 'category';

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /**
     * Returns tags with category.
     * @return TagWithCategory[]
     */
    public function getTagsWithCategory()
    {
        if (!$this->owner->getIsNewRecord() && $this->_tagsArray === null) {
            $this->_tagsArray = [];

            foreach ($this->getTagsNameCategoryInfo() as $tag) {
                $this->_tagsArray[] = new TagWithCategory([
                    'name' => $tag[$this->tagValueAttribute],
                    'category' => (int) $tag[$this->tagCategoryAttribute]]);
            }
        }

        return $this->_tagsArray;
    }
    
    /**
     * Sets tags.
     * @param TagWithCategory[] $tagsWithCategory
     */
    public function setTagsWithCategory(array $tagsWithCategory)
    {
        $this->_tagsArray = array_unique($tagsWithCategory);
    }
    
    /**
     * Adds tags with category.
     * @param TagWithCategory[] $tagsWithCategory
     */
    public function addTagsWithCategory(array $tagsWithCategory)
    {
        $this->_tagsArray = array_unique(array_merge($this->tagsWithCategory, $tagsWithCategory));
    }

    /**
     * Removes tags with category.
     * @param TagWithCategory[] $tagsWithCategory
     */    
    public function removeTagsWithCategory(array $tagsWithCategory)
    {
        $this->_tagsArray = array_diff($this->tagsWithCategory, $tagsWithCategory);
    }
    
    /**
     * Removes all tags with category.
     */
    public function removeAllTagsWithCategory()
    {
        $this->_tagsArray = [];
    }

    /**
     * Returns a value indicating whether tags exists.
     * @param TagWithCategory[] $tagsWithCategory
     * @return boolean
     */
    public function hasTagsWithCategory(array $tagsWithCategory)
    {
        $twc = $this->tagsWithCategory;
        foreach ($tagsWithCategory as $tagWithCategory) {
            if (!in_array((string) $tagWithCategory, $twc)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return void
     */
    public function afterSave()
    {
        if ($this->_tagsArray === null) {
            return;
        }

        if (!$this->owner->getIsNewRecord()) {
            $this->beforeDelete();
        }

        $tagRelation = $this->owner->getRelation($this->tagRelation);
        $pivot = $tagRelation->via->from[0];
        /* @var ActiveRecord $class */
        $class = $tagRelation->modelClass;
        $rows = [];

        foreach ($this->_tagsArray as $tagWithCategory) {
            /* @var ActiveRecord $tag */
            $tag = $class::findOne([$this->tagValueAttribute => $tagWithCategory->name]);

            if ($tag === null) {
                $tag = new $class();
                $tag->setAttribute($this->tagValueAttribute, $tagWithCategory->name);
            }

            if ($this->tagFrequencyAttribute !== false) {
                $frequency = $tag->getAttribute($this->tagFrequencyAttribute);
                $tag->setAttribute($this->tagFrequencyAttribute, ++$frequency);
            }

            if ($tag->save()) {
                $rows[] = [$this->owner->getPrimaryKey(), $tag->getPrimaryKey(),
                    $tagWithCategory->category];
            }
        }

        if (!empty($rows)) {
            $this->owner->getDb()
                    ->createCommand()
                    ->batchInsert($pivot, [key($tagRelation->via->link), current($tagRelation->link), 
                        $this->tagCategoryAttribute], $rows)
                    ->execute();
        }
    }

    /**
     * @return void
     */
    public function beforeDelete()
    {
        $tagRelation = $this->owner->getRelation($this->tagRelation);
        $pivot = $tagRelation->via->from[0];

        if ($this->tagFrequencyAttribute !== false) {
            /* @var ActiveRecord $class */
            $class = $tagRelation->modelClass;

            $pks = (new Query())
                    ->select(current($tagRelation->link))
                    ->from($pivot)
                    ->where([key($tagRelation->via->link) => $this->owner->getPrimaryKey()])
                    ->column($this->owner->getDb());

            if (!empty($pks)) {
                $class::updateAllCounters([$this->tagFrequencyAttribute => -1], ['in', $class::primaryKey(), $pks]);
            }
        }

        $this->owner->getDb()
                ->createCommand()
                ->delete($pivot, [key($tagRelation->via->link) => $this->owner->getPrimaryKey()])
                ->execute();
    }
    
    /**
     * Returns array obejcts TagWithCategory.
     * @return TagWithCategory[]
     */
    public function getTagsNameCategoryInfo()
    {
        $tagRelation = $this->owner->getRelation($this->tagRelation);
        $pivot = $tagRelation->via->from[0];
        $tagClass = $tagRelation->modelClass;

        $result = (new Query())
                ->select([$this->tagValueAttribute, $this->tagCategoryAttribute])
                ->from(['pivot' => $pivot])
                ->innerJoin(['tag' => $tagClass::tableName()], 'pivot.tag_id = tag.id')
                ->where([
                    key($tagRelation->via->link) => $this->owner->getPrimaryKey(),
                        //'category' => $category
                ])
                ->all();

        return $result;
    }
}
