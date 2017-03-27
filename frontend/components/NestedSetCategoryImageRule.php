<?php

namespace frontend\components;

use yii\web\UrlRuleInterface;
use yii\base\Object;
use frontend\components\BrowseQueryParams;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

class NestedSetCategoryImageRule extends Object implements UrlRuleInterface
{
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
        if (preg_match('%^browse/images/((\w+\/)*)$%', $pathInfo, $matches)) {
            
            \Yii::$app->setComponents([
                'imageNestedSetCatPath' => [
                    'class' => 'frontend\components\ImageNestedSetCategoryPath'
                ]
            ]);
			
            \Yii::$app->imageNestedSetCatPath->path = $matches[1];
            
            $params['filter'] = BrowseQueryParams::normalizeFilter($request->getQueryParam('filter'));
            $params['order'] = BrowseQueryParams::normalizeOrder($request->getQueryParam('order'));
            $params['tags'] = BrowseQueryParams::normalizeTags($request->getQueryParam('tags'));
            
            try {
                $params['page'] = BrowseQueryParams::normalizePage($request->getQueryParam('page'));
            } catch (InvalidParamException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
                
            return ['browse/images', $params];
            
        } elseif (preg_match('%^browse/images((/\w+)*)$%', $pathInfo, $matches)) {
            \Yii::$app->response->redirect('/' . $pathInfo . '/', 301)->send();
            return;
        }

        return false;  // this rule does not apply
    }

}
