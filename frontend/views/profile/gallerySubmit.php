<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\widgets\selectcategory\SelectCategory;
use frontend\models\GalleryNestedSetCategory;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\GallerySubmitForm */

$this->title = $model->scenario === 'edit' ? 'Edycja galerii' : 'Utwórz galerię';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-submit">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'gallery-submit-form', 'options' => ['enctype' => 'multipart/form-data'], 'validateOnBlur' => false]) ?>
                <?= $form->field($model, 'title') ?>
                <?= $form->field($model, 'description')->textarea() ?>
                <?= $form->field($model, 'can_comment')->checkbox() ?>
                <?= $form->field($model, 'can_evaluated')->checkbox() ?>
                <?= $form->field($model, 'plus_18')->checkbox() ?>
                <?= $form->field($model, 'category_id')->widget(
                        SelectCategory::className(), [
                            'clientOptions' => [
                                'categories' => GalleryNestedSetCategory::getAdjacencyListModel(true)->asArray()->all(),
                            ],
                            'clientEvents' => [
                                'change' => 'function () { $("#' . $form->id . '").yiiActiveForm("validateAttribute", "' .
                                Html::getInputId($model, 'category_id').'"); }'
                            ],
                        ]) ?>
                <?= $form->field($model, 'tags') ?>
                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
