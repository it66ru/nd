<?php

class News extends Controller {

	function __construct()
	{
		parent::Controller();
	}


	function index()
	{
		echo '1';
	}


	# добавление ссылок
	function show()
	{
		$data = array();
		# отображение
		$this->load->view('news/page', $data);
	}


}

?>
