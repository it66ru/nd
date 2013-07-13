<?php
class Pic extends Controller {

	function __construct()
	{
		parent::Controller();
	}

	function index()
	{		echo $this -> input -> post('name');
		echo '<pre>'.print_r($this -> input -> post('foto'),true).'</pre>';

		$this -> load -> view('upload');	}




	function tmp($name)
	{
		# задаем настройки для изображения
		$config['image_library']  = 'gd2';
		$config['source_image']   = $this->config->item('path').'/foto/temp/'.$name;
		$config['dynamic_output'] = TRUE;
		$config['width']          = 150;
		$config['height']         = 100;

		# загружаем библиотеку
		$this->load->library('image_lib');
		$this->image_lib->initialize($config);

		# и вызываем функцию
		$this->image_lib->resize();
	}


	
}
?>
