<?php 
     define('_SAPE_USER', '5d3da9644e225eecf8e32a042216465e');
     require_once($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php'); 
     $sape_articles = new SAPE_articles();
     echo $sape_articles->process_request();
?>
