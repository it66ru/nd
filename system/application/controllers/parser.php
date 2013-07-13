<?php

class parser extends Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('parser_model', 'parser');
	}


	# получение списков ссылок со всех сайтов
	public function getLists()
	{
		$urls = array();
		$urls = array_merge($urls, $this->parser->getListRosrealt());
		$urls = array_merge($urls, $this->parser->getListEstate());
		$urls = array_merge($urls, $this->parser->getListEip());
		foreach ($urls as $url) $this->parser->insertUrl($url);
		print_r($urls);
	}


	public function estate()
	{
		$url = (isset($_GET["url"])) ? $_GET["url"] : "";
		
		if ($url == "")
		{
			$adverts = $this->parser_model->getListEstate();
			

		}else{
			print_r($this->parser_model->getAdvertEstate($url));
		}
	}

	public function rosrealt() {
		$url = (isset($_GET["url"])) ? $_GET["url"] : "";

		if ($url == "") {
			$adverts = $this->parser_model->getListRosrealt(1);

		}else{
			print_r($this->parser_model->getAdvertRosrealt($url));
		}
	}

	public function eip() {

		$url = (isset($_GET["url"])) ? $_GET["url"] : "";

		if ($url == "") {

			$adverts = $this->parser_model->getListEip(2);

		} else {
			print_r($this->parser_model->getAdvertEip($url));
		}
	}

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
