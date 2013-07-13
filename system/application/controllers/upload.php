<?php
class Upload extends Controller {

	function __construct()
	{
		parent::Controller();
		$this->load->helper(array('form', 'url'));
	}

	function index()
	{		echo $this->input->post('name');
		echo '<pre>'.print_r($this->input->post('foto'),true).'</pre>';

		$this -> load -> view('upload');	}

	function do_upload()
	{
		$config['upload_path']    = './foto/temp/';
		$config['allowed_types']  = 'jpg|png';
		$config['max_size']       = '2048';
		$config['max_width']      = '3000';
		$config['max_height']     = '2000';
		$config['remove_spaces']  = TRUE;
		$config['encrypt_name']   = TRUE;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload())
		{
			echo 'error/=/' . $this -> upload -> display_errors() . '/=/' . time();
		}
		else
		{
			$data = $this -> upload -> data();
			echo 'ok/=/' . $data['file_name'] . '/=/' . time();
		}
	}

}
?>
