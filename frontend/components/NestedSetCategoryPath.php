<?php

namespace frontend\components;

use yii\base\Object;
use Yii;
use frontend\models\NestedSetCategory;
use yii\base\InvalidParamException;

/**
 * Nested set category path
 */
abstract class NestedSetCategoryPath extends Object
{
    //public $categoryPath = [];
    private $_path = [];
    private $_pathFullInfo = [];
    private $_currentCategory = null;

    abstract protected function findCategoryByShortName($shortName);
    abstract protected function findCategoryById($id);

    private function parseCategoryPath(array $path)
    {
        $result = [];

        if (!empty($path)) {
            end($path);

            foreach ($this->findCategoryByShortName(current($path)) as $node) {
                $tmpPath = \array_map(function($cat)
                {
                    return $cat['short_name'];
                }, $node->getSinglePath()->asArray()->all());

                if ($tmpPath === $path) {
                    $result = $node->getSinglePath(true)->all();
                    break;
                }
            }
        }

        return $result;
    }

    public function setPath($path)
    {
        $this->_path = empty($path) ? [] : explode('/', trim($path, '/'));

        if (!empty($this->_path) && empty($this->_pathFullInfo = $this->parseCategoryPath($this->_path))) {
            throw new InvalidParamException('Invalid category path.');
        }
    }

    public function getPath()
    {
        return $this->_path;
    }

    public function getPathFullInfo()
    {
        return $this->_pathFullInfo;
    }

    public function setCurrentCategory($id)
    {
        if (is_null($category = $this->findCategoryById(intval($id)))) {
            throw new InvalidParamException('Category id not exist.');
        } else {
            $this->_pathFullInfo = $category->getSinglePath(true)->all();
        }
    }

    public function getCurrentCategory()
    {
	return empty($this->_pathFullInfo) ? null : end($this->_pathFullInfo);
    }
}