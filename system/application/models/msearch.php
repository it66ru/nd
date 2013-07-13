<?php

	class Msearch extends Model {

		function __construct()
		{
			parent::Model();
		}

		# сохраняем параметры поиска
		function set_param()
		{
			$price['ot'] = (int) $this -> input -> post('price_ot');
			$price['do'] = (int) $this -> input -> post('price_do');

			$pl['o_ot'] = (int) $this -> input -> post('pl_o_ot');
			$pl['o_do'] = (int) $this -> input -> post('pl_o_do');
			$pl['j_ot'] = (int) $this -> input -> post('pl_j_ot');
			$pl['j_do'] = (int) $this -> input -> post('pl_j_do');
			$pl['k_ot'] = (int) $this -> input -> post('pl_k_ot');
			$pl['k_do'] = (int) $this -> input -> post('pl_k_do');

			$et['ot'] = (int) $this -> input -> post('et_ot');
			$et['do'] = (int) $this -> input -> post('et_do');
			$et['n1'] = (int) $this -> input -> post('et_n1');
			$et['np'] = (int) $this -> input -> post('et_np');

			# делаем параметры для вставки
			$search_param = array (
				'type'  => $this->input->post('type'),
				'price' => $price['ot'].'-'.$price['do'],
				'p' => implode ( '-', $pl ),
				'e' => implode ( '-', $et ),
				'k' => $this->input->post('k') ? implode ( ',', $this->input->post('k') ) : null,
				'r' => $this->input->post('r') ? implode ( ',', $this->input->post('r') ) : null,
			);

			# идентификатор поиска
			$Sid = md5 ( implode ( '/', $search_param ) );

			# пишем в базу
			$this -> db -> set ( 'sid', $Sid );
			$this -> db -> set ( 'ip_address', $this->session->userdata('ip_address') );
			$this -> db -> set ( 'user_agent', $this->session->userdata('user_agent') );
			$this -> db -> set ( 'last_activity', $this->session->userdata('last_activity') );
			$this -> db -> set ( 'time', time() );
			$this -> db -> set ( $search_param );
			$this -> db -> insert ( 'nd_search' );

			return $Sid;
		}

		# получает параметры поиска
		function get_param ( $sid )
		{
			$this->db->select('type, price, k, p, e, r');
			$this->db->where('sid', $sid);
			$this->db->order_by('id', 'desc');
			$this->db->limit(1);
			$query = $this->db->get('nd_search');
			if ($query->num_rows())
			{				$param = $query->row();
				$p['type'] = $param->type;
				list ( $p['price_ot'], $p['price_do'] ) = explode ( '-', $param -> price );
				list ( $p['pl_o_ot'], $p['pl_o_do'], $p['pl_j_ot'], $p['pl_j_do'], $p['pl_k_ot'], $p['pl_k_do'] ) = explode ( '-', $param -> p );
				list ( $p['et_ot'], $p['et_do'], $p['et_n1'], $p['et_np'] ) = explode ( '-', $param -> e );
				if ( $param -> k ) $p['k'] = explode ( ',', $param -> k ); else $p['k'] = array();
				if ( $param -> r ) $p['r'] = explode ( ',', $param -> r ); else $p['r'] = array();
			}
			else
			{				$p = array(
					'type' => 'sale',
					'price_ot' => '',
					'price_do' => '',
					'pl_o_ot' => '',
					'pl_o_do' => '',
					'pl_j_ot' => '',
					'pl_j_do' => '',
					'pl_k_ot' => '',
					'pl_k_do' => '',
					'et_ot' => '',
					'et_do' => '',
					'et_n1' => '',
					'et_np' => '',
					'k' => array(),
					'r' => array()
				);			}

			return $p;		}

		# создает массив для поиск из параметров
		function where_search ( $p )
		{
			$where = array();

			$where['en']['nd_objects.type'] = isset($p['type']) ? $p['type'] : 'sale';

			if ( $p['price_ot'] ) $where['en']['nd_objects.price >='] = 1000 * $p['price_ot'];
			if ( $p['price_do'] ) $where['en']['nd_objects.price <='] = 1000 * $p['price_do'];

			if ( $p['pl_o_ot'] ) $where['en']['nd_objects.space_total >='] = $p['pl_o_ot'];
			if ( $p['pl_o_do'] ) $where['en']['nd_objects.space_total <='] = $p['pl_o_do'];
			if ( $p['pl_j_ot'] ) $where['en']['nd_objects.space_living >='] = $p['pl_j_ot'];
			if ( $p['pl_j_do'] ) $where['en']['nd_objects.space_living <='] = $p['pl_j_do'];
			if ( $p['pl_k_ot'] ) $where['en']['nd_objects.space_kitchen >='] = $p['pl_k_ot'];
			if ( $p['pl_k_do'] ) $where['en']['nd_objects.space_kitchen <='] = $p['pl_k_do'];

			if ( $p['et_ot'] ) $where['en']['nd_objects.floor >='] = $p['et_ot'];
			if ( $p['et_do'] ) $where['en']['nd_objects.floor <='] = $p['et_do'];
			if ( $p['et_n1'] ) $where['en']['nd_objects.floor !='] = 1;
			if ( $p['et_np'] ) $where['en']['nd_objects.floor !='] = 'house.storey';

			if ( sizeof($p['k']) ) $where['in']['nd_objects.rooms'] = $p['k'];
			if ( sizeof($p['r']) ) $where['in']['house.district_id'] = $p['r'];

			return $where;		}

		# результаты поиска
		function result ( $search_where, $page, $orderby, $direction )
		{
			$this->db->select('nd_objects.id, nd_objects.house_id, nd_objects.rooms');
			$this->db->select('nd_objects.space_total, nd_objects.space_living, nd_objects.space_kitchen');
			$this->db->select('nd_objects.floor, nd_objects.price');
			$this->db->select('DATE_FORMAT(nd_objects.cdate, \'%d.%m.%Y\') as cdate', false);
			$this->db->where('house.id', 'nd_objects.house_id', false);
			$this->db->where('nd_objects.status', 'approved');
			$this->db->where('house.district_id', 'district.id', false);
			$this->db->where('district.city_id', $this->config->item('city_id'));
			$this->db->order_by($orderby, $direction);

			if ( array_key_exists ( 'en', $search_where ) )
				$this -> db -> where ( $search_where['en'], NULL, TRUE );

			if ( array_key_exists ( 'in', $search_where ) )
				foreach ( $search_where['in'] as $k => $v ) $this -> db -> where_in ( $k, $v );

			$this->db->limit(20, $page);
			$query = $this->db->get('nd_objects, house, district');
# echo $this->db->last_query();
			# результаты поиска
			$result = array();

			# перебираем все резуьтаты
			foreach ( $query -> result_array() as $r )
			{				$r['dom'] = $this->Mdom->info($r['house_id']);
				$result[] = $r;			}

			return $result;
		}

		# число строк без учета LIMIT
		function count ( $search_where )
		{
			$this->db->select('count(1)');
			$this->db->where('house.id', 'nd_objects.house_id', false);
			$this->db->where('nd_objects.status', 'approved');
			$this->db->where('house.district_id', 'district.id', false);
			$this->db->where('district.city_id', $this->config->item('city_id'));

			if ( array_key_exists ( 'en', $search_where ) )
				$this -> db -> where ( $search_where['en'], NULL, TRUE );

			if ( array_key_exists ( 'in', $search_where ) )
				foreach ( $search_where['in'] as $k => $v ) $this -> db -> where_in ( $k, $v );

			$query = $this->db->get('nd_objects, house, district');
			$r = $query->row_array();
			return $r['count(1)'];
		}

		# для зарезервированных поисков получаем заголовок и текста
		function title_search ( $sid )
		{
			$this->db->select('title, keywords, description');
			$this->db->where('sid', $sid);
			$this->db->limit(1);
			$query = $this->db->get('nd_search');
			
			# если такой поиск найден
			if ( $query->num_rows() )
				return $query->row_array();
			# если не найден, возвращаем дефолтные значения
			else
				return array (
					'title' => 'Недвижимость в Екатеринбурге. PN66 - Поиск недвижимости в Екатеринбурге',
					'keywords' => 'PN66.ru - Поиск недвижимости в Екатеринбурге',
					'description' => 'PN66.ru - Поиск недвижимости в Екатеринбурге',
				);		}

	}

?>
