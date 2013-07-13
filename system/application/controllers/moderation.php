<?php

# модерация объявлений
class Moderation extends Controller {

	private $data = array();

	function __construct()
	{
		parent::__construct();
		
		$access = array('admin', 'moderator');
		
		# только для админов и модераторов. иначе шифруемся, показываем страницу 404
		if ($this->auth->login() == false || !in_array($this->auth->user['type'], $access))
			show_404('page');
	}


	# список объявлений
	function index()
	{
		$m = $this->auth->user['id'];
		
		# проверка текущего задания
		$SQL = <<<SQL
SELECT DATE_FORMAT( mdate, '%Y-%m-%d' ) as mdate, count(1) as cnt
FROM nd_objects
WHERE moderator = $m
GROUP BY DATE_FORMAT(mdate, '%Y-%m-%d')
SQL;
		$query = $this->db->query($SQL);
		
		# приводим к нужноми виду
		$cnt = array();
		foreach ( $query->result_array() as $r )
			$cnt[$r['mdate']] = $r['cnt'];
		
		$time = time();
		
		# текущий день в неделе
		$t = date('w',$time) ? date('w',$time)-1 : 6;
		
		# делаем что-то типа календаря
		$data['calendar'] = array();
		for ( $w=0; $w<4; $w++ )
		{
			$week = date('d.m', $time + (0-$t)*24*60*60 - $w*7*24*60*60).' - '.date('d.m', $time + (6-$t)*24*60*60 - $w*7*24*60*60);
			$data['calendar'][$week] = array();
			for ( $d=0; $d<7; $d++ )
			{
				$date = date('Y-m-d', $time + ($d-$t)*24*60*60 - $w*7*24*60*60);
				$data['calendar'][$week][$date] = isset($cnt[$date]) ? $cnt[$date] : 0;
			}
		}
		
		# платежи
		$this->db->where('moderator', $this->auth->user['id']);
		$this->db->order_by('cdate', 'desc');
		$query = $this->db->get('nd_payment');
		$data['payment'] = $query->result_array();
		
		# баланс
		$sql = "select
			( select sum(amount) from `nd_payment` where moderator = " . $this->auth->user['id'] . " ) as amount,
			( select count(1) from `nd_objects` where moderator = " . $this->auth->user['id'] . " ) as parse
		from dual";
		$query = $this->db->query($sql);
		$data['balance'] = $query->row_array();
		
		# разная сводная статистика
		$data['cnt_free']  = $this->cnt_free();
		$data['cnt_error'] = $this->cnt_error();
		
		# отображение
		$this->load->view('moderation/index', $data);
	}


	# модерация конкретного объявления
	function obj($id, $type = null)
	{
		# загрузка объявления
		$this->data['obj'] = $this->Mobject->load($id);
		
		# проверка доступа
		if (!$this->Mobject->isCanEdit())
			show_404('page');
		
		# статусы объявления
		$this->Mobject->form_data['status'] = array('approved' => 'Одобрено', 'rejected' => 'Отклонено');
		
		# редактирование объявления
		if ($this->input->post('edit'))
		{
			# заполняем данные объявления
			$this->Mobject->complete_data_from_post();
			$this->Mobject->data['mdate'] = date('Y-m-d H:i:s');
			$this->Mobject->data['moderator'] = $this->auth->user['id'];
			# сохраняем объект
			$this->Mobject->save();

			# сохранение фотографий
			$foto = (array) $this->input->post('foto');
			# удаляем фотки
			foreach (array_diff($this->Mobject->foto, $foto) as $f)
				$this->Mobject->del_foto($f);
			# сортируем фотки
			$this->Mobject->sort_foto($foto);

			# при модерации - перекидываем на главную
			if ($type == 'm') redirect('/moderation/next');
			# во всех других случаях перекидываем на саму страницу
			else redirect('/moderation/obj/'.$this->Mobject->id);
		}
		
		# отображение
		$this->load->view('moderation/object', $this->data);
		
	}


	# выбор следующего объявления для модерации
	function next()
	{
		# проверка текущего задания
		$sql = "select id
			from nd_objects
			where moderator = ".$this->auth->user['id']."
				and status = 'new'
				and parse_id is not null
			limit 1";
		$query = $this->db->query($sql);
		
		# если текущее задание есть
		if ($query->num_rows())
		{ 
			$data = $query->row_array();
		}
		# если задания нет, то берем следующее
		else 
		{
			$sql = "select id
				from nd_objects
				where moderator is null 
					and status = 'new'
					and parse_id is not null
					and house_id is not null
				limit 1";
			$query = $this->db->query($sql);
			
			# если нашлось свободное, закрепляем за модератором
			if ($query->num_rows())
			{
				$data = $query->row_array();
				
				$this->db->set('moderator', $this->auth->user['id']);
				$this->db->where('id', $data['id']);
				$this->db->update('nd_objects');
			}
		}

		# если задание есть
		if ($data['id'])
			return redirect('/moderation/obj/'.$data['id'].'/m');
		# если задания нет
		else
			return redirect('/moderation');
	}


	# история модерации на дату
	public function history($date='')
	{
		$cond = $date ? "and o.mdate between '$date 00:00:00' and '$date 23:59:59'" : "";
		$sql = "select o.*, p.get_data, p.url, (select count(1) from nd_foto where object = o.id) as foto
				from nd_objects o
				inner join parse p on o.parse_id = p.id
				where o.moderator = " . $this->auth->user['id'] . " " . $cond . "
				order by o.mdate desc";
		$data['objects'] = $this->db->query($sql)->result_array();
		$data['date'] = $date;

		# отображение
		$this->load->view('moderation/history', $data);
	}


	# список ошибок модерации
	public function error()
	{
		$sql = "select * from v_object_error e";
		if ($this->auth->user['type'] == 'moderator')
			$sql.= " where e.moderator_id = " . $this->auth->user['id'];
		$data['objects'] = $this->db->query($sql)->result_array();

		# отображение
		$this->load->view('moderation/error', $data);
	}


	# подсчет свободных объявлений
	private function cnt_free()
	{
		$sql = "select count(1) as cnt
				from nd_objects o
				where o.status = 'new'
					and o.parse_id is not null
					and o.house_id is not null
					and o.moderator is null";
		$data = $this->db->query($sql)->row_array();
		return $data['cnt'];
	}


	# подсчет кол-ва ошибок
	private function cnt_error()
	{
		$sql = "select count(1) as cnt
				from v_object_error e";
		if ($this->auth->user['type'] == 'moderator')
			$sql.= " where e.moderator_id = " . $this->auth->user['id'];
		$data = $this->db->query($sql)->row_array();
		return $data['cnt'];
	}

}

?>
