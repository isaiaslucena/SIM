#!/bin/bash

#update the temptable to the new files
curl http://192.168.0.15:8000/codeIgniter312/tools/update_texts_by_keyword

#delete db cache of index
#curl http://192.168.0.15:8000/codeIgniter312/tools/deletepage_dbcache/default/index

#delete db cache of home_keyword
#curl http://192.168.0.15:8000/codeIgniter312/tools/deletepage_dbcache/pages/home_keyword

#udpdate db cache keywords query
#curl http://192.168.0.15:8000/codeIgniter312/tools/update_keyword/

#curl http://192.168.0.15:8000/codeIgniter312/tools/update_keyword_home

#delete output cache of index
#curl http://192.168.0.15:8000/codeIgniter312/tools/deletepage_pagecache/

#update db cache of index page
#curl http://192.168.0.15:8000/codeIgniter312/
