<?php

namespace frontend\widgets\selectcategory;

use yii\helpers\Html;

class SelectCategory extends \yii\jui\InputWidget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $options = ['id' => $this->options['id'] . '-selectcategory-widget'];
        
        echo Html::tag('div', '', $options);
        echo Html::activeHiddenInput($this->model, $this->attribute);

        $this->registerWidget('selectcategory', $options['id']);
    }

    protected function registerWidget($name, $id = null) 
    {
        SelectCategoryAsset::register($this->getView());
        
        if (!isset($this->clientOptions['input_name_category_id'])) {
            $this->clientOptions['input_name_category_id'] = Html::getInputName($this->model, $this->attribute);
        }
        
        parent::registerWidget($name, $id);
    }
}
