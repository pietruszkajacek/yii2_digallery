<?php

namespace frontend\components;

use yii\web\UrlRuleInterface;
use yii\base\Object;
use frontend\components\BrowseQueryParams;
use frontend\components\ImageURLNormalizationBehavior;
use yii\base\Component;
use yii\web\NotFoundHttpException;

class SubmitEditImageRule extends Component implements UrlRuleInterface
{
    public function behaviors()
    {
        return [
            'normalizeImageName' => [
                'class' => ImageURLNormalizationBehavior::className(),
            ],
        ];
    }

    public function createUrl($manager, $route, $params)
    {
        //var_dump($route);
        
        if ($route === 'browse/images') {
//            echo ' - in if';
//            var_dump($params);
//            exit;
//            if (isset($params['manufacturer'], $params['model'])) {
//                return $params['manufacturer'] . '/' . $params['model'];
//            } elseif (isset($params['manufacturer'])) {
//                return $params['manufacturer'];
//            }
        }
        
        return false;  // this rule does not apply
    }

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        if (preg_match('%^profile/image-submit/(([\w\-]+)-(\d+)(/)*)*$%', $pathInfo, $matches)) {
           
            if (empty($matches[1])) {
                $params = ['imageId' => null];
                return ['profile/image-submit', $params];
            }
            
//            try {
            if ($this->normalizeURL($pathInfo, $matches)) {
                $params = ['imageId' => $matches[3]];
                return ['profile/image-submit', $params];
            } else {
                return;
            }
//            } catch (NotFoundHttpException $ex) {
//                return false;
//            }
        } elseif (preg_match('%^profile/image-submit$%', $pathInfo, $matches)) {
            \Yii::$app->response->redirect('/' . $pathInfo . '/', 301)->send();
            return;
        }

        return false;  // this rule does not apply
    }

}
