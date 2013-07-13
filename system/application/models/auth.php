<?php

class Auth extends Model {

	function __construct()
	{
		parent::Model();
	}

	# проверка залогинен ли пользователь
	function login ()
	{
		$WhereData = array (
			'email'		=> $this -> session -> userdata ( 'email' ),
			'password'	=> $this -> session -> userdata ( 'password' ),
		);

		if ( $WhereData['email'] && $WhereData['password'] )
		{
			# шифруем пароль
			$WhereData['password'] = md5 ( $WhereData['email'].'-'.$WhereData['password'] );
			# делаем запрос в базу
			$query = $this -> db -> get_where ( 'nd_user', $WhereData );
			# если такая комбанация нашлась
			if ( $query -> num_rows() )
			{				# сохраняем все данные пользователя
				$this -> user = $query -> row_array();
				return TRUE;			}
			else return FALSE;
		}
		else return FALSE;
	}


	# возвращает имя по ID
	function name ( $id )
	{
		$this -> db -> select ( 'username' );
		$this -> db -> where ( 'id', $id );
		$query = $this -> db -> get ( 'user' );		$r = $query -> row_array();
		return $r['username'];	}


}

?>