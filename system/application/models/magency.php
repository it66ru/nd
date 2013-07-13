<?php

	class Magency extends Model {

		function __construct()
		{
			parent::Model();
		}

		# возвращает массив данных об агентстве
		function info ( $id )
		{			# получаем текущий уровень сортировки
			$this -> db -> where ( 'id', $id );
			$query = $this -> db -> get ( 'agency' );

			if ( $query -> num_rows() )
			{				return $query -> row_array();			}
			else return FALSE;
		}

		# возвращает массив объектов данного агентства
		function objects ( $id, $sort_field='cena', $sort_type='asc' )
		{
		}

	}

?>
