<?php

	class Mparse extends Model {

		function __construct()
		{
			$this->table = 'parse';
			parent::Model();
		}

/*
 * http://www.eip.ru/view/info-object/2089479/?ncity=118
 * http://www.rosrealt.ru/Sankt_Peterburg/kvartira/prodam
 * http://www.estate.spb.ru/apartments/?apa_rooms0=1&apa_rooms1=1&apa_filter=%CF%EE%E8%F1%EA
 * 
 * */

		# получаем данные парса
		function get($id)
		{
			if (!$id) return null;
			
			$SQL = "SELECT p.id, p.url, o.id as object_id, p.get_data
				FROM parse p
				LEFT JOIN nd_objects o ON o.parse_id = p.id
				WHERE p.id = ".$id;
			$query = $this->db->query($SQL);
			
			if ($query->num_rows())
			{
				$this->data = $query->row_array();
				$parse['get_data'] = json_decode($this->data['get_data'], true);
				$parse['get_data']['foto'] = json_decode($this->data['get_data']['foto'], true);
				
				$this->id = $this->data['id'];
				return $this->data;
			}
			else return null;
		}


		# сохранение парса в базе
		function save()
		{
			$sql_data = array();
			
			$sql = "show columns from `{$this->table}`";
			foreach ($this->db->query($sql)->result_array() as $f)
			{
				$k = $f['Field'];
				if (array_key_exists($k, $this->data))
					$sql_data[$k] = $this->data[$k];
			}

			# редактирование существующего
			if (!empty($this->data['id']))
			{
				$this->db->set($sql_data);
				$this->db->where('id', $this->data['id']);
				$this->db->update($this->table);
			}
			# добавление нового
			else
			{
				$this->db->set($sql_data);
				$this->db->insert($this->table); 
				$this->data['id'] = $this->db->insert_id();
			}
		}


		# список парсов за указанную дату
		function log($date)
		{
			$SQL = "SELECT l.cdate, l.parse_id, p.url, l.status as log_status, 
					obj.id, obj.status as obj_status, obj.mdate, obj.ddate,
					(select max(cdate) from parse_log where parse_id = l.parse_id and cdate < l.cdate) as last_cdate
				FROM parse_log l
				INNER JOIN nd_objects obj ON obj.parse_id = l.parse_id
				INNER JOIN parse p ON p.id = l.parse_id
				WHERE l.cdate BETWEEN '$date 00:00:00' AND '$date 23:59:00'
				ORDER BY l.cdate DESC";
			$query = $this->db->query($SQL);
			return $query->result_array();
		}


		# парсинг следующего
		function get_next($id = null)
		{
			$sql = "select p.*, (select o.id from nd_objects o where o.parse_id = p.id) as object_id
			from parse p
			where p.source = 'avito'
				and ".($id ? "p.id = $id" : "status is null")."
			limit 1";
			return $this->data = $this->db->query($sql)->row_array();
		}


		# парсинг объявления c авито
		function avito_parsing()
		{
			if (empty($this->data['id']) || empty($this->data['url'])) return;
			
			$html = file_get_html($this->data['url']);
			
			if ($html)
			{
				$title = $html->find('h1.item_title', 0);
				
				# если зголовок не найден, то это не объявление
				if (!$title) return false;
				
				$this->data['title'] = $title->plaintext; 
				$this->data['title'] = trim($this->data['title']);
				
				# цена
				if ($price = $html->find('span.p_i_price',0))
					$this->data['price'] = preg_replace('|\D|i', '', $price->plaintext);
				else $this->data['price'] = 0;
				
				# адрес
				if ($contact = $html->find('#i_contact', 0))
				{
					$dds = $contact->find('dd');
					$dds = array_reverse($dds);
					$address = array_shift($dds);
					if (!trim($address->plaintext))
						$address = array_shift($dds);
					if ($address)
					{
						$s = $address->find('span', 0);
						if ( $s ) $s->innertext = '';
						$a = $address->find('a', 0);
						if ( $a ) $a->innertext = '';
						$this->data['address'] = str_replace('/', 'к', trim($address->plaintext));
					}
				}
				
				# продавец
				if ($seller = $html->find('#seller', 0))
				{
					$this->data['author'] = $seller->find('strong',0)->plaintext;
					$this->data['author'] = str_replace('"', '', $this->data['author']);
					$this->data['author'] = trim($this->data['author']);
				}
				
				$this->data['info'] = $html->find('#desc_text',0)->plaintext;
				$this->data['info'] = trim($this->data['info']);
				$this->data['info'] = preg_replace('/ {2,}/', ' ', $this->data['info']);
				
				if ($params = $html->find('dd.item-params', 0))
				{
					$this->data['params'] = array();
					
					$this->data['params']['kom'] = $params->find('div', 0)->plaintext;
					$this->data['params']['kom'] = trim($this->data['params']['kom']);
					
					$kk = $params->find('div', 1)->find('a', 0);
					$this->data['params']['kk'] = (int) trim($kk->plaintext);
					$kk->innertext = '';
					
					@list($pl, $et) = explode('на', $params->find('div', 1)->plaintext);
					$this->data['params']['pl'] = (int) trim($pl);
					$this->data['params']['et'] = (int) trim($et);
					
					$this->data['params'] = json_encode($this->data['params']);
				}
				
				# фотки
				$fotos = array();
				
				if ( $foto = $html->find('#photo',0) )
				{
					# перебираем все картинки
					foreach ($foto->find('img') as $img)
					{
						if (preg_match('/640x480/', $img->src))
							$fotos[] = 'http:' . $img->src;
					}
					
					# перебор ссылок
					foreach ($foto->find('a') as $a)
						if ($a->href) $fotos[] = 'http:' . $a->href;
					$fotos = array_unique($fotos);
					$fotos = array_values($fotos);
				}
				$this->data['foto'] = json_encode($fotos);
				
				return true;
			}
			else return false;
		}

	}

?>
