<?php

namespace frontend\components;

/**
 * TagWithCategory
 * 
 * @author Jacek Pietruszka <pietruszka.jacek@gmail.com>
 */
class TagWithCategory extends \yii\base\Object
{
    private $_name;
    private $_category;
    
    public function getName() {
        return $this->_name;
    }
    
    public function setName($value) {
        $this->_name = $value;
    }
    
    public function getCategory() {
        return $this->_category;
    }
    
    public function setCategory($value) {
        $this->_category = $value;
    }
    
    public function __toString()
    {
        return (string) $this->name;
    }
    
    public function init()
    {
        parent::init();
    }
}

