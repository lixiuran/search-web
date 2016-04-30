<?php

/**
 * @name IndexController
 * @author lixiuran
 * @desc 默认控制器
 * @see http://51find.cc
 */
class IndexController extends Yaf_Controller_Abstract
{

    /**
     * 搜索首页
     * 
     * @param string $name            
     * @return boolean
     */
    public function indexAction()
    {
        return TRUE;
    }

    /**
     * 搜索列表页面
     * 
     * @return boolean
     */
    public function searchAction()
    {
        echo 'show';
        return false;
    }
}
