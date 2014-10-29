<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.10.14
 * Time: 9:43
 */

namespace app\components;

use yii\web\UrlRule;
use app\models\Content;
use yii\mongodb\Query;


class PageUrlRule extends UrlRule
{

    public $connectionID = 'mongodb';
    public $pattern = 'site';
    public $route = 'site';


    public function init()
    {
        if ($this->name === null) {
            $this->name = __CLASS__;
        }
    }

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();

        if(preg_match('/manager/', $pathInfo)){
            return false;
        }


        if (preg_match('#^([\w-]+)#i', $pathInfo, $matches)) {

            $query = new Query;
            // compose the query
            $query->select(['id'])
                ->where(array('alias'=>$matches[1]))
                ->from('Content')
                ->limit(1);

            // execute the query
            $page = $query->one();

            if ($page !== null) {

                $params = ['id'=>$page['id']];

                return ['site/index', $params];

            }else{
                return false;
            }
        }else{
            //TODO доделать компонент-настроек и работу с ним
            //$_GET['id'] = 1;
            //yml-lowara.html
            //$_GET['id'] = (int) Yii::app()->config->get('SYSTEM.MAIN_PAGE');;

            $params = ['id'=>1];

            return ['site/index', $params];
        }

        return false;
    }
    /*
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {

        if(!preg_match('#manager#i', $pathInfo)){
            if (preg_match('#^([\w-]+)#i', $pathInfo, $matches)) {
                $query = new Query;
                // compose the query
                $query->select(['_id'])
                    ->where('alias='.$matches[1])
                    ->from('Content')
                    ->limit(1);
                // execute the query
                $page = $query->one();

                if ($page !== null) {
                    //$_GET['id'] = $page->_id;
                    $_GET['id'] = $page['_id'];
                    return 'site/index';
                }else{

                }
            }else{
                //TODO доделать компонент-настроек и работу с ним
                $_GET['id'] = 1;
                //$_GET['id'] = (int) Yii::app()->config->get('SYSTEM.MAIN_PAGE');;
                return 'site/index';
            }
        }else{
            return false;
        }
    }
*/

    public function createUrl($manager, $route, $params)
    {
        if ($route == 'site/index') {
            if (!empty($params['id'])) {


                $criteria = new EMongoCriteria(array(
                    'condition' => array('id'=>$params['id']),
                ));
                $page = Content::model()->findOne($criteria);

                if(!empty($page->alias)){
                    return $page->alias.'.html';
                }else{
                    return '';
                }


            }elseif(!empty($params['alias'])){

                if(!empty($params['alias'])){
                    return $params['alias'].'.html';
                }else{
                    return '';
                }


            }
        }
        return false;
    }
}