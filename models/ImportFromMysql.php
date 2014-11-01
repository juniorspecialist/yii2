<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.10.14
 * Time: 13:17
 */

namespace app\models;


class ImportFromMysql {

    /*
     * импортируем документы из БД mysql
     */
    public function importContent(){
        $sql = 'SELECT * FROM modx_site_content';
        $rows = \Yii::$app->db->createCommand($sql)->queryAll();
        $content = \Yii::$app->mongodb->getCollection('Content');
        foreach($rows as $row){
            $row['id'] = (int)$row['id'];
            $row['parent'] = (int)$row['parent'];
            $row['published'] = (int)$row['published'];
            $row['isfolder'] = (int)$row['isfolder'];
            $row['template'] = (int)$row['template'];
            $row['menuindex'] = (int)$row['menuindex'];
            $row['searchable'] = (int)$row['searchable'];
            $row['cacheable'] = (int)$row['cacheable'];
            $row['deleted'] = (int)$row['deleted'];
            $row['hidemenu'] = (int)$row['hidemenu'];
            //если PARENT=0, привяжим его к первому уровню, для построения корректного дерева
            if($row['id']==1){
                //укажим начало ДЕРЕВА(главное звено)
                $row['parent'] = 0;
            }else{
                //если не указана подвязка, то подвязываем к самому первому элементу дерева
                if($row['parent']==0){
                    $row['parent'] = 1;
                }else{
                    $row['parent'] = (int)$row['parent'];
                }
            }


            //получаем список тв-параметров по документу+названия этих тв-параметров
            $tv_params = array();

            $tvs = \Yii::$app->db->createCommand('SELECT tmplvarid, value FROM modx_site_tmplvar_contentvalues WHERE contentid="'.$row['id'].'"')->queryAll();
            if(!empty($tvs)){
                foreach($tvs as $tv){
                    //получаем по каждому тв-параметру его название
                    $tv_name_row = \Yii::$app->db->createCommand('SELECT name FROM modx_site_tmplvars WHERE id="'.$tv['tmplvarid'].'"')->queryOne();
                    if(!empty($tv_name_row['name'])){
                        $row['tv_'.$tv_name_row['name']] = $tv['value'];
                    }
                }
            }
           $content->insert($row);
        }
    }

    public function run(){
        //импортирем документы из модкс
        //$this->importContent();

        //импортируем чанки
        //$this->importChunk();

        //импортируем шаблоны
        //$this->importTemplate();

    }


    /*
     * импорт чанков в систему
     */
    public function importChunk(){
        $sql = 'SELECT * FROM modx_site_htmlsnippets';
        $rows = \Yii::$app->db->createCommand($sql)->queryAll();
        foreach($rows as $row){
            $chunk = new Chunk();
            $chunk->title = $row['name'];
            $chunk->desc = $row['description'];
            $chunk->content = $row['snippet'];
            $chunk->save();
        }
    }



    /*
     * импорт тв-параметров(списоких настройки и параметры)
     */
    public function importTvParams(){
        $sql = 'SELECT * FROM modx_site_tmplvars';
        $rows = \Yii::$app->db->createCommand($sql)->queryAll();
        foreach($rows as $row){
            $tv = new Tv();
            //$tv->id = (int)$row['id'];
            $tv->type = $row['type'];
            $tv->name = $row['name'];
            $tv->caption = $row['caption'];
            $tv->description = $row['description'];
            $tv->elements = $row['elements'];
            $tv->default_text = $row['default_text'];
            $tv->save();
        }
    }

    /*
        * импортируем список шаблонов
        * +список подвязанных к шаблону тв-параметров
        */
    public function importTemplate(){
        $db = \Yii::$app->db;
        //получаем список шаблонов
        $tpl_list = $db->createCommand('SELECT * FROM modx_site_templates')->queryAll();

        $Template = \Yii::$app->mongodb->getCollection('Template');

        if(!empty($tpl_list)){
            foreach($tpl_list as $tpl){

                $tpl['id'] = (int)$tpl['id'];
                $tpl['category'] = (int)$tpl['category'];

                //по каждому шаблону находим список подвязанных к нему тв-параметров и запишим их
                $tv_params_by_tpl = $db->createCommand('SELECT tmplvarid FROM modx_site_tmplvar_templates WHERE templateid="'.$tpl['id'].'"')->queryAll();

                foreach($tv_params_by_tpl as $tv_id){
                    //получаем по каждому тв-параметру его название
                    $tv_name_row = \Yii::$app->db->createCommand('SELECT name FROM modx_site_tmplvars WHERE id="'.$tv_id['tmplvarid'].'"')->queryOne();
                    $tpl[$tv_name_row['name']] = $tv_name_row['name'];
                }

                $Template->insert($tpl);
            }
        }

    }
} 