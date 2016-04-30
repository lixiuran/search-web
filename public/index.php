<?php

/**
 * 搜索引擎入口
 * @author lixiuran <http://xiuran.me>
 * @see http://51find.cc
 */
define('APPLICATION_PATH', dirname(__DIR__));

$application = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");

$application->bootstrap()->run();