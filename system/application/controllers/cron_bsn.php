<?php

class cron_bsn extends Controller {

	function __construct()
	{
		parent::Controller();
		
		include('simple_html_dom.php');
	}

	function index()
	{
		echo $this->get_dom_id('соликамская 7', 'Продам 2-к квартиру, Металлургов, 40/1');
	}

	# добавление ссылок
	function get_links()
	{
		$url = 'http://www.bsn.ru/estate/live/sell/flats/';
		$html = file_get_html($url);

		# получаем все ссылки по формату
		$links = array();
		foreach ( $html->find('a[href^=/live/]') as $a )
		{
			$links[] = 'http://www.bsn.ru'.$a->href;
		}

		# добавляем в базу только уникальные
		foreach ( array_unique($links) as $href )
		{
			$SQL = "INSERT IGNORE INTO parse SET
					source = 'bsn', 
					url    = '".$href."', 
					cdate  = now()";
			$this->db->query($SQL);
			echo $href.'<br>';
		}
	}


	# парсинг конкретного объявления
	function get_info ( $id=0 )
	{
		$SQL = $id ? "SELECT id, url FROM parse WHERE source = 'bsn' and id = ".$id
			: "SELECT id, url FROM parse WHERE source = 'bsn' and status = '' LIMIT 1";
		
		$query = $this->db->query($SQL);
		
		if ( $query->num_rows() )
		{
			$r = $query->row_array();
			echo '<pre>'.print_r($r, true).'</pre>';
			
			# пишем в файл всю страницу
#			$html = file_get_html($r['url']);
#			file_put_contents('./cron_log/'.$r['id'].'.html', $html->outertext);
			
			$html = file_get_html('./cron_log/'.$r['id'].'.html');
			
			
			$div = $html->find('div.col700',0);
#			echo $div->outertext;
			
			# заголовок - адрес
			$title = $div->find('h1', 0)->plaintext;
			echo $title.'<br>';
			
			# инфо
			$main_info = $div->find('td[class=view_offer_row estateoffer_top]', 0)->plaintext;
			echo $main_info.'<br>';
			
			# цена
			$price = $div->find('td[class=view_offer_row estateoffer_price]', 0);
			$price->find('div',1)->innertext = '';
			$price = preg_replace('/(\D)/s', '', $price->plaintext);
			echo $price.'<br>';
			
			# данные по блокам
			foreach ( $div->find('td.estateoffer_title') as $td )
			{
				# примичание
				if ( trim($td->plaintext) == 'Примечание:' )
				{
					$description = $td->parent()->next_sibling()->find('div',0)->innertext;
				}
				
				# площади
				if ( trim($td->plaintext) == 'Площади:' )
				{
					$space = $td->parent()->next_sibling()->find('div',0)->innertext;
				}
				
				# доп. инфо
				if ( trim($td->plaintext) == 'Общая информация:' )
				{
					$dop_info = $td->parent()->next_sibling()->find('div',0)->innertext;
				}
				
				# продавец
				if ( trim($td->plaintext) == 'Продавец:' )
				{
					$seller = $td->parent()->next_sibling()->find('div',0)->innertext;
				}
			}

			echo $description.'<br>';
			echo $space.'<br>';
			echo $dop_info.'<br>';
			echo $seller.'<br>';
			
			
			return;
			
			$data = array();
			$html = file_get_html($r['url']);
			
			if ( $html )
			{
				$data['title'] = $html->find('h1.p_i_ex_title',0)->plaintext; 
				$data['title'] = trim($data['title']);
				
				$data['price'] = $html->find('span.p_i_price',0)->plaintext;
				$data['price'] = preg_replace('|\D|i', '', $data['price']);
				
				$address = $html->find('dd[class=b_d_c b_d_c_x]', 0);
				if ( $address )
				{
					$s = $address->find('span', 0);
					if ( $s ) $s->innertext = '';
 					$a = $address->find('a', 0);
 					if ( $a ) $a->innertext = '';
					$data['address'] = trim($address->plaintext);
				}
				else $data['address'] = null;
				$data['id_dom'] = $this->get_dom_id($data['address'], $data['title']);
				
				$data['author'] = $html->find('#seller',0)->find('strong',0)->plaintext;
				$data['author'] = str_replace('"', '', $data['author']);
				$data['author'] = trim($data['author']);
				
				$data['info'] = $html->find('dl[class=b_d b_d_d]',0)->plaintext;
				$data['info'] = trim($data['info']);
				
				if ( $params = $html->find('dl[class=b_d b_d_l]', 0) )
				{
					$data['params']['kom'] = $params->find('div', 0)->plaintext;
					$data['params']['kom'] = trim($data['params']['kom']);
					
					$kk = $params->find('div', 1)->find('a', 0);
					$data['params']['kk'] = (int) trim($kk->plaintext);
					$kk->innertext = '';
					
					@list($pl, $et) = explode('на', $params->find('div', 1)->plaintext);
					$data['params']['pl'] = (int) trim($pl);
					$data['params']['et'] = (int) trim($et);
				}
				
				$fotos = array();
				if ( $foto = $html->find('#photo',0) )
				{
					foreach ( $foto->find('img') as $img )
						$fotos[] = basename($img->src);
					$fotos = array_unique($fotos);
					$fotos = array_values($fotos);
				}
				$data['foto'] = json_encode($fotos);
				
				$data['get_data'] = json_encode($data);
				
				$data['status'] = 'parse';
			}
			else
			{
				$data['status'] = 'error';
			}
			
			echo '<pre>'.print_r($data, true).'</pre>';
			
			$data['sdate'] = date('Y-m-d H:i:s');
			$data['params'] = json_encode($data['params']);
			
			$this->db->set($data);
			$this->db->where('id', $r['id']);
			$this->db->update('parse');
			
			# добавляем новый объект в базу
			$this->parse_to_object($r['id']);
		}
	}


