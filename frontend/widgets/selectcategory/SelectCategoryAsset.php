<?php
/**
 * @link 
 * @copyright Copyright (c) 
 * @license 
 */

namespace frontend\widgets\selectcategory;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files needed for the [[SelectCategory]] widget.
 *
 * @author Jacek Pietruszka <pietruszka.jacek@gmail.com>
 */
class SelectCategoryAsset extends AssetBundle
{
    //public $sourcePath = '@yii/assets';
	public $basePath = '@webroot';
    public $baseUrl = '@web';    
    public $js = [
        'js/selectcategory.js',
    ];
    public $depends = [
        'yii\jui\JuiAsset'
    ];
}
