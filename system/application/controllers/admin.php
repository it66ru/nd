<?php

	class Admin extends Controller {

		function __construct()
		{
			parent::Controller();

			# только для избранных
			if ( $this -> auth -> login() == FALSE || $this -> auth -> user['id'] != 5213 )
			{
				# шифруемся, показываем страницу 404
				show_404('page');
			}
		}

		function index()
		{
			$this->objects('new');
		}

		# список объектов
		function objects($type = 'new')
		{
			$data['type'] = array (
				'new'      => 'Новые',
				'approved' => 'Одобренные',
				'rejected' => 'Отклоненные',
				'removed'  => 'Удаленные',
			);
			$data['this_type'] = $type;
			$data['objects']   = array();

			# получаем список
			$this->db->where('status', $type );
			$this->db->where('user_id != 0 and user_id is not null');
			$this->db->order_by('cdate', 'asc');
			$query = $this->db->get('nd_objects');
			foreach ($query->result_array() as $r)
			{
				# получаем число фоток
				$this -> db -> where ( 'object', $r['id'] );
				$query = $this -> db -> get ( 'nd_foto' );
				$r['foto'] = $query -> num_rows();

				# в общий массив
				$data['objects'][] = $r;
			}

			# отображение
			$this -> load -> view ( 'admin/object/list', $data );
		}


		# информация по конкретному объекту
		function object ( $id )
		{
			# получаем данные объекта
			$this->Mobject->id = $id;
			$this->Mobject->get();

			# редактирование объявления
			if ( $this->input->post('edit') )
			{
				# новые данные объекта
				$this->Mobject->complete_data_from_post();
				$this->Mobject->data['mdate'] = date('Y-m-d H:i:s');
				$this->Mobject->data['moderator'] = $this->auth->user['id'];
				# сохраняем объект
				$this->Mobject->save();

				# удаляем фотки
				if ( $f = $this->input->post('foto') )
				{
					foreach ( array_diff($this->Mobject->foto, $f) as $foto )
						$this->Mobject->del_foto($foto);
				}

				# остаемся на странице
				$this->Mobject->get();
			}

			# данные для селектов
			$data['form'] = $this->Mobject->array_data('all');

			$data['form']['type'] = array (
				'approved' => 'Одобрено',
				'rejected' => 'Отклонено',
			);

			# подключаем хэлпер
			$this->load->helper('form');

			# отображение
			$this->load->view('admin/object/view', $data);
		}


		# список пользователей
		function users($id=0, $act=null)
		{
			# удаление всех объектов пользователя
			if ($act=='all_del' && $id)
			{
				$SQL = "update nd_objects o set o.status='rejected' where o.user_id = $id and o.status = 'new'";
				$this->db->query($SQL);
			}
			# получаем список
			$SQL = "select u.*, 
						(select count(1) from nd_objects o where o.user_id = u.id) cnt,
						(select count(1) from nd_objects o where o.user_id = u.id and o.status = 'new') cnt_un_status
					from nd_user u
					order by cnt_un_status desc";
			$data['users'] = $this->db->query($SQL)->result_array();

			# отображение
			$this -> load -> view ( 'admin/user/list', $data );
		}


		# парсы
		function parse()
		{
			# данные парсинга
			$this->db->where('id', $parse_id);
			$query = $this->db->get('parse');
			$data = $query->result_array();

			# отображение
			$this->load->view('admin/object/parse_list', $data);
		}


		# проверка модераторов
		function check_parse ( $parse_id )
		{
			# данные парсинга
			$this->db->where('id', $parse_id);
			$query = $this->db->get('parse');
			$data['parse'] = $query->row_array();

			# получаем данные об объекте
			$data['object'] = $this -> Mobject -> info ( 0, $parse_id );

			# фотки
			$data['foto'] = $this -> Mobject -> foto ( $data['object']['id'] );

			# данные для селектов
			$data['form'] = $this -> Mobject -> array_data ( 'all' );

			$data['form']['type'] = array (
				'' => '',
				'new' => 'Новое',
				'ok' => 'Одобрено',
				'cancel' => 'Отклонено',
				'del' => 'Удалено',
			);

			# подключаем хэлпер
			$this->load->helper('form');
			
			# отображение
			$this->load->view('admin/object/parse_check', $data);
		}


		# статистика по работе модераторов
		function mstat($id=0)
		{
			# сводная таблица по всем модерам
			if ( !$id)
			{
				$SQL = "SELECT u.id, u.username, u.payment,
					( SELECT SUM(amount) FROM `nd_payment` WHERE moderator = u.id ) as amount,
					( SELECT count(1) FROM `nd_objects` WHERE moderator = u.id ) as parse
				FROM nd_user u
				WHERE type = 'moderator'";
				$query = $this->db->query($SQL);
				$data['stat'] = $query->result_array();

				# отображение
				$this->load->view('admin/stat/moderators', $data);
			}
			# детализация
			else
			{
				$SQL = "SELECT o.*, p.get_data, p.url, 
							(SELECT count(1) FROM nd_foto WHERE object = o.id) as foto
						FROM nd_objects o
						INNER JOIN parse p ON o.parse_id = p.id
						WHERE o.moderator = ? 
							and o.mdate > CURDATE() - INTERVAL 10 Day
						ORDER BY o.mdate DESC";
				$data['objects'] = $this->db->query($SQL, $id)->result_array();
				$data['user'] = $this->db->query("select * from nd_user where id = ?", $id)->row_array();
				
				# отображение
				$this->load->view('admin/stat/moderator', $data);
			}
		}


		# статистика парсера
		function pstat()
		{
			# все объявления за 30 дней
			$SQL = "SELECT o.id, o.house_id, o.moderator,
						o.status, o.reason_reject,
						o.cdate, o.mdate, o.ddate
					FROM nd_objects o 
					WHERE o.parse_id is not null 
						and o.cdate > CURDATE() - INTERVAL 30 Day
					order by o.cdate desc";
			$data['objects'] = $this->db->query($SQL)->result_array();
			$data['pstat'] = array();
			foreach ( $data['objects'] as $r )
			{
				$d = date('Y.m.d', strtotime($r['cdate']));
				
				if ( !array_key_exists($d, $data['pstat']) ) 
					$data['pstat'][$d] = array();
				
				$data['pstat'][$d][$r['id']] = $r;
			}
			
			
			
			echo '<pre>'.print_r($data, true).'</pre>';
			
			
			# отображение
			$this->load->view('admin/stat/parse', $data);
		}
		

		# проверка повтроности
		function double()
		{
			$SQL = <<<SQL
SELECT f.*, d.cnt
FROM nd_flat_sale f
INNER JOIN ( SELECT id_dom, kk, et, count(1) as cnt
	FROM `nd_flat_sale`
	WHERE `type` LIKE 'ok'
	GROUP BY id_dom
	HAVING count(1) > 1
) d ON f.id_dom = d.id_dom 
	AND f.kk = d.kk 
	AND f.et = d.et
WHERE f.type LIKE 'ok'
SQL;
			$query = $this->db->query($SQL);
			$data['stat'] = $query->result_array();
			echo '<pre>'.print_r($data, true).'</pre>';
		}


		# история парса
		function parse_log($date='')
		{
			if ( !$date ) $date = date('Y-m-d');
			
			echo '<pre>';
			echo str_pad('cdate', 25);
			echo str_pad('last_cdate', 25);
			echo str_pad('obj_id', 10);
			echo str_pad('obj_status', 13);
			echo str_pad('mdate', 25);
			echo str_pad('ddate', 25);
			echo str_pad('pid', 15);
			echo 'log_status';
			echo "\n".str_repeat('-', 150)."\n";

			foreach ( $this->Mparse->log($date) as $r )
			{
				if ( $r['obj_status'] == 'rejected' ) echo '<span style="background:#eef">';
				elseif ( $r['obj_status'] == 'removed' ) echo '<span style="background:#fee">';
				else echo '<span>';
				echo str_pad($r['cdate'], 25);
				echo str_pad($r['last_cdate'], 25);
				echo str_pad($r['id'], 10);
				echo str_pad($r['obj_status'], 13);
				echo str_pad($r['mdate'], 25);
				echo str_pad($r['ddate'], 25);
				echo '<a href="'.$r['url'].'" target="_blank">'.str_pad($r['parse_id'].'</a>', 15);
				echo $r['log_status'];
				echo '</span>'."\n";
			}
			echo '</pre>';
		}


		# проверка ненайденных
		function check_not_found($id=0, $status='')
		{
			# редиктирование
			if ( $id )
			{
				# возврат
				if ( $status == 'approved' )
				$sql = "update nd_objects obj set ddate = null where obj.id = ".(int)$id;
				
				# удаление
				if ( $status == 'removed' )
				$sql = "update nd_objects obj set obj.status = 'removed' where ddate is not null and obj.id = ".(int)$id;
				
				$data = $this->db->query($sql);
			}

			# получаем общее кол-во
			$sql = "select count(1) as cnt 
					from nd_objects obj 
					where obj.status = 'approved'
						and obj.ddate is not null";
			$data = $this->db->query($sql)->row_array();

			# если есть хотя бы один такой объект, получаем его данные
			if ($data['cnt'] > 0)
			{
				$sql = "SELECT obj.id, obj.parse_id, p.url
						FROM nd_objects obj, parse p
						WHERE obj.parse_id = p.id
							and obj.status = 'approved'
							and obj.ddate is not null
						LIMIT 1";
				$data['obj'] = $this->db->query($sql)->row_array();
			}

			# отображение
			$this->load->view('admin/object/check_not_found', $data);
		}


		# обновляем не распаршенные объявления
		function unparse($object_id = 0, $reasons = '')
		{
			$data['reasons'] = array (
				'no_adr' => 'нет адреса',
				'dd'     => 'добавить дом',
				'ne_ekb' => 'не Екб',
				'no_pr'  => 'нет цены',
				'fuflo'  => 'фуфло',
			);

			# отклонение
			if ( $object_id )
			{
				$sql = "update nd_objects obj 
						set obj.status = 'rejected', 
							obj.reason_reject = '".$data['reasons'][$reasons]."',
							obj.mdate = now()
						where obj.id = " . (int) $object_id;
				$this->db->query($sql);
				redirect('/admin/unparse');
			}

			# одобрение
			if ( $this->input->post('ok') )
			{
				$sql = "update nd_objects obj 
						set obj.ul = '".$this->input->post('ul')."', 
							obj.d  = '".$this->input->post('d')."',
							obj.house_id = ".$this->input->post('house_id')."
						where obj.id = ".$this->input->post('object_id');
				$this->db->query($sql);
				redirect('/admin/unparse');
			}

			$cond = array (
				"obj.status is null",
				"obj.house_id is null",
				"obj.parse_id is not null",
			);

			$sql = "select count(1) as cnt
					from nd_objects obj
					where ".implode(" and ", $cond);
			$r = $this->db->query($sql)->row_array();
			$data['count'] = $r['cnt'];

			$sql = "select obj.id, p.address, obj.price, obj.description
					from nd_objects obj, parse p
					where obj.parse_id = p.id and ".implode(" and ", $cond)."
					limit 10";
			$data['objects'] = $this->db->query($sql)->result_array();

			$data['reasons'] = array (
				'no_adr' => 'нет адреса',
				'dd'     => 'добавить дом',
				'ne_ekb' => 'не Екб',
				'no_pr'  => 'нет цены',
				'fuflo'  => 'фуфло',
			);


			# отображение
			$this->load->view('admin/object/unparse', $data);
		}



# ======================================================================
# Дома
# ----------------------------------------------------------------------

		# список домов
		function houses($street_id=0)
		{
			# список улиц
			$sql = "SELECT * FROM street WHERE city_id = ".$this->config->item('city_id')." order by name";
			$data['streets'] = $this->db->query($sql)->result_array();
			$data['street_id'] = $street_id;

			# список домов
			$sql = "SELECT * FROM house h WHERE h.street_id = ".$street_id." order by h.num*1, h.num";
			$data['houses'] = $this->db->query($sql)->result_array();

			# отображение
			$this->load->view('admin/house/list', $data);
		}

		# работа с конкретным домом
		function house($id=0)
		{
			if ($this->input->post('edit'))
			{
				$this->Mhouse->id = (int)$id;
				$this->Mhouse->data = array (
					'street_id'     => $this->input->post('street_id'),
					'num'           => $this->input->post('num'),
					'district_id'   => $this->input->post('district_id'),
					'ya_address'    => $this->input->post('ya_address'),
					'yaLat'         => $this->input->post('yaLat'),
					'yaLng'         => $this->input->post('yaLng'),
					'house_type_id' => $this->input->post('house_type_id'),
					'storey'        => $this->input->post('storey'),
					'material'      => $this->Mhouse->form_data['material'][$this->input->post('material')],
					'year'          => $this->input->post('year'),
					'name'          => htmlspecialchars($this->input->post('name')),
					'description'   => $this->input->post('description'),
					'source'        => $this->input->post('source'),
				);
				$this->Mhouse->save();
				redirect('/admin/house/'.$this->Mhouse->id);
				
			}

			# информация о выбраном доме
			$data['house'] = $this->Mhouse->info($id);
			
			# список улиц
			$data['streets'] = $this->Mhouse->streets();
			
			# список районов
			$data['districts'] = $this->Mhouse->districts();

			# отображение
			$this->load->view('admin/house/edit', $data);
		}


		# статистика по домам
		function houses_stat()
		{
			$sql = "select h.id, concat(s.type, '. ', s.name, ', д.', h.num) as address, ob.cnt, 
						h.material, h.year, h.storey, h.house_type_id, 
						(select max(floor) from nd_objects where house_id = h.id) max_floor
					from house h, street s, (
						SELECT obj.house_id, count(1) as cnt
						FROM nd_objects obj
						WHERE obj.status = 'approved'
						group by obj.house_id) ob
					where h.id = ob.house_id and h.street_id = s.id
						and (h.material = '' or h.year = 0 or h.storey = 0 or h.house_type_id = 0)
					order by s.name";
			
			$data['stat'] = $this->db->query($sql)->result_array();
			
			# отображение
			$this->load->view('admin/house/stat', $data);
		}

		function basemail()
		{
			echo '<table border=1>';
			$sql = "select * from nd_user";
			foreach ($this->db->query($sql)->result_array() as $r)
			{
				echo '<tr>';
				foreach ($r as $v) echo '<td>'.$v.'</td>';
				echo '</tr>';
			}
			echo '</table>';
		}

	}

?>
