<?php
	/* стандартные ограничения нам не подходят. ставим свои */
	set_time_limit(0);
	ini_set('memory_limit', '256M');
	 
	/* проверочка. чтобы этот скрипт по неосторожности никто не вызвал из браузера */
	if (isset($_SERVER['REMOTE_ADDR'])) die('Permission denied.');
	 
	/*  вручную подменяем путь URI на основе параметров командной строки */
	unset($argv[0]); /* первый параметр нам ни к чему, это имя скрипта */
	$_SERVER['QUERY_STRING'] = implode('/', $argv);
	$_SERVER['SERVER_NAME'] = 'pn66.ru';
	
	/* подключаем framework */
	include(dirname(__FILE__).'/index.php');
?>
