<?php

	class Mhouse extends Model {

		public $id;
		public $data;
		public $fotos = array();
		public $plans = array();

		public $form_data;

		function __construct()
		{
			parent::Model();

			# данные для формы
			$this->form_data = array (
				# материал дома
				'material' => array (
					'',
					'Блок',
	 	 	 		'Газозолобетон',
	 	 	 		'Дерево',
	 	 	 		'Кирпич',
	 	 	 		'Монолит',
	 	 	 		'Монолит-кирпич',
	 	 	 		'Несъемная опалубка',
	 	 	 		'Панель',
	 	 	 		'Шлакоблок',
				),
				# тип дома
				'type' => array (
					0 => '',
	 	 	 		2 => 'Полнометражка',
	 	 	 		3 => 'Малосемейка',
	 	 	 		4 => 'Хрущевка',
	 	 	 		5 => 'Брежневка',
	 	 	 		6 => 'Пентагон',
	 	 	 		7 => 'Улучш. планировка',
	 	 	 		8 => 'Спец. планировка',
				),
			);
		}

		# возвращает массив данных об объекте
		function info($id)
		{
			$this->id = (int)$id;
			return $this->get();
		}


		# получение всех данных
		function get()
		{
			$sql = "SELECT h.id, h.num,
						concat(s.type, '. ', s.name, ', д. ', h.num) as address,
						h.street_id, s.type as street_type, s.name as street_name, 
						h.district_id, d.name as district_name,
						h.ya_address, h.yaLat, h.yaLng,
						h.material, h.year, h.storey,
						h.house_type_id, ht.name as house_type_name,
						h.name, h.description, h.source
					FROM
						house h, street s, house_type ht, district d
					WHERE
						h.id = ".$this->id."
						and h.street_id = s.id
						and h.house_type_id = ht.id
						and h.district_id = d.id";
			$query = $this->db->query($sql);
			# дом найден
			if ($query->num_rows())
			{
				$this->data = $query->row_array();
			}
			# дом не найден
			else 
			{
				$fields = array (
					'id', 'num', 'address', 'street_id', 'street_type', 'street_name', 'district_id', 'district_name', 
					'ya_address', 'yaLat', 'yaLng', 'material', 'year', 'storey', 'house_type_id', 'house_type_name',
					'name', 'description', 'source',
				);
				foreach ($fields as $f)
					$this->data[$f] = null;
			}
			return $this->data;
		}


		# сохранение объекта в базе
		function save()
		{
			# добавление нового
			if ( !$this->id ) $this->add();
			# редактирование существующего
			else $this->edit();
		}

		# добавление нового дома
		private function add()
		{
			$this->db->insert('house', $this->data); 
			$this->id = $this->db->insert_id();
			return true;
		}

		# редактирование существующего
		private function edit()
		{
			$this->db->where('id', $this->id);
			$this->db->update('house', $this->data); 
			return true;
		}


		# заполнение данных из POST
		function complete_data_from_post()
		{
			$fields_data = array (
				'ul', 'd', 'house_id', 
				'status', 'reason_reject',
				'rooms', 'floor', 'price',
				'space_total', 'space_living', 'space_kitchen',
				'name', 'phone', 'email', 
				'description', 'index',
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


		# поиск дома по адресу
		function get_dom_id($address, $title = null, $city_name = 'Екатеринбург')
		{
			if (!$address) $address = preg_replace('/Продам(.*)квартиру/', '', $title);
		
			$key = 'AKgVxU4BAAAAW0EGcQIAcVX3PcvNJLoVFqMkRImZfQKEXfAAAAAAAAAAAACvXzKRousBqhlBTh8gc-ZKP1ckJQ==';
			$address = $city_name.' '.$address;
			$url = 'http://geocode-maps.yandex.ru/1.x/?geocode='.$address.'&key='.$key;
			$xml = simplexml_load_file($url);

			foreach ($xml->GeoObjectCollection->featureMember as $m)
			{
				$data = $m->GeoObject->metaDataProperty->GeocoderMetaData;
				
				# будем смотреть только тех, у когото точно "дом"
				if ($data->kind == 'house')
				{
					$locality = $data->AddressDetails->Country->AdministrativeArea->Locality;
					
					# только указанный город
					if ($locality->LocalityName == $city_name)
					{
						$t = $locality->DependentLocality->Thoroughfare ? $locality->DependentLocality->Thoroughfare : $locality->Thoroughfare;
						
						$ul = $t->ThoroughfareName;
						$d  = $t->Premise->PremiseNumber ? $t->Premise->PremiseNumber : $t->Premise->PremiseName;
						
						$SQL = <<<SQL
select h.id
from house h, street s
where h.streeT_id = s.id
	and instr('$ul', s.name) > 0
	and h.num like '$d'
SQL;
						
						$query = $this->db->query($SQL);
					
						# если такой дом нашелся
						if ( $query->num_rows() )
						{
							$r = $query->row_array();
							return (int) $r['id'];
						}
					}
				}
			}
			return null;
		}


		# получение правильного адреса дома
		public function getYandexAddress($address)
		{
			$key = 'AKgVxU4BAAAAW0EGcQIAcVX3PcvNJLoVFqMkRImZfQKEXfAAAAAAAAAAAACvXzKRousBqhlBTh8gc-ZKP1ckJQ==';
			$url = 'http://geocode-maps.yandex.ru/1.x/?geocode='.$address.'&key='.$key;
			$xml = simplexml_load_file($url);
			$GeocoderMetaData = $xml->GeoObjectCollection->featureMember[0]->GeoObject->metaDataProperty->GeocoderMetaData;
			$text = $GeocoderMetaData->kind == 'house' ? (string) $GeocoderMetaData->text : null;
			return $text;
		}


# ======================================================================
# Фотографии дома 
# ----------------------------------------------------------------------

		# добавление фотки
		function add_foto($path, $sort)
		{
			if ( !$this->id ) return;
			
			# копиурем картинку в папку объекта
			$this->Mfoto->adding_to_object($this->id, $path, $sort);
			
			return true;
		}


		# удаление одной фотки по имени (или всех foto = 'all')
		function del_foto($foto)
		{
			if ( !$this->id ) return;
			
			if ( $foto != 'all' ) $this->db->where('foto', $foto);
			$this->db->where('object', $this->id);
			$this->db->delete('nd_foto');
			
			return true;
		}


		# сортировка фоток (в том порядке как они переданны в массиве)
		function sort_foto ( $foto )
		{
			if ( !$this->id ) return;
			
			foreach ( $foto as $sort => $name )
			{
				$this->db->set('sort', $sort);
				$this->db->where('object', $this->id);
				$this->db->where('type', 'obj');
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


# ======================================================================
# Справочники
# ----------------------------------------------------------------------

		# список улиц
		function streets()
		{
			$data = array('');
			
			$sql = "select s.id, concat(s.type, '. ', s.name) as name
					from street s
					where s.city_id = ".$this->config->item('city_id')."
					order by s.name";
			foreach ($this->db->query($sql)->result_array() as $r)
				$data[$r['id']] = $r['name'];
			
			return $data;
		}


		# список районов
		function districts()
		{
			$data = array('');
			
			$sql = "select d.id, d.name
					from district d
					where d.city_id = ".$this->config->item('city_id')."
					order by d.name";
			foreach ($this->db->query($sql)->result_array() as $r)
				$data[$r['id']] = $r['name'];
			
			return $data;
		}


	}

?>
