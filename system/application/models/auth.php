<?php

class Auth extends Model {

	function __construct()
	{
		parent::Model();
	}

	# �������� ��������� �� ������������
	function login ()
	{
		$WhereData = array (
			'email'		=> $this -> session -> userdata ( 'email' ),
			'password'	=> $this -> session -> userdata ( 'password' ),
		);

		if ( $WhereData['email'] && $WhereData['password'] )
		{
			# ������� ������
			$WhereData['password'] = md5 ( $WhereData['email'].'-'.$WhereData['password'] );
			# ������ ������ � ����
			$query = $this -> db -> get_where ( 'nd_user', $WhereData );
			# ���� ����� ���������� �������
			if ( $query -> num_rows() )
			{				# ��������� ��� ������ ������������
				$this -> user = $query -> row_array();
				return TRUE;			}
			else return FALSE;
		}
		else return FALSE;
	}


	# ���������� ��� �� ID
	function name ( $id )
	{
		$this -> db -> select ( 'username' );
		$this -> db -> where ( 'id', $id );
		$query = $this -> db -> get ( 'user' );		$r = $query -> row_array();
		return $r['username'];	}


}

?>