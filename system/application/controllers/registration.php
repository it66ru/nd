<?php

class Registration extends Controller
{

	function index()
	{
		return $this->user();
	}


	# регистрация частного лица
	function user()
	{
		$this->load->library('validation');

		# настройка правил
		$rules['email']    = "required|valid_email|callback_email_check";
		$rules['password'] = "required|matches[passconf]";
		$rules['username'] = "required";
		$this->validation->set_rules($rules);

		# сообщения об ошибках
		$this->validation->set_message('required', 'Вы не указали %s');
		$this->validation->set_message('matches', 'Пароли не совпадают');
		$this->validation->set_message('valid_email', 'E-mail указан не верно');

		# названия полей
		$fields['username']  = "Имя";
		$fields['email']     = "E-mail";
		$fields['password']  = "Пароль";
		$fields['passconf']  = "Подтверждение пароля";
		$this->validation->set_fields($fields);

		# успешная регистрация
		if ( $this -> validation -> run() )
		{
			# шифруем пароль
			$email    = $this->input->post('email');
			$password = $this->input->post('password');
			$password = md5($email.'-'.md5($password));

			# вставка в базу
			$InsertData = array (
				'email'     => $email,
				'password'  => $password,
				'username'  => $this->input->post('username'),
				'date'      => date('Y-m-d H:i:s'),
				'ip'        => $this->input->ip_address(),
			);
			$this->db->insert('nd_user', $InsertData);

			# отображение
			$this->load->view('registration/success');

		}
		# не успешная
		else
		{
			$this->load->view('registration/form_user');
		}

	}


	# регистрация агентства
	function agency()
	{
		$this->load->library('validation');

		# настройка правил
		$rules['username'] = "required";
		$rules['contact']  = "required";
		$rules['phone']    = "required";
		$rules['email']    = "required|valid_email|callback_email_check";
		$rules['password'] = "required|matches[passconf]";
		$this->validation->set_rules($rules);

		# сообщения об ошибках
		$this->validation->set_message('required', 'Вы не указали %s');
		$this->validation->set_message('matches', 'Пароли не совпадают');
		$this->validation->set_message('valid_email', 'E-mail указан не верно');

		# названия полей
		$fields['username']		= "Название организации";
		$fields['contact']		= "Контактное лицо";
		$fields['phone']		= "Телефон";
		$fields['address']		= "Адрес";
		$fields['site']			= "Сайт";
		$fields['email']		= "E-mail";
		$fields['password']		= "Пароль";
		$fields['passconf']		= "Подтверждение пароля";
		$this->validation->set_fields($fields);

		# успешная регистрация
		if ( $this -> validation -> run() )
		{
			# шифруем пароль
			$email    = $this->input->post('email');
			$password = $this->input->post('password');
			$password = md5($email.'-'.md5($password));

			# вставка в базу
			$InsertData = array (
				'type'      => 'agency',
				'email'     => $email,
				'password'  => $password,
				'username'  => $this->input->post('username'),
				'contact'   => $this->input->post('contact'),
				'phone'     => $this->input->post('phone'),
				'address'   => $this->input->post('address'),
				'site'      => $this->input->post('site'),
				'date'      => date('Y-m-d H:i:s'),
				'ip'        => $this->input->ip_address(),
			);
			$this->db->insert('nd_user', $InsertData);

			# отображение
			$this->load->view('registration/success');
		}
		# не успешная
		else
		{
			$this->load->view('registration/form_agency');
		}
	}


	# проверка сущестования email
	function email_check ( $email )
	{
		$this->db->where('email', $email);
		$query = $this->db->get('nd_user');
		
		if ($query->num_rows())
		{
			$this->validation->set_message('email_check', 'Пользователь с таким E-mail уже существует');
			return FALSE;
		}
		else return TRUE;
	}

}
?>
