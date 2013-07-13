<?php

	class reklama extends Controller {

		function __construct()
		{
			parent::Controller();
		}

		function index()
		{
			$data = array();

			# отображение
			$this->load->view('pages/reklama', $data);
		}

	}

?>
