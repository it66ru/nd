<?php

	class check_foto extends Controller {

		function __construct()
		{
			parent::Controller();

			# только для избранных
			if ( $this -> auth -> user['id'] != 5213 )
			{
				# шифруемся, показываем страницу 404
				show_404('page');
			}
		}

		# список неразобранных фоток
		function index()
		{
			$this -> objects();
		}

		# установка типа для фотографии
		function set_status($foto_id, $type)
		{
			# проверка входных данных
			if ( (int)$foto_id && in_array($type, array('obj', 'dom', 'plan')) )

			# обновляем статус
			$this->db->set('type', $type);
			$this->db->where('id', $foto_id);
			$this->db->update('nd_foto');

			redirect('/check_foto');
		}

	}

?>
