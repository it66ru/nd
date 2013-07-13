<?php

class import extends Controller {

	function __construct()
	{
		parent::Controller();
	}

	function index()
	{
		$this->info();
	}

	# описание
	function info()
	{
		$data = array();
		# отображение
		$this->load->view('pages/import/info', $data);
	}

}

?>
