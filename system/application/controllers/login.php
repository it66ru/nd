<?php

class Login extends Controller
{
	function __construct()
	{
		parent::Controller();

	}

	function index()
	{
		if ( $this -> input -> post('email') && $this -> input -> post('password') )
		{
			$SessionData = array(
				'email' =>		$this -> input -> post('email'),
				'password' =>	md5 ( $this -> input -> post('password') )
			);			$this -> session -> set_userdata ( $SessionData );
		}

		# если удалось авторизоваться
		if ( $this -> auth -> login() )
		{
			redirect ( 'my', 'refresh' );
		}
		# если не удалось, то остаемся здесь
		else
		{			# отображение
			$this -> load -> view ( 'login/index' );
		}

	}

	function change ( $password = '' )
	{
		# задаем пустой массив, что бы небыло ошибки есть ничего не передадим
		$data = array();

		# форма для ввода паролей
		if ( $password != '' )
		{			# ищем пользователя с таким паролем
			$this -> db -> where ( 'password', $password );
			$query = $this -> db -> get ( 'nd_user' );
			# если строка найдена
			if ( $query -> num_rows() )
			{
				if ( $this -> input -> post('pass1') )
				{					if ( $this -> input -> post('pass1') != $this -> input -> post('pass2') )
					{
						$data['error'] = 'Пароли не совпадают';
					}
					else
					{						$r = $query -> row_array();

						$password = $this -> input -> post('pass1');
						$new_pass = md5 ( $r['email'] . '-' . md5( $password ) );

						$this -> db -> set ( 'password', $new_pass );
						$this -> db -> where ( 'id', $r['id'] );
						$this -> db -> update ( 'nd_user' );

						$data['success'] = 'Пароль успешно изменен';
					}
				}			}
			else $data['not_access'] = 'Не верный код';

			# отображение
			$this -> load -> view ( 'login/change2', $data );
		}
		# форма для воода мыла
		else
		{
			if ( $this -> input -> post('email') )
			{
				# ищем пользователя
				$this -> db -> where ( 'email', $this -> input -> post('email') );
				$query = $this -> db -> get ( 'nd_user' );
				# если пользователь найден
				if ( $query -> num_rows() )
				{					$r = $query -> row_array();

					# загружаем библиотеку
					$this -> load -> library ('email');
					# передаем все параметры
					$this -> email -> from ( 'info@pn66.ru', 'PN66.ru' );
					$this -> email -> to ( $r['email'] );
					$this -> email -> subject ( 'Восстановление пароля' );
					$this -> email -> message ( 'Для восстановления пароля, Вам необходимо пройти по следующей ссылке'."\n".'http://pn66.ru/login/change/'.$r['password'] );
					# отправляем
					$this -> email -> send();

					$data['success'] = 'На Ваш E-mail отправлено письмо с инструкцией для восстановления пароля';				}
				else $data['error'] = 'E-mail не найден';
			}
			# отображение
			$this -> load -> view ( 'login/change', $data );
		}
	}

	function out()
	{
		# уничтожаем всю сессию целиком
		$this -> session -> sess_destroy();
		# редирект на страницу авторизации		redirect('login', 'refresh');
	}

}
?>