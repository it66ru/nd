<?php

	class Mobject extends Model {

		var $id;
		var $data;
		var $foto = array();
		var $building;
		var $parse = false;
		public $form_data;

		function __construct()
		{
			parent::Model();
			$this->load->model('Mdom');
			$this->load->model('Mparse');
			$this->load->model('Mfoto');

			# данные для формы
			$this->form_data = array (
				'rooms' => array ('', '1', '2', '3', '4', '5+'),
				'renovation' => array (   # ремонт
					''    => '',
					'br'  => 'Без ремонта',
					'tip' => 'Типовой',
					'eu'  => 'Евроремонт',
					'diz' => 'Дизайнерский'
				),
				'balcony' => array (   # балкон
					''   => '',
					'no' => 'Нет',
					'b'  => 'Балкон',
					'pl' => 'Полу-лоджия',
					'l'  => 'Лоджия',
					'bb' => 'Два балкона',
					'lb' => 'Лоджия + балкон',
					'll' => 'Две лоджии',
					'b2' => 'Более двух лоджий',
				),
				'bathroom' => array (   # сан. узел
					''  => '',
					's' => 'Совмещённый',
					'r' => 'Раздельный',
					'2' => '2 и более'
				),
				'window' => array (   # куда выходят окна
					''  => '',
					'd' => 'Во двор',
					'u' => 'На улицу',
					'r' => 'На разные стороны'
				),
				'phone' => array (   # телефон
					''  => '',
					'y' => 'Есть',
					'n' => 'Нет'
				),
				'furniture' => array (   # мебель
					''  => '',
					'y' => 'Есть',
					'n' => 'Нет'
				),
				'type' => array (   # условия продажи
					''  => '',
					'p' => 'Чистая продажа',
					'o' => 'Обмен'
				),
				'mortgage' => array (   # ипотека
					''  => '',
					'y' => 'Возможна',
					'n' => 'Невозможна'
				),
				'office' => array (   # под офис
					''  => '',
					'y' => 'Возможно',
					't' => 'Только под офис'
				),
				'replan' => array (   # перепланировка
					''   => '',
					'no' => 'не было',
					'sg' => 'согласована',
					'ns' => 'не согласована'
				),
				'obj_type' => array (   # тип объявления
					'sale' => 'продажа',
					'rent' => 'аренда',
				),
			);
		}

		# возвращает массив данных об объекте
		function load($id)
		{
			$this->id = $id;
			return $this->get();
		}
		function info($id)
		{
			$this->id = $id;
			return $this->get();
		}


		# получение всех данных
		function get()
		{
			$this -> db -> where ( 'id', $this->id );
			$query = $this -> db -> get ( 'nd_objects' );
			if ( $query -> num_rows() )
			{
				$this->data = $query -> row_array();
				
				# преобразаем некоторые параметры
				$this->data['info']  = json_decode($this->data['info'], true);
				$this->data['price'] = number_format($this->data['price'], 0, ',', ' ');
				
				if (empty($this->data['type']))
					$this->data['type'] = 'sale';
				
				# если id дома не указан, попытаемся его найти
				if (!$this->data['house_id'])
					$this->data['house_id'] = $this->Mdom->search($this->data['ul'], $this->data['d']);
				
				# получаем связанную информацию
				$this->foto     = $this->get_foto();
				$this->building = $this->Mdom->info($this->data['house_id']);
				$this->parse    = $this->Mparse->get($this->data['parse_id']);
				
				return $this->data;
			}
			else return FALSE;
		}


		# сохранение объекта в базе
		function save()
		{
			# если добавляется новое объявление
			if ( !$this->id )
			{
				$this->data['cdate']  = date('Y-m-d H:i:s');
				$this->data['status'] = 'new';
				$this->data['ip']     = $this->input->ip_address();
			}

			# приведение данных в нужный вид
			$data = $this->data;
			if ( isset($data['info']) )  $data['info'] = json_encode($data['info']);
			if ( isset($data['price']) ) $data['price'] = str_replace(' ', '', $data['price']);

			# если дома нет, то ставим Null
			if ( empty($data['house_id']) )
			{
				$data['house_id'] = null;
			}

			# добавление нового
			if ( !$this->id )
			{
				$this->db->insert('nd_objects', $data); 
				$this->id = $this->db->insert_id();
				return true;
			}
			# редактирование существующего
			else
			{
				$this->db->where('id', $this->id);
				$this->db->update('nd_objects', $data); 
				return true;
			}
		}


		# заполнение данных из POST
		function complete_data_from_post()
		{
			$fields_data = array (
				'type', 'ul', 'd', 'house_id', 
				'status', 'reason_reject',
				'rooms', 'floor', 'price',
				'space_total', 'space_living', 'space_kitchen',
				'name', 'phone', 'email', 
				'description', 'for_index',
			);

			$fields_info = array (
				'renovation', 'balcony', 'bathroom', 'window', 'furniture', 
				'phone', 'comment', 'type', 'mortgage', 'office', 'replan',
			);

			# перебираем все поля и добавляем к текущему объекту
			foreach ( $fields_data as $f )
			{
				$this->data[$f] = $this->input->post($f);
			}

			# перебираем все дополнительные поля
			$info = $this->input->post('info');
			foreach ( $fields_info as $f ) 
			{
				if ( $f != 'comment' )
				{
					$this->data['info'][$f] = $this->form_data[$f][$info[$f]];
				}
				else
				{
					$this->data['info'][$f] = $info[$f];
				}
			}

			# что-нибудь возвращаем
			return true;
		}


		# добавление фотки
		function add_foto($path, $sort=0)
		{
			if (!$this->id) return;
			
			# копиурем картинку в папку объекта
			$this->Mfoto->adding_to_object($this->id, $path, $sort);
			
			return true;
		}


		# сохранение фотографий
		function save_foro()
		{
		
		}


		# удаление одной фотки по имени (или всех foto = 'all')
		function del_foto ( $foto )
		{
			if ( !$this->id ) return;
			
			if ( $foto != 'all' ) $this->db->where('foto', $foto);
			$this->db->where('object', $this->id);
			$this->db->delete('nd_foto');
			
			return true;
		}


		# сортировка фоток (в том порядке как они переданны в массиве)
		function sort_foto($foto)
		{
			if (!$this->id) return;
			
			foreach ($foto as $sort => $name)
			{
				$this->db->set('sort', $sort);
				$this->db->where('object', $this->id);
				$this->db->where('foto', $name);
				$this->db->update('nd_foto');
			}
			
			return true;
		}


		# фотки объекта
		function foto ( $id )
		{			$this -> db -> where ( 'object', $id );
			$query = $this -> db -> get ( 'nd_foto' );
			if ( $query -> num_rows() )
			{
				return $query -> result_array();
			}
			else return FALSE;
		}


		# фотки объекта
		function get_foto()
		{
			$data = array();
			$this->db->select('foto');
			$this->db->where('object', $this->id);
			$this->db->order_by('sort');
			$query = $this->db->get('nd_foto');
			foreach ( $query->result_array() as $r ) 
				$data[] = $r['foto'];
			return $data;
		}

		function array_data ( $name, $val=0, $key='' )
		{			# кол-во комнат
			$data['kk'] = array (
				0 => '',
				1 => '1',
				2 => '2',
				3 => '3',
				4 => '4',
				5 => '5+'
			);

			# ремонт
			$data['rem'] = array (
				''    => '',
				'br'  => 'Без ремонта',
				'tip' => 'Типовой',
				'eu'  => 'Евроремонт',
				'diz' => 'Дизайнерский'
			);

			# балкон
			$data['bl'] = array (
				''   => '',
				'no' => 'Нет',
				'b'  => 'Балкон',
				'pl' => 'Полу-лоджия',
				'l'  => 'Лоджия',
				'bb' => 'Два балкона',
				'lb' => 'Лоджия + балкон',
				'll' => 'Две лоджии',
				'b2' => 'Более двух лоджий',
			);

			# сан. узел
			$data['su'] = array (
				''  => '',
				's' => 'Совмещённый',
				'r' => 'Раздельный',
				'2' => '2 и более'
			);

			# куда выходят окна
			$data['okna'] = array (
				''  => '',
				'd' => 'Во двор',
				'u' => 'На улицу',
				'r' => 'На разные стороны'
			);

			# телефон
			$data['te'] = array (
				''  => '',
				'y' => 'Есть',
				'n' => 'Нет'
			);

			# мебель
			$data['mb'] = array (
				''  => '',
				'y' => 'Есть',
				'n' => 'Нет'
			);

			# условия продажи
			$data['up'] = array (
				''  => '',
				'p' => 'Чистая продажа',
				'o' => 'Обмен'
			);

			# ипотека
			$data['ipo'] = array (
				''  => '',
				'y' => 'Возможна',
				'n' => 'Невозможна'
			);

			# под офис
			$data['pof'] = array
			(
				''  => '',
				'y' => 'Возможно',
				't' => 'Только под офис'
			);

			# перепланировка
			$data['pp'] = array (
				''   => '',
				'no' => 'не было',
				'sg' => 'согласована',
				'ns' => 'не согласована'
			);

			# выдача всего массива
			if ( $name == 'all' ) return $data;

			# выдача определенной группы
			if ( $val == 0 ) return $data[$name];

			# выдача какого-то значения
			if ( isset($data[$name][$key]) ) return $data[$name][$key];
			else return $key;
		}


		# проверка доступа на редактирование
		function isCanEdit()
		{
			# админ может все
			if ($this->auth->user['type'] == 'admin')
				return true;
			
			# модератор может только пустые или новые
			if ($this->auth->user['type'] == 'moderator')
				if ($this->data['moderator'] == $this->auth->user['id'] || $this->data['status'] == 'new')
					return true;
			
			# все остальные могу только свои
			if ($this->data['user_id'] == $this->auth->user['id'])
				return true;
			
			return false;
		}


		# объекты для главной
		function for_index()
		{
			$data = array();

			# запрос из вьюшки
			$SQL = "(SELECT * FROM nd_objects_index WHERE rooms = 1 and city_id = ".$this->config->item('city_id')." ORDER BY RAND() LIMIT 3)
				UNION ALL
					(SELECT * FROM nd_objects_index WHERE rooms = 2 and city_id = ".$this->config->item('city_id')." ORDER BY RAND() LIMIT 3)
				UNION ALL
					(SELECT * FROM nd_objects_index WHERE rooms = 3 and city_id = ".$this->config->item('city_id')." ORDER BY RAND() LIMIT 3)";
			$query = $this->db->query($SQL);

			# набираем массив
			foreach ( $query->result_array() as $r ) 
			{
				if (!isset($data[$r['rooms']]))
					$data[$r['rooms']] = array();
				
				$data[$r['rooms']][] = $r;
			}

			# возвращаем массив
			return $data;
		}

	}

?>
