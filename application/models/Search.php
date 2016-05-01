<?php

/**
 * 搜索模型类
 * @author lixiuran <http://xiuran.me>
 */

class SearchModel 
{
    const PAGE_SIZE  = 10;
    const SP_INDEX   = 'cnblogs';
    
    protected $obj_cl    = null;
    protected $obj_db    = null;
    protected $obj_redis = null;
    protected $obj_page  = null;
    
    protected $hi_opts   = array();
    protected $p         = 1;
    protected $q         = '';
    
    /**
     * 初始化对象
     */
    public function __construct()
    {
        
        $this->obj_db   = Yaf_Registry::get('db');
        $this->obj_cl   = new SphinxClient();
        $this->_init();
        $this->getCrawlNum();
    }
    
    /**
     * 初始化参数
     */
    private function _init()
    {
        $config = Yaf_Registry::get('config');
        $this->obj_cl->SetServer($config->sphinx->hostname, (int) $config->sphinx->port);
        $this->obj_cl->SetConnectTimeout(3);
        $this->obj_cl->SetArrayResult(true);
        $this->obj_cl->SetMatchMode(SPH_MATCH_ANY);
        //高亮关键词
        $this->hi_opts=array(
            "before_match" => "<span style='font-weight:bold;color:red'>",
            "after_match" => "</span>"
        );
    }
    
    /**
     * 获取搜索后的数据,包括分页导航
     * @param string $q
     * @param string $p
     * @return array
     */
    public function getSeachData()
    {
        //设置偏移量
        if ($this->p > 0) {
            $offset = ($this->p-1) * self::PAGE_SIZE;
            $this->obj_cl->setLimits($offset, self::PAGE_SIZE);
        } else {
            $this->obj_cl->setLimits(0, self::PAGE_SIZE);
        }

        $result = $this->obj_cl->Query($this->q, "*");
        //分页高亮后的结果列表
        $page_data = array();
        
        if(isset($result['matches'])){
            $ids=join(',',Tools::array_column($result['matches'], 'id'));
            $db_data    = $this->getPageDataFromDb($ids);
            $page_data  = $this->highlightData($db_data, $this->q);
        }    
        
        //获取分页导航
        $params = array(
            'total_rows'=> $result['total'],
            'method'    => 'html',
            'parameter' => '/search?q='.$this->q.'&p=#p#',
            'now_page'  => $this->p,
            'list_rows' => self::PAGE_SIZE,
        );
        $this->obj_page = new Page($params);
        $page_nav = $this->obj_page->show(2);
        
        $return =  array(
            'page_data' => $page_data,
            'page_nav'  => $page_nav,
            'sph_res'   => $result,
            'crawl_total'   => $this->getCrawlNum(),
        );
        
        return $return;
    }
    
    /**
     * 通过主键查找一页的数据
     * @param string $ids 逗号隔开
     * @return array
     */
    public function getPageDataFromDb($ids)
    {
        if (empty($ids)) {
            return array();
        }
        $sql = "select id,title,post_time,description,link,post_author,create_time,view_count,comment_count from cnblogs_tb where id in({$ids})";
        $res = $this->obj_db->get_all($sql);
        return !empty($res) ? $res : array();
    }
    
    /**
     * 获取收录博文数
     */
    public function getCrawlNum() 
    {
        $sql = 'select count(id) as cnt from cnblogs_tb'; 
        $cnt = $this->obj_db->get_one($sql,'cnt');
        return $cnt ? $cnt : 0;       
    }
    
    /**
     * 搜索结果 关键词标红 [注意:bulid之后返回的是索引数组,与上边sql的字段要对应]
     * @param array $list 单页数据
     * @param string $q 关键词
     * @return array
     */
    public function highlightData($list, $q)
    {
        $hi_data = array();
        foreach ($list as $k=>$row) {
            $res=$this->obj_cl->BuildExcerpts($row, self::SP_INDEX, $q, $this->hi_opts);
            $hi_data[$k]['id'] = $res[0];
            $hi_data[$k]['title'] = $res[1];
            $hi_data[$k]['post_time'] = $res[2];
            $hi_data[$k]['description'] = $res[3];
            $hi_data[$k]['link'] = $res[4];
            $hi_data[$k]['post_author'] = $res[5];
            $hi_data[$k]['create_time'] = $res[6];
            $hi_data[$k]['view_count'] = $res[7];
            $hi_data[$k]['comment_count'] = $res[8];
         }
         return $hi_data;
    }
    
    /**
     * @return the $hi_opts
     */
    public function getHi_opts()
    {
        return $this->hi_opts;
    }

    /**
     * @return the $p
     */
    public function getP()
    {
        return $this->p;
    }

    /**
     * @return the $q
     */
    public function getQ()
    {
        return $this->q;
    }

    /**
     * @param multitype: $hi_opts
     */
    public function setHi_opts($hi_opts)
    {
        $this->hi_opts = $hi_opts;
    }

    /**
     * @param number $p
     */
    public function setP($p)
    {
        $this->p = $p;
    }

    /**
     * @param string $q
     */
    public function setQ($q)
    {
        $this->q = $q;
    }

}