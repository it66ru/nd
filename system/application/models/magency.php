<?php

	class Magency extends Model {

		function __construct()
		{
			parent::Model();
		}

		# возвращает массив данных об агентстве
		function info ( $id )
		{
			$this -> db -> where ( 'id', $id );
			$query = $this -> db -> get ( 'agency' );

			if ( $query -> num_rows() )
			{
			else return FALSE;
		}

		# возвращает массив объектов данного агентства
		function objects ( $id, $sort_field='cena', $sort_type='asc' )
		{
		}

	}

?>