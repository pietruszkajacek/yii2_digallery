<?php

namespace frontend\components;

use yii\base\Behavior;
use yii\db\Expression;

class TaggableCategoryQueryBehavior extends Behavior
{
    public function anyTagValues($values, $attribute = null)
    {
        $model = new $this->owner->modelClass();
        $tagClass = $model->getRelation($model->tagRelation)->modelClass;
        $this->owner
            ->innerJoinWith($model->tagRelation, false)
            ->andWhere([$tagClass::tableName() . '.' . ($attribute ?: $model->tagValueAttribute) => $values])
            ->addGroupBy(array_map(function ($pk) use ($model) { return $model->tableName() . '.' . $pk; }, $model->primaryKey()));
        return $this->owner;
    }
    
    public function allTagValues($values, $attribute = null)
    {
        $model = new $this->owner->modelClass();
        return $this->anyTagValues($values, $attribute)->andHaving(new Expression('COUNT(*) = ' . count($values)));
    }    
}