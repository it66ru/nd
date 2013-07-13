<?php

class Cron_avito extends Controller {

	function __construct()
	{
		parent::Controller();

		include($this->config->item('path').'/simple_html_dom.php');
	}


	function index()
	{
		echo '1';
	}


	# добавление ссылок
	function get_links()
	{
		$url = 'http://www.avito.ru/ekaterinburg/kvartiry?params=201_1059';
		
		$html = file_get_html($url);
		
		$div = $html->find('div.t_i',0); 
		
		foreach ( $div->find('h3.t_i_h3') as $h3 )
		{
			$href = 'http://www.avito.ru' . $h3->find('a', 0)->href;
			echo $href."\n";
			
			$sql = "insert ignore into parse set source = 'avito', url = '".$href."', cdate = now()";
			$this->db->query($sql);
		}
	}


	# парсинг следующего
	function get_info($id = null)
	{
		# получаем следующую запись для парсинга
		$this->Mparse->get_next($id);
		
		# если парс не найден, то можно выходить
		if (empty($this->Mparse->data)) return;
		
		# парсим с авито
		if ($this->Mparse->data['source'] == 'avito')
		{
			# парсми и определяем статус
			$this->Mparse->data['status'] = $this->Mparse->avito_parsing() ? 'parse' : 'error';
			$this->Mparse->data['sdate'] = date('Y-m-d H:i:s');
		}
		
		# поиск id_doma
		if ($this->Mparse->data['status'] == 'parse')
			$this->Mparse->data['id_dom'] = $this->Mhouse->get_dom_id($this->Mparse->data['address'], $this->Mparse->data['title']);
		
		# сохранение парса
		$this->Mparse->save();
		
		print_r($this->Mparse->data);

		# если парс без ошибок, переносим данные в объект
		if ($this->Mparse->data['status'] == 'parse')
		{
			# создаем новый объект (если объект уже есть, то обновляем его)
			$this->Mobject->id = $this->Mparse->data['object_id'];
			
			# сохраняем сам объект
			$params = json_decode($this->Mparse->data['params'], true);
			$this->Mobject->data = array (
				'type'        => 'sale',
				'house_id'    => $this->Mparse->data['id_dom'],
				'rooms'       => $params['kk'],
				'space_total' => $params['pl'],
				'floor'       => $params['et'],
				'name'        => $this->Mparse->data['author'],
				'price'       => $this->Mparse->data['price'],
				'description' => $this->Mparse->data['info'],
				'parse_id'    => $this->Mparse->data['id'],
			);
			$this->Mobject->save();
			
			# удаляем старые фотки
			$this->Mobject->del_foto('all');
			
			# добавляем новые фотки
			foreach (json_decode($this->Mparse->data['foto'], true) as $n => $foto)
			{
				$file = $this->Mfoto->parse_avito($foto);
				$this->Mobject->add_foto($file, $n);
			}
		}
	}


	# проверка объявлений
	function check($id=0)
	{
		$cond = $id ? "o.id = ".$id : "o.status = 'approved' and ddate is null";
		
		$SQL = <<<SQL
SELECT p.id parse_id, p.url, o.id object_id
FROM parse p
INNER JOIN nd_objects o ON p.id = o.parse_id
WHERE $cond and source = 'avito'
ORDER BY p.check
LIMIT 1
SQL;
		$query = $this->db->query($SQL);
		$r = $query->row_array();

		# получаем всю страницу
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL,$r['url']); // set url to post to  
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable  
		curl_setopt($ch, CURLOPT_TIMEOUT, 3); // times out after 4s  
		$result = curl_exec($ch); // run the whole process  
		curl_close($ch);   

		# удачная проверка
		if ( substr_count($result, 'id="phone"') )
		{
			$this->db->query("UPDATE parse SET `check` = '".time()."' WHERE id = ".$r['parse_id']);
			$this->db->query("UPDATE nd_objects SET status = 'approved', ddate = null WHERE id = ".$r['object_id']);
			$status = 'ok';
		}
		# если кидает на список или какой-то дургой ответ, то значит удалено
		else
		{
			$this->db->query("UPDATE nd_objects SET ddate = now() WHERE id = ".$r['object_id']);
			$status = 'del';
		}

		# лог проверки
		$SQL = "INSERT INTO `parse_log` (`parse_id`, `cdate`, `status`) VALUES (".$r['parse_id'].", now(), '".$status."')";
		$this->db->query($SQL);

		echo '<pre>'.print_r($r, true).'</pre>';
		echo $status;


		# проверка всех продавцов (очень затратная операция!!!)
		$this->up_seller();
	}


	# обновление статуса продавцов
	function up_seller()
	{
		$this->db->query("truncate table nd_phones");
		$this->db->query("insert nd_phones select phone, count(1) as cnt from nd_objects where phone is not null and phone != '' group by phone");
		$this->db->query("update nd_objects set seller = null");
		$this->db->query("update nd_objects set seller = 'owner'  where phone in ( select phone from nd_phones where cnt < 3)");
		$this->db->query("update nd_objects set seller = 'agency' where phone in ( select phone from nd_phones where cnt > 10)");
	}


}

?>
