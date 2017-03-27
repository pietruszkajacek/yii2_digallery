<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>This is the About page. You may modify the following file to customize its content:</p>

    <code><?= __FILE__ ?></code>
	
	<p><?= var_dump($model) ?></p>
	<p><?php var_dump(\Yii::$app->user->id); ?></p>
	<p><?php var_dump($hpath); ?></p>
	<p><?php var_dump($param); ?></p>
	<p><?= Yii::getAlias('@webroot'); ?></p>
	<p><?= Yii::getAlias('@bower'); ?></p>
    
    <p><?= Yii::getAlias('@app/migrations'); ?></p>
	
</div>
