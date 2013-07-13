<?php

class Jurist extends Controller {

	function __construct()
	{
		parent::Controller();
	}

	# информация об объекте
	function index()
	{
		$data = array (
			'head' => array (
				'title'  => 'Бесплатная юридическая консультация',
				'keyw'   => 'недвижимость, Екатеринбург',
				'desc'   => 'Поиск недвижимости в Екатеринбурге',
				'search' => $this->Msearch->get_param(null),
			),
		);
		$this->load->view('pages/lawyer', $data);
	}

}

?>

