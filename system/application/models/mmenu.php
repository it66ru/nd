<?php

	class Mmenu extends Model {

		function __construct()
		{
		}

		function edit_page ( $type, $action, $id )
		{			if ( $this -> input -> post('name') == '' ) $er[] = 'Не указано название страницы';
			if ( $this -> input -> post('url') == ''  ) $er[] = 'Не указан URL страницы';

			if ( isset($er) ) return array( 'er' => $er );

			$this -> db -> set ( 'name',	$this -> input -> post('name') );
			$this -> db -> set ( 'url',	$this -> input -> post('url') );
			$this -> db -> set ( 'text',	$this -> input -> post('text') );
			$this -> db -> set ( 'title',	$this -> input -> post('title') );
			$this -> db -> set ( 'keyw',	$this -> input -> post('keyw') );
			$this -> db -> set ( 'desc',	$this -> input -> post('desc') );

			if ( $action == 'add' ) {				$this -> db -> set ( 'type', $type );
				$this -> db -> set ( 'top', $id );
				$this -> db -> insert ( 'menu' );
				return array( 'ok' => 'Страница успешно добавлена' );			}
			if ( $action == 'edit' ) {
				$this -> db -> where ( 'id', $id );
				$this -> db -> update ( 'menu' );
				return array( 'ok' => 'Страница успешно заменена' );
			}
		}

		function edit_sort ( $type, $id )
		{			# получаем текущий уровень сортировки
			$this -> db -> select ( 'id, top, type, sort' );
			$this -> db -> where ( 'id', $id );
			$query = $this -> db -> get ( 'menu' );
			if ( ! $query -> num_rows() ) return;
			$m = $query -> row();

			#получаем то значение на которое будет заменен текущий
			$this -> db -> select ( 'id, sort' );
			$this -> db -> where ( 'type', $m->type );
			$this -> db -> where ( 'top', $m->top );
			$this -> db -> where ( 'del', 0 );
			if ( $type == 'up' )		$this -> db -> where ( 'sort <', $m->sort );
			if ( $type == 'down' )	$this -> db -> where ( 'sort >', $m->sort );
			if ( $type == 'up' )		$this -> db -> order_by ( 'sort', 'desc' );
			if ( $type == 'down' )	$this -> db -> order_by ( 'sort', 'asc' );
			$query = $this -> db -> get ( 'menu', 1 );
			if ( ! $query -> num_rows() ) return;
			$n = $query -> row();

			# делаем 2 замены
			$this -> db -> set ( 'sort', $m->sort );
			$this -> db -> where ( 'id', $n->id );
			$this -> db -> update ( 'menu' );

			$this -> db -> set ( 'sort', $n->sort );
			$this -> db -> where ( 'id', $m->id );
			$this -> db -> update ( 'menu' );

		}

		function del ( $id )
		{			$this -> db -> set ( 'del', 1 );
			$this -> db -> where ( 'id', $id );
			$this -> db -> update ( 'menu' );
		}




	}

?>