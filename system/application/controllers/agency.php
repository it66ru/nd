<?php

	class Agency extends Controller {

		function __construct()
		{
			parent::Controller();

			$this -> sections = array (
				'info'     => 'Информация',
				'objects'  => 'Объекты на продажу',
				'comments' => 'Отзывы'
			);
		}

		function _remap ()
		{

			if ( $this->uri->segment(2) == 'info' )
			{
				$this -> info ( $this->uri->segment(3) );
			}
			elseif ( $this->uri->segment(2) == 'objects' )
			{
				$this -> objects ( $this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5) );
			}
			elseif ( $this->uri->segment(2) == 'comments' )
			{
				$this -> comments ( $this->uri->segment(3) );
			}
			elseif ( substr_count ( $this -> uri -> segment(2), '.html' )  )
			{
				$id = $this -> uri -> segment ( 2 );
				$id = str_replace ( '.html', '', $id );
				$this -> info ( $id );
			}
			else
			{
				# буква
				$letter = $this -> uri -> segment(2) ? $this -> uri -> segment(2) : 'a';
				$this -> index ( $letter );
			}
		}

		# список агентств по букве
		function index ( $letter )
		{
			# получаем параметры поиска
			$Sid = $this -> session -> userdata ( 'session_id' );
			$param = $this -> Msearch -> get_param ( $Sid );

			# заголовок
			$Dhead['title'] = 'PN66.ru - Поиск недвижимости в Екатеринбурге';
			$Dhead['keyw'] = 'недвижимость, Екатеринбург';
			$Dhead['desc'] = 'Поиск недвижимости в Екатеринбурге';
			$Dhead['search'] = $param;

			# получаем список агентств
			$this -> db -> where ( 'letter', $letter );
			$this -> db -> order_by ( 'name' );
			$query = $this -> db -> get ( 'agency' );
			$data['agency'] = $query -> result_array();

			# список букв
			$data['letters'] = array (
				'0' => '0-9',
				'a' => 'А',
				'b' => 'Б',
				'v' => 'В',
				'g' => 'Г',
				'd' => 'Д',
				'e' => 'Е',
				'j' => 'Ж',
				'z' => 'З',
				'i' => 'И',
				'k' => 'К',
				'l' => 'Л',
				'm' => 'М',
				'n' => 'Н',
				'o' => 'О',
				'p' => 'П',
				'r' => 'Р',
				's' => 'С',
				't' => 'Т',
				'y' => 'У',
				'f' => 'Ф',
				'c' => 'Ц',
				'4' => 'Ч',
				'w' => 'Ш',
				'x' => 'Э',
				'u' => 'Ю',
				'q' => 'Я',
			);
			$data['this_letter'] = $letter;

			# отображение
			$this -> load -> view ( 'head' , $Dhead );
			$this -> load -> view ( 'agency/list' , $data );
			$this -> load -> view ( 'foot' );
		}

		# информация об агентстве
		function info ( $id = 0 )
		{
			# получаем данные агентства
			$this -> db -> where ( 'id', $id );
			$query = $this -> db -> get ( 'agency' );
			
			# если такое агентство не нашлось - 404
			if ( !$query->num_rows() ) return show_404('page');
			
			$data['agency'] = $query -> row_array();

			# предложения данного агенства
#			$this -> db -> select ( 'nd_flat_sale.id, nd_flat_sale.id_dom, nd_flat_sale.ob, nd_flat_sale.kk, nd_flat_sale.pl_o, nd_flat_sale.pl_j, nd_flat_sale.pl_k, nd_flat_sale.et, nd_flat_sale.cena, nd_flat_sale.date_up' );
#			$this -> db -> select ( 'nd_dom.adr_ul, nd_dom.adr_d, nd_dom.storey' );
#			$this -> db -> where ( 'nd_flat_sale.ag', $id );
#			$this -> db -> where ( 'nd_dom.id', 'nd_flat_sale.id_dom', false );
#			$query = $this -> db -> get ( 'nd_flat_sale, nd_dom' );
#			$data['flat_sale'] = $query -> result_array();
			$data['flat_sale'] = array();

			# заголовок
			$Dhead['title'] = $data['agency']['name'].' - агентство недвижимости, г. Екатеринбург';
			$Dhead['keyw'] = 'недвижимость, Екатеринбург';
			$Dhead['desc'] = 'Поиск недвижимости в Екатеринбурге';

			# получаем параметры поиска (пустой, просто что бы отображалось)
			$Dhead['search'] = $this -> Msearch -> get_param ( '' );

			# вкладки
			$data['sections'] = $this -> sections;

			# отображение
			$this -> load -> view ( 'head' , $Dhead );
			$this -> load -> view ( 'agency/info' , $data );
			$this -> load -> view ( 'foot' );
		}

		# объекты на продажу
		function objects ( $id, $sort_field='cena', $sort_type='asc' )
		{
			# получаем данные агентства
			$data['agency'] = $this -> Magency -> info ( $id );

			if ( $sort_field == 'map' )
			{				$view = 'objects_map';
			}
			else
			{				if ( $sort_field == '' ) $sort_field = 'cena';
				if ( $sort_type  == '' ) $sort_type  = 'asc';

				# данные о поиске
				$data['sort']['field'] = $sort_field;
				$data['sort']['type']  = $sort_type;

				# предложения данного агенства
#				$data['flat_sale'] = $this -> Magency -> objects ( $id, $sort_field, $sort_type );
				$data['flat_sale'] = array();

				$view = 'objects_tab';
			}

			# заголовок
			$Dhead['title'] = $data['agency']['name'].' - агентство недвижимости, г. Екатеринбург';
			$Dhead['keyw'] = 'недвижимость, Екатеринбург';
			$Dhead['desc'] = 'Поиск недвижимости в Екатеринбурге';

			# получаем параметры поиска (пустой, просто что бы отображалось)
			$Dhead['search'] = $this -> Msearch -> get_param ( '' );

			# вкладки
			$data['sections'] = $this -> sections;

			# отображение
			$this -> load -> view ( 'head' , $Dhead );
			$this -> load -> view ( 'agency/'.$view , $data );
			$this -> load -> view ( 'foot' );
		}

		# отзывы
		function comments ( $id )
		{
			# новый коммент
			if ( $this -> input -> post('comment') && $this -> auth -> login() )
			{
				$this -> db -> set ( 'agency', $id );
				$this -> db -> set ( 'user', $this -> auth -> user['id'] );
				$this -> db -> set ( 'comment', $this -> input -> post('comment') );
				$this -> db -> set ( 'date', time() );
				$this -> db -> insert ( 'agency_comment' );
				$data['add_success'] = TRUE;			}

			# получаем данные агентства
			$data['agency'] = $this -> Magency -> info ( $id );

			# заголовок
			$Dhead['title'] = $data['agency']['name'].' - агентство недвижимости, г. Екатеринбург';
			$Dhead['keyw'] = 'недвижимость, Екатеринбург';
			$Dhead['desc'] = 'Поиск недвижимости в Екатеринбурге';

			# получаем параметры поиска (пустой, просто что бы отображалось)
			$Dhead['search'] = $this -> Msearch -> get_param ( '' );

			# вкладки
			$data['sections'] = $this -> sections;

			# отображение
			$this -> load -> view ( 'head' , $Dhead );
			$this -> load -> view ( 'agency/comments' , $data );
			$this -> load -> view ( 'foot' );
		}

	}

?>
