<?php

	class Errors extends Controller {

		function __construct()
		{
			parent::Controller();

			# только для избранных
			if ( $this -> auth -> login() == FALSE || $this -> auth -> user['id'] != 5213 )
			{
				# шифруемся, показываем страницу 404
				show_404('page');
			}

			# набор ошибок
			$this->error_list = array (
				'price_none' => array (
					'title' => 'Цена: отсутствует',
					'sql'   => "obj.price = 0"
				),
				'price_high' => array (
					'title' => 'Цена: завышена',
					'sql'   => "obj.price > 10000000"
				),
				'price_low' => array (
					'title' => 'Цена: занижена',
					'sql'   => "obj.price < 1500000"
				),
				'space_none' => array (
					'title' => 'Площадь: отсутствует',
					'sql'   => "obj.space_total = 0"
				),
				'space_sum' => array (
					'title' => 'Площадь: ошибка суммы',
					'sql'   => "obj.space_total <= obj.space_living + obj.space_kitchen"
				),
				'space_dev' => array (
					'title' => 'Площадь: отклонение',
					'sql'   => "obj.space_total < 20 OR obj.space_total > 200"
				),
				'floor_none' => array (
					'title' => 'Этаж: отсутствует',
					'sql'   => "obj.floor = 0"
				),
				'floor_storey' => array (
					'title' => 'Этаж: больше этажности',
					'sql'   => "obj.floor > h.storey"
				),
				'phone_none' => array (
					'title' => 'Телефон: отсутствует',
					'sql'   => "obj.phone LIKE ''"
				),
				'phone_format' => array (
					'title' => 'Телефон: не по формату',
					'sql'   => "obj.phone NOT LIKE  '(___) ___-__-__'"
				),
			);
			
		}

		function index()
		{
			# ошибки объявлений
			$data['obj'] = array();
			foreach ( $this->error_list as $k => $e )
			{
				$SQL = "SELECT count(1) as cnt
					FROM nd_objects obj
					INNER JOIN house h ON h.id = obj.house_id
					WHERE obj.status = 'approved' AND (" . $e['sql'] . ")";
				$query = $this->db->query($SQL);
				$r = $query->row_array();
				$data['obj'][$k] = $r['cnt'];
			}

			# недостающие данные домов
			$SQL = "SELECT
				( SELECT count(1)
					FROM nd_objects obj
					INNER JOIN house h ON h.id = obj.house_id
					WHERE obj.status = 'approved' AND h.storey = 0 ) as storey,
				( SELECT count(1)
					FROM nd_objects obj
					INNER JOIN house h ON h.id = obj.house_id
					WHERE obj.status = 'approved' AND h.year = 0 ) as gp,
				( SELECT count(1)
					FROM nd_objects obj
					INNER JOIN house h ON h.id = obj.house_id
					WHERE obj.status = 'approved' AND h.material LIKE '' ) as material,
				( SELECT count(1)
					FROM nd_objects obj
					INNER JOIN house h ON h.id = obj.house_id
					WHERE obj.status = 'approved' AND h.house_type_id = 0 ) as type
				FROM dual";
			$query = $this->db->query($SQL);
			$data['building'] = $query->row_array();

			# отображение
			$this->load->view('errors/index', $data);
		}

		# список объектов
		function objects($e)
		{
			if ( !isset($this->error_list[$e]) ) 
				return show_404('page');

			$data['title'] = $this->error_list[$e]['title'];

			$SQL = "SELECT obj.id, obj.moderator, CONCAT(s.type, '. ', s.name, ', д. ', h.num) as address, 
				obj.rooms, obj.price, obj.floor, h.storey, obj.space_total, obj.space_living, obj.space_kitchen, obj.phone
				FROM nd_objects obj, house h, street s
				WHERE obj.house_id = h.id and h.street_id = s.id and obj.status = 'approved' and (" . $this->error_list[$e]['sql'] . ")";
			$query = $this->db->query($SQL);
			$data['objects'] = $query->result_array();

			# отображение
			$this->load->view('errors/objects', $data);
		}

		# список домов
		function building($e)
		{
			# отображение
			$this->load->view('admin/object/view', $data);
		}

	}

?>
