<?php 
include 'common_fun/file_func.php';
i_need_func('article',__FILE__,true);
include $my_need_files;

import_request_variables("gpc","rvar_");

$article = new article(true,true);
$article->index_articles();
?>