<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\models\ImageNestedSetCategory;
use yii\web\Response;
use frontend\models\ImageSubmitForm;
use frontend\models\GallerySubmitForm;
use frontend\models\Image;
use yii\web\BadRequestHttpException;

class ProfileController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['image-submit'],
                'rules' => [
                    [
                        'actions' => ['image-submit'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionGallerySubmit($galleryId = null)
    {
        $model = new GallerySubmitForm();
         
        return $this->render('gallerySubmit', [
                    'model' => $model,
        ]);
    }
    
    public function actionImageSubmit($imageId = null)
    {
        $model = new ImageSubmitForm();

        if (!is_null($imageId)) {
            $model->scenario = 'edit';

            $image = Image::findOne($imageId);

            if (is_null($image)) {
                throw new BadRequestHttpException('Image not found!');
            }
            
            if ($model->load(Yii::$app->request->post())) {
                $model->updateImage($image)
                        ? Yii::$app->getSession()->setFlash('info', 'Praca została zaktualizowana.')
                        : Yii::$app->getSession()->setFlash('error', 'Niestety nie udało się zaktualizować pracy.');
            } else {
                $model->attributes = $image->attributes;

                /* @var $tag frontend\components\TagWithCategory */
                foreach ($image->TagsWithCategory as $tag) {
                    if ($tag->category === 1) {
                        $model->tags .= $tag->name . ' ';
                    }
                }
            }
        } else {
            if ($model->load(Yii::$app->request->post())) {
                $model->submitImage()
                        ? Yii::$app->getSession()->setFlash('info', 'Praca została wysłana.')
                        : Yii::$app->getSession()->setFlash('error', 'Niestety nie udało się wysłać pracy.');
            } else {
                $model->can_comment = 1;
            }
        }

        return $this->render('imageSubmit', [
                    'model' => $model,
        ]);
    }
    
    public function actionTest($id) {
        echo $id;
    }
}
