<?php

	class copy_emls extends Controller {

		function __construct()
		{
			parent::Controller();
			include('simple_html_dom.php');
		}
		
		function index()
		{
			echo '<pre>';
			
			$sql = "select id, url from sp_house where district = '' limit 3000";
			foreach ( $this->db->query($sql)->result_array() as $r )
			{
				$url = explode('/', $r['url']);
				echo $url[2].'<br>';
				
				$this->db->set('district', trim($url[2]));
				$this->db->where('id', $r['id']);
				$this->db->update('sp_house');
				
			}
			
			echo '</pre>';
		}
		
		function house()
		{
			echo '<META HTTP-EQUIV="REFRESH" CONTENT="1">';
			
			$sql = "select title
					from ya_foto 
					where ya_address is null
					order by title
					limit 1";
			$q = $this->db->query($sql);
			$r = $q->row_array();

			$key = 'AKgVxU4BAAAAW0EGcQIAcVX3PcvNJLoVFqMkRImZfQKEXfAAAAAAAAAAAACvXzKRousBqhlBTh8gc-ZKP1ckJQ==';
			$address = 'Россия, Санкт-Петербург, ' . $r['title'];
			$url = 'http://geocode-maps.yandex.ru/1.x/?geocode='.$address.'&key='.$key;

			$xml = simplexml_load_file($url);
			$yaa = (string) $xml->GeoObjectCollection->featureMember->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AddressLine;
			
			if (!$yaa) $yaa = '-';

			$this->db->set('ya_address', $yaa);
			$this->db->where('title', $r['title']);
			$this->db->update('ya_foto');

			echo $r['title'].' = '.$yaa;
		}
		
		function parse($url)
		{
			$sql = "select * from sbp_emls where url = ?";
			$data = $this->db->query($sql, $url)->row_array();
			
			if ( $data['info'] == '404' )
			{
				$this->db->set('parse', 1);
				$this->db->where('url', $url);
				$this->db->update('sbp_emls');
			}
			else
			{
				$info = unserialize($data['info']);
				$upData = array (
					'address' => $this->return_value($info, 'Адрес:'),
					'metro' => $this->return_value($info, 'Метро:'),
					'rooms' => $this->return_value($info, 'Кол-во комнат:'),
					'composition' => $this->return_value($info, 'Планировка:'),
					'total_sq' => $this->return_value($info, 'Общая пл.:'),
					'living_sq' => $this->return_value($info, 'Жилая пл.:'),
					'rooms_sq' => $this->return_value($info, 'Пл.комнат:'),
					'kitchen_sq' => $this->return_value($info, 'Кухня:'),
					'hall' => $this->return_value($info, 'Прихожая:'),
					'corridor' => $this->return_value($info, 'Коридор:'),
					'building' => $this->return_value($info, 'Здание:'),
					'floor' => $this->return_value($info, 'Этаж:'),
					'bathrooms' => $this->return_value($info, 'Санузел:'),
					'balcony' => $this->return_value($info, 'Балкон:'),
					'gender' => $this->return_value($info, 'Пол:'),
					'bath' => $this->return_value($info, 'Ванна:'),
					'hot_water' => $this->return_value($info, 'Гор.вода:'),
					'garbage' => $this->return_value($info, 'Мусоропровод:'),
					'entry' => $this->return_value($info, 'Вход:'),
					'parse' => 1
				);
				$this->db->where('url', $url);
				$this->db->update('sbp_emls', $upData);
				echo '<pre>'.print_r($upData, true).'</pre>';
			}
		}
		
		function return_value($data, $key)
		{
			foreach ( $data as $r )
			{
				if ( $r[0] == $key )
				{
					if ( trim($r[1]) )
					{
						if ( $key == 'Адрес:' )
						{
							$adr = explode(',', $r[1]);
							return trim($adr[2]).', '.trim($adr[3]);
						}
						return trim($r[1]);
					}
					else return null;
				}
			}
		}
		
		function copy_link_page()
		{
			# квартиры за сегодня
			$url = 'http://emls.ru/flats/?query=reg/2/dept/2/sort1/7/dir1/1/sort2/1/dir2/2/interval/4/s/1';
			$html = file_get_html($url);
	
			$links = $html->find('a[href^=/fullinfo/]');
			
			foreach ( $links as $a )
			{
				$sql = "INSERT IGNORE INTO sbp_emls SET url = '".$a->href."', date = now()";
				$this->db->query($sql);
				echo $sql.'<br>';
			}
			
			$v = count($links);
			
			$html->clear();
			unset($links);
			
			return $v;
		}


		function get_page()
		{
			$query = $this->db->query("select url from sbp_emls where up is null order by date desc limit 1");
			
			if ( $query->num_rows() )
			{
				$r = $query->row_array();
				
				$url = 'http://emls.ru'.$r['url'];
				echo $url;
				
				$str = file_get_contents($url);
				$str = str_replace('<sup>2</sup</td>', '<sup>2</sup></td>', $str);
				
				if ( $html = str_get_html($str) )
				{
					$div = $html->find('div.content', 0);
					
					$tab = $html->find('table', 1);
					
					$data = array();
					
					foreach ( $tab->find('tr') as $tr )
					{
						$key = $tr->find('td',0);
						$val = $tr->find('td',1);
						
						if ($key && $val)
						{
							$data[] = array (
								iconv('cp1251', 'utf-8', trim($key->plaintext)),
								iconv('cp1251', 'utf-8', trim($val->plaintext)),
							);
						}
					}
				}
				
				$this->db->set('up', date('Y-m-d H:i:s'));
				$this->db->set('info', isset($data) ? serialize($data) : '404');
				$this->db->where('url', $r['url']);
				$this->db->update('sbp_emls');
				
				if ( isset($data) ) echo '<pre>'.print_r($data, true).'</pre>';
				$this->parse($r['url']);
			}
		}


		function up_house()
		{
			$q = $this->db->query("select * from sbp_emls where up is not null and info != '404' and house_id is null limit 500");
			
			foreach ( $q->result_array() as $r ) 
			{
				$adr = explode(' ', $r['address']);
				$house_num = str_replace('д.', '', array_pop($adr));
				$ul_type = str_replace('.,', '', array_pop($adr));
				$ul_name = implode(' ', $adr);
			
				# поиск дома в нашей базе
				$SQL = "select h.id 
						from house h
						inner join street s on s.id = h.street_id 
							and s.city_id = 2
							and s.type = '".$ul_type."'
							and s.name = '".$ul_name."'
						where h.num = '".$house_num."'";

				$q = $this->db->query($SQL);
				# дом нашелся
				if ( $q->num_rows() )
				{
					$h = $q->row_array();
					$this->db->set('house_id', $h['id']);
					$this->db->where('url', $r['url']);
					$this->db->update('sbp_emls');
					
					echo '+';
				}
				# дом не нашелся
				else
				{
					$this->db->set('house_id', 0);
					$this->db->where('url', $r['url']);
					$this->db->update('sbp_emls');
					
					echo '-';
				}
				
				echo ' '.$r['address'].' : '.$ul_type.' / '.$ul_name.' / '.$house_num.' <br>';
			}
		}


		function copy_ya_foto()
		{
			echo '<META HTTP-EQUIV="REFRESH" CONTENT="1">';
			
			$sql = "select * from ya_foto where copy = 0 limit 10";
			foreach ( $this->db->query($sql)->result_array() as $r )
			{
				$d = ceil($r['id']/1000);
				if (!file_exists('ya_foto/'.$d))
					mkdir('ya_foto/'.$d);
				
				$foto = md5($r['yaid']).'.jpg';
				$url = str_replace('_XXS', '_orig', $r['url']);
				copy($url, 'ya_foto/'.$d.'/'.$foto);
				
				$this->db->set('copy', 1);
				$this->db->set('foto', $foto);
				$this->db->where('id', $r['id']);
				$this->db->update('ya_foto');

				echo $r['id'].'<br>';
			}
		}


		function check_address()
		{
			echo '<META HTTP-EQUIV="REFRESH" CONTENT="3">';
			$this->load->model('Mhouse', 'house');
			$sql = "select h.id as house_id,
						c.name as city_name,
						s.type as street_type,
						s.name as street_name,
						h.num as house_num
					from house h, street s, city c
					where h.street_id = s.id and s.city_id = c.id and h.ya_address is null
					order by c.name, s.name, h.num
					limit 10";
			foreach ( $this->db->query($sql)->result_array() as $_ )
			{
				$_['address'] = 'г. '.$_['city_name'].', '.$_['street_type'].'. '.$_['street_name'].', д.'.$_['house_num'];
				$yaa = $this->house->getYandexAddress($_['address']);
				echo $_['house_id'].' = '.$_['address'].' / '.$yaa.'<br>';
				
				if ($yaa)
				{
					$sql = "update house h set h.ya_address = '".$yaa."' where h.id = '".$_['house_id']."'";
					$this->db->query($sql);
				}
			}
		}

		function check_double()
		{
			$sql = "select h.ya_address
					from house h
					group by h.ya_address
					having count(1) > 1
					order by h.ya_address
					limit 500";
			
			foreach ($this->db->query($sql)->result_array() as $d)
			{
				$sql = "select  * from house h where h.ya_address like '".$d['ya_address']."'";
				echo '<table border=1>';
				foreach ($this->db->query($sql)->result_array() as $_)
				{
					$sl = strlen($_['num']);
					$bg = substr($_['ya_address'], (-1)*$sl) != $_['num'] ? '#ffbbbb' : '#ffffff';
					echo '<tr bgcolor="'.$bg.'">
						<td>'.$_['id'].'</td>
						<td>'.$_['street_id'].'</td>
						<td>'.$_['ya_address'].'</td>
						<td>'.$_['num'].'</td>
						<td id="h'.$_['id'].'"><a href="#h'.$_['id'].'" onClick="del('.$_['id'].'); return false;">del</a></td>
						
					</tr>';
				}
				echo '</table><br>';
			}
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type= "text/javascript">
function del(id)
{
	$.ajax({
		url: '/copy_emls/ajax_del/'+id,
		success: function() {
			$('#h'+id).html('-');
		}
	});
	return false;
}

</script>
<?
		}

		function ajax_del($id)
		{
			$this->db->where('id', $id);
			$this->db->delete('house');
		}

	}

?>


