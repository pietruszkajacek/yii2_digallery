<?php

namespace frontend\models;

use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;

abstract class NestedSetCategory extends ActiveRecord
{
    public function getSinglePath($fullInfoPath = false)
    {
        return $this->find()
                        ->select($fullInfoPath ? '*' : 'parent.short_name')
                        ->from(['node' => static::tableName(), 'parent' => static::tableName()])
                        ->where(['and',
                            ['between', 'node.lft',
                                new Expression($this->getDb()->quoteColumnName('parent.lft')),
                                new Expression($this->getDb()->quoteColumnName('parent.rgt'))],
                            ['node.id' => $this->id]])
                        ->orderBy(['parent.lft' => SORT_ASC]);
    }

    public function getImmediateSubordinatesNode()
    {
        return $this->find()
                        ->select(['node.id', 'node.lft', 'node.rgt', 'node.name', 'node.short_name', 'depth' => '(COUNT(parent.id) - (sub_tree.depth + 1))'])
                        ->from(['node' => static::tableName(),
                            'parent' => static::tableName(),
                            'sub_parent' => static::tableName(),
                            'sub_tree' => (new Query())
                            ->select(['node.id', 'depth' => '(COUNT(parent.id) - 1)'])
                            ->from(['node' => static::tableName(),
                                'parent' => static::tableName()])
                            ->where(['and',
                                ['between', 'node.lft',
                                    new Expression($this->getDb()->quoteColumnName('parent.lft')),
                                    new Expression($this->getDb()->quoteColumnName('parent.rgt'))],
                                ['node.id' => $this->id]])
                            ->groupBy('node.id')
                            ->orderBy('node.lft', SORT_ASC)])
                        ->where(['and',
                            ['between', 'node.lft',
                                new Expression($this->getDb()->quoteColumnName('parent.lft')),
                                new Expression($this->getDb()->quoteColumnName('parent.rgt'))],
                            ['between', 'node.lft',
                                new Expression($this->getDb()->quoteColumnName('sub_parent.lft')),
                                new Expression($this->getDb()->quoteColumnName('sub_parent.rgt'))],
                            ['sub_parent.id' => new Expression($this->getDb()->quoteColumnName('sub_tree.id'))]])
                        ->groupBy('node.id')
                        ->having(['depth' => 1])
                        ->orderBy('node.lft', SORT_ASC);
    }

    /**
     * Convert Tree Structure From Nested Set Into Adjacency List
     * Credit goes to Plamen Ratchev
     * based on http://pratchev.blogspot.com/2007/02/convert-tree-structure-from-nested-set.html
     */
    public static function getAdjacencyListModel($excludeRoot = false, $name = 'name_cat', $shortName = 'short_name_cat',
            $parent = 'parent_cat_id')
    {
        $className = get_called_class();
        
        $q = $className::find()
                ->select('A.id, A.name AS ' . $name . ', A.short_name AS ' . $shortName . ', B.id AS ' . $parent . ', A.lft, A.rgt')
                ->from(['A' => static::tableName()])
                ->leftJoin(static::tableName() . ' B', 
                        'B.lft = (SELECT MAX(C.lft)' .
                        '   FROM ' . static::tableName() . ' AS C' .
                        '  WHERE A.lft > C.lft' .
                        '    AND A.lft < C.rgt' . ($excludeRoot ? ' AND C.lft != 1)' : ')')
                        );
        
        if ($excludeRoot) {
            $q->where('A.lft != 1');
        }
        
        return $q;
    }

}