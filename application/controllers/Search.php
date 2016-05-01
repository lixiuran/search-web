<?php

/**
 * @name SearchController
 * @author lixiuran
 * @desc 搜索控制器
 * @see http://51search.cc
 */
class SearchController extends Yaf_Controller_Abstract
{
    
    public $model_serach = null;
    
    public function init()
    {
        $this->model_serach = new SearchModel(); 
    }

    /**
     * 搜索列表页面
     * 
     * @param string $name            
     * @return boolean
     */
    public function indexAction()
    { 
        //接受参数
        $q = strip_tags(trim($_GET['q']));
        $p = isset($_GET['p']) && !empty($_GET['p']) ? sprintf('%d', $_GET['p']) : 1;
        
        //设置参数
        $this->model_serach->setP($p);
        $this->model_serach->setQ($q);
        
        //返回数据
        $search_data = $this->model_serach->getSeachData();
         
        //分配变量
        $this->getView()->assign('page_nav', $search_data['page_nav']);
        $this->getView()->assign('page_data', $search_data['page_data']);
        $this->getView()->assign('req', $_REQUEST);
        $this->getView()->assign('sph_res', $search_data['sph_res']);
        
        return TRUE;
    }
    
    public function debug($arr)
    {
        if (is_array($arr)) {
            echo '<pre>';print_r($arr);die;
        } else {
            echo '<pre>';var_dump($arr);die;
        }
    }
}
