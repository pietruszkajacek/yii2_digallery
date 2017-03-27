<?php
namespace frontend\controllers;

use Yii;
use common\models\User;
use frontend\models\ContactForm;
use yii\web\Controller;

use frontend\models\NestedSetCategoryPath;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
//        
//        $path = new NestedSetCategoryPath();
//        
//        //$path->path = 'wszystkie/artystyczne/nagosc/nagosc_zakryta';
//        
//        $path->currentCategory = 6;
//        
//        //var_dump($path->pathFullInfo);
//        
//        var_dump($path->currentCategory->getImmediateSubordinatesNode()->all());
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }
}
