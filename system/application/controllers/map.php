<?php

	class Map extends Controller {

		function __construct()
		{
			parent::Controller();
		}
		
		function index()
		{
			$data['key'] = 'AKgVxU4BAAAAW0EGcQIAcVX3PcvNJLoVFqMkRImZfQKEXfAAAAAAAAAAAACvXzKRousBqhlBTh8gc-ZKP1ckJQ==';
			$data['search'] = array();
			
			# параметры запроса
			$url = parse_url($_SERVER['REQUEST_URI']);
			if ( isset($url['query']) )
				parse_str($url['query'], $_GET);
			else
				$_GET = array();

			# минимальные и максимальные значения
			$limits = array (
				'Price' => array(1000, 10000),
				'Space' => array(20, 100),
				'Floor' => array(1, 12),
			);
			foreach ( $limits as $p => $v )
			{
				# минимальный лимит
				$_GET['min'.$p] = empty($_GET['min'.$p]) ? 0 : (int) $_GET['min'.$p];
				$data['search']['min'.$p] = $_GET['min'.$p] ? $_GET['min'.$p] : $v[0];
				if ( $_GET['min'.$p] <= $v[0] ) $_GET['min'.$p] = 0;
				# максимальный лимит
				$_GET['max'.$p] = empty($_GET['max'.$p]) ? 0 : (int) $_GET['max'.$p];
				$data['search']['max'.$p] = $_GET['max'.$p] ? $_GET['max'.$p] : $v[1];
				if ( $_GET['max'.$p] >= $v[1] ) $_GET['max'.$p] = 0;
			}
			
			$_GET['room'] = empty($_GET['room']) ? array() : array_intersect(array(1,2,3,4,5), (array)$_GET['room']);
			$data['search']['room'] = $_GET['room'];

			# упаковка переменные
			$_GET['minPrice'] *= 1000;
			$_GET['maxPrice'] *= 1000;
			$data['js_params'] = array (
				'rooms' => $_GET['room'] ? implode('-', $_GET['room']) : '',
				'price' => $_GET['minPrice'].'-'.$_GET['maxPrice'],
				'space' => $_GET['minSpace'].'-'.$_GET['maxSpace'],
				'floor' => $_GET['minFloor'].'-'.$_GET['maxFloor'],
				'rand'  => rand(0,999999999),
			);

			# отображение
			$this -> load -> view ( 'map/index' , $data );
		}


		# список объектов, кгруппированных по домамм в опредленном квадрате x,y на масштабе z
		# условия для поиска передаются через POST (rooms, price, space, floor)
		function search ( $z, $x, $y )
		{
			$maxInTile = 7;    // лимит домов в одном тайле (меньшего порядка)
			$maxZoom   = 17;   // конечный зум карты (нет тайлов меньшего порядка)
			
			# следующий зум
			$nz = $z == $maxZoom ? $z : $z+1;
			
			# обрабатываем входные данные
			$price = explode('-',$this->input->post('price'));
			$space = explode('-',$this->input->post('space'));
			$floor = explode('-',$this->input->post('floor'));
			
			$rooms = array();
			foreach ( explode('-',$this->input->post('rooms')) as $r ) if ( (int) $r ) $rooms[] = (int) $r;
			
			if ( !isset($price[1]) ) $price[1] = 0;
			if ( !isset($space[1]) ) $space[1] = 0;
			if ( !isset($floor[1]) ) $floor[1] = 0;
			
			# параметры поиска
			$cond = array ( "obj.status = 'approved'", "h.z".$z."x = ".$x, "h.z".$z."y = ".$y ) ;
			if ( $rooms ) $cond[] = "obj.rooms IN (".implode(',',$rooms).")";
			if ( $price_ot = (int) $price[0] ) $cond[] = "obj.price >= ".$price_ot;
			if ( $price_do = (int) $price[1] ) $cond[] = "obj.price <= ".$price_do;
			if ( $space_ot = (int) $space[0] ) $cond[] = "obj.space_total >= ".$space_ot;
			if ( $space_do = (int) $space[1] ) $cond[] = "obj.space_total <= ".$space_do;
			if ( $floor_ot = (int) $floor[0] ) $cond[] = "obj.floor >= ".$floor_ot;
			if ( $floor_do = (int) $floor[1] ) $cond[] = "obj.floor <= ".$floor_do;
			$cond[] = "obj.house_id = h.id";
			$cond[] = "h.house_type_id = ht.id";
			
			# поля запроса
			$fields = array (
				'h.id as dom', 
				'h.yaLat as lat', 
				'h.yaLng as lng', 
				'obj.id',
				'obj.rooms as kk', 
				'obj.space_total as pl', 
				'obj.price',
				'ht.style',
			);
			
			if ( $z < $maxZoom )
			{
				$fields[] = 'h.z'.($z+1).'x as nzx';
				$fields[] = 'h.z'.($z+1).'y as nzy';
			}
			
			$SQL = "SELECT ".implode(", ", $fields)."
				FROM nd_objects obj, house h, house_type ht
				WHERE ".implode(" AND ", $cond);
			
			# получаем все объявления
			$result = array();
			$query = $this->db->query($SQL);
			foreach ( $query->result_array() as $r )
			{
				# номер тайла меньешго порядка
				$t =  $z < $maxZoom ? 'z'.($z+1).'x'.$r['nzx'].'y'.$r['nzy'] : 'z'.$z.'x'.$x.'y'.$y;
				if ( !isset($result[$t]) ) $result[$t] = array();
				
				# группируем дома по тайлам меньшего порядка
				if ( !isset($result[$t][$r['dom']]) )
					$result[$t][$r['dom']] = array (
						 'id' => $r['dom'],
						'lng' => (double) $r['lng'],
						'lat' => (double) $r['lat'],
						'ico' => $r['style'],
						'obj' => array () );
				
				# группируем объекты по домам
				$result[$t][$r['dom']]['obj'][] = array (
					'id' => $r['id'],
					'kk' => $r['kk'],
					'pl' => $r['pl'],
					'pr' => number_format($r['price'], 0, ',', ' '),
				);
			}
			
			# если это не последний зум, прокручиваем все тайлы
			if ( $z < $maxZoom ) foreach ( $result as $t => $bilds )
			{
				$count = count($bilds);
				
				# если в тайле домов большем чем нужно
				if ( $count > $maxInTile )
				{
					# находим среднее значение координат
					$lng = $lat = 0;
					
					foreach ( $bilds as $b )
					{
						$lng += $b['lng'];
						$lat += $b['lat'];
					}
					
					$result[$t] = array (
						'lng' => $lng / $count,
						'lat' => $lat / $count,
						'count' => $count,
					);
				}
			}
			
			$data['response'] = array ( 'reqid' => 0, 'type' => 'buildings', 'results' => $result );
			
			# копия запроса на поиск
			$data['request'] = array (
				'search' => array (
					'rooms' => implode('-',$rooms),
					'price' => $price_ot.'-'.$price_do,
					'space' => $space_ot.'-'.$space_do,
					'floor' => $floor_ot.'-'.$floor_do
				),
				'coordinates' => array ( 'tile' => $x.','.$y, 'zoom' => $z ),
			);
			
			# ответ в JSON'e
			echo json_encode($data);		}

		function search_all ( $bounds )
		{
			list($lt, $lg) = explode('-', $bounds);
			$lt = (int) $lt;
			$lg = (int) $lg;
			
			# обрабатываем входные данные
			$price = explode('-',$this->input->post('price'));
			$space = explode('-',$this->input->post('space'));
			$floor = explode('-',$this->input->post('floor'));
			
			$rooms = array();
			foreach ( explode('-', $this->input->post('rooms')) as $r ) 
				if ( (int) $r ) 
					$rooms[] = (int) $r;
			
			if ( !isset($price[1]) ) $price[1] = 0;
			if ( !isset($space[1]) ) $space[1] = 0;
			if ( !isset($floor[1]) ) $floor[1] = 0;
			
			# параметры поиска
			$cond = array("f.type = 'ok'");
			$cond[] = "h.yaLat >= ".$lt/100;
			$cond[] = "h.yaLat < ".($lt+1)/100;
			$cond[] = "h.yaLng >= ".$lg/100;
			$cond[] = "h.yaLng < ".($lg+1)/100;
			
			if ( $rooms ) $cond[] = "f.kk IN (".implode(',',$rooms).")";
			if ( $price_ot = (int) $price[0] ) $cond[] = "f.cena >= ".$price_ot;
			if ( $price_do = (int) $price[1] ) $cond[] = "f.cena <= ".$price_do;
			if ( $space_ot = (int) $space[0] ) $cond[] = "f.pl_o >= ".$space_ot;
			if ( $space_do = (int) $space[1] ) $cond[] = "f.pl_o <= ".$space_do;
			if ( $floor_ot = (int) $floor[0] ) $cond[] = "f.et >= ".$floor_ot;
			if ( $floor_do = (int) $floor[1] ) $cond[] = "f.et <= ".$floor_do;
			$cond[] = "obj.house_id = h.id";
			$cond[] = "h.house_type_id = ht.id";

			# копия запроса на поиск
			$data['request'] = array (
				'search' => array (
					'rooms' => implode('-',$rooms),
					'price' => $price_ot.'-'.$price_do,
					'space' => $space_ot.'-'.$space_do,
					'floor' => $floor_ot.'-'.$floor_do
				),
			);

			# поля запроса
			$fields = array (
				'h.id as dom', 
				'h.yaLat as lat', 
				'h.yaLng as lng', 
				'f.id', 
				'f.kk', 
				'f.pl_o as pl', 
				'f.cena',
				'ht.style',
			);

			$SQL = "SELECT ".implode(", ", $fields)."
				FROM nd_objects obj, house h, house_type ht
				WHERE ".implode(" AND ", $cond);
				// "ORDER BY f.date_up DESC LIMIT 100";

			# получаем все объявления
			$result = array();
			$query = $this->db->query($SQL);
			foreach ( $query->result_array() as $r )
			{
				$d = $r['dom'];
				# если такого дома не существует
				if ( !isset($result[$d]) )
					$result[$d] = array (
						 'id' => $r['dom'],
						'lng' => (double) $r['lng'],
						'lat' => (double) $r['lat'],
						'ico' => $r['style'],
						'obj' => array () );
				
				# группируем объекты по домам
				$result[$d]['obj'][] = array (
					'id' => $r['id'],
					'kk' => $r['kk'],
					'pl' => $r['pl'],
					'pr' => number_format($r['cena'], 0, ',', ' '),
				);
			}

			# прокручиваем все дома
			$data['results'] = array();
			foreach ( $result as $d )
			{
				$obj = array('lng' => $d['lng'], 'lat' => $d['lat']);
				$obj['d'] = $d['id'];	// id дома
				$obj['i'] = $d['ico'];	// иконка дома
				
				# если в доме несколько предложений
				if ( count($d['obj']) > 1 )
				{
					$obj['c'] = count($d['obj']);
					# достаем все id
					$n = array();
					foreach ($d['obj'] as $i) $n[] = $i['id'];
					$obj['n'] = implode('-',$n);
				}
				# если только одно предложение в доме
				else 
				{
					$obj['n'] = $d['obj'][0]['id'];	// id объекта
					$obj['k'] = $d['obj'][0]['kk'];	// кол-во комнат
					$obj['s'] = $d['obj'][0]['pl'];	// площадь
					$obj['p'] = $d['obj'][0]['pr'];	// цена
				}
				
				# добавляем в результаты
				$data['results'][] = $obj;
			}

			# ответ в JSON'e
			echo json_encode($data);
		}

		function objects ( $ids )
		{
			$objects = array();
			
			foreach ( explode('-', $ids) as $id )
			{
				$this->Mobject->id = $id;
				
				if ( $this->Mobject->get() )
				{
					# данные дома (общие для всех - пишем только первый раз)
					if ( !isset($building) )
					{
						$building = '<div class="building">
							<div class="addr">'.$this->Mobject->building['adr'].'</div>
							<div class="info">
								<span>Дом:</span> '.$this->Mobject->building['house_type'].' / '.$this->Mobject->building['material'].'
							</div>
						</div>';
					}
					
					# данные самого объекта
					$objects[] = '<div class="object">
						<div class="title">
							'.$this->Mobject->data['rooms'].'х-комн. квартира
						</div>
						<div class="param">
							<span>Площадь:</span> 
							'.($this->Mobject->data['space_total'] ? $this->Mobject->data['space_total'] : '-').' / 
							'.($this->Mobject->data['space_living'] ? $this->Mobject->data['space_living'] : '-').' / 
							'.($this->Mobject->data['space_kitchen'] ? $this->Mobject->data['space_kitchen'] : '-').' кв.м
						</div>
						<div class="param">
							<span>Этаж:</span> '.$this->Mobject->data['floor'].' / '.$this->Mobject->building['storey'].'
						</div>
						<div class="price">
							<span>Цена:</span> '.$this->Mobject->data['price'].' р.
						</div>
						<div class="link">
							<a href="/flat/info/'.$this->Mobject->id.'" target="_blank">подробнее</a>
						</div>
					</div>';
				}
			}
			
			# если не найдено ни одного объекта
			if ( !count($objects) )
			{
				echo '-';
				return;
			}
			
			# размеры балуна
			$w = count($objects) > 2 ? 195 : 180;
			$h = count($objects) == 1 ? 165 : 290;
			$s = count($objects) > 2 ? 'overflow-y: scroll;' : '';
			
			echo '<div class="balloonInfo" style="width: '.$w.'px; height: '.$h.'px; '.$s.'">
				'.$building.'
				'.implode(' ', $objects).'
			</div>';
		}


		# районы (полигоны)
		function district($city_id)
		{
			$sql = "select * from district where polygon!='' and city_id = ".(int)$city_id;
			$data['districts'] = $this->db->query($sql)->result_array();
			
			# отображение
			$this->load->view('map/district', $data);
		}

	}

?>