	# проверка объявлений
	function check($id=0)
	{
		$cond = $id ? "o.id = ".$id : "o.status = 'approved'";
		
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
			$status = 'ok';
		}
		# если кидает на список или какой-то дургой ответ, то значит удалено
		else
		{
			$this->db->query("UPDATE nd_objects SET status = 'removed', ddate = now() WHERE id = ".$r['object_id']);
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


	# поиск дома по адресу
	function get_dom_id ( $address, $title='' )
	{
		if (!$address) $address = preg_replace('/Продам(.*)квартиру/', '', $title);
		
		$key = 'AKgVxU4BAAAAW0EGcQIAcVX3PcvNJLoVFqMkRImZfQKEXfAAAAAAAAAAAACvXzKRousBqhlBTh8gc-ZKP1ckJQ==';
		$address = 'Екатеринбург ' . $address;
		$url = 'http://geocode-maps.yandex.ru/1.x/?geocode='.$address.'&key='.$key;
		$xml = simplexml_load_file($url);

		echo $url.'<br>';

		foreach ( $xml->GeoObjectCollection->featureMember as $m )
		{
			$data = $m->GeoObject->metaDataProperty->GeocoderMetaData;
			
			# будем смотреть только тех, у когото точно "дом"
			if ( $data->kind == 'house' )
			{
				$locality = $data->AddressDetails->Country->AdministrativeArea->Locality;

				# только екб
				if ( $locality->LocalityName == 'Екатеринбург' )
				{
					$t = $locality->DependentLocality->Thoroughfare ? $locality->DependentLocality->Thoroughfare : $locality->Thoroughfare;
					
					$ul = $t->ThoroughfareName;
					$d = $t->Premise->PremiseNumber ? $t->Premise->PremiseNumber : $t->Premise->PremiseName;
					
					$SQL = <<<SQL
select h.id
from house h, street s
where h.streeT_id = s.id
	and instr('$ul', s.name) > 0
	and h.num like '$d'
SQL;
					echo $SQL;
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


	# превращаем парс в объект
	function parse_to_object($id)
	{
		# получаем данные парса
		$parse = $this->Mparse->get($id);
		echo '<pre style="color:#f00;">'.print_r($parse, true).'</pre>';

		# если объект уже есть, то обновляем его, иначе создаем новый
		$this->Mobject->id = $parse['object_id'];

		# сохраняем сам объект
		$this->Mobject->data = array (
			'type' => 'sale',
			'house_id' => $parse['get_data']['id_dom'],
			'rooms' => $parse['get_data']['params']['kk'],
			'space_total' => $parse['get_data']['params']['pl'],
			'floor' => $parse['get_data']['params']['et'],
			'name' => $parse['get_data']['author'],
			'price' => $parse['get_data']['price'],
			'description' => $parse['get_data']['info'],
			'parse_id' => $parse['id'],
		);
		$this->Mobject->save();

		# добавляем все фотки
		$this->Mobject->del_foto('all');
		foreach ( $parse['get_data']['foto'] as $n => $foto )
		{
			$file = $this->Mfoto->parse_avito($foto);
			$this->Mobject->add_foto($file, $n);
		}

		return true;
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


	function testcurl()
	{
		$ch = curl_init(); // create cURL handle (ch)


		// set some cURL options
		$ret = curl_setopt($ch, CURLOPT_URL,            "http://mail.yahoo.com");
		$ret = curl_setopt($ch, CURLOPT_HEADER,         1);
		$ret = curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
		$ret = curl_setopt($ch, CURLOPT_TIMEOUT,        30);

		$info = curl_getinfo($ch);
		curl_close($ch);
		
		echo '<pre>'.print_r($info, true).'</pre>';

	}


}

?>
