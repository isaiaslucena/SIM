#!/bin/bash

tempfolder="/home/isaiasneto/app/codeIgniter312/assets/temp/"

#remove files from temp folder
rm -rf $tempfolder"*.mp3"

#truncate table
curl http://192.168.0.15:8000/codeIgniter312/tools/truncate_table/temp_texts_keyword_found

#search texts of today and insert in temp table
curl http://192.168.0.15:8000/codeIgniter312/tools/get_texts_by_keyword

#delete all db cache
#curl http://192.168.0.15:8000/codeIgniter312/tools/deleteall_dbcache

#delete db cache of index
curl http://192.168.0.15:8000/codeIgniter312/tools/deletepage_dbcache/default/index

#delete db cache of home_keyword
curl http://192.168.0.15:8000/codeIgniter312/tools/deletepage_dbcache/pages/home_keyword

#udpdate db cache keywords query
#curl http://192.168.0.15:8000/codeIgniter312/tools/update_keyword/

#delete output cache of index
curl http://192.168.0.15:8000/codeIgniter312/tools/deletepage_pagecache/

#update db cache of index page
#curl http://192.168.0.15:8000/codeIgniter312/
