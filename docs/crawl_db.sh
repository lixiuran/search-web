#!/bin/bash
# indexer
# */15 * * * * /data/scripts/sphinx/crawl_db.sh  > /dev/null 2>&1
# 定时生成索引,根据个人情况而定, sudo chmod a+x /data/scripts/sphinx/crawl_db.sh

#/usr/local/coreseek/bin/indexer -c /usr/local/coreseek/etc/crawl_db.conf 测试索引
#/usr/local/coreseek/bin/search -c /usr/local/coreseek/etc/crawl_db.conf  关键词  命令行测试搜索中文
#/usr/local/coreseek/bin/searchd -c /usr/local/coreseek/etc/crawl_db.conf 开启searchd服务,默认监听9312端口

/usr/local/coreseek/bin/indexer -c /usr/local/coreseek/etc/crawl_db.conf --all --rotate