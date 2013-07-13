<?php
class export extends Controller {

	function __construct()
	{
		parent::Controller();
	}

	function index()
	{
		# делаем XML
		$root = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><root/>');

		# поиск объявлений
		$sql = "select 
					o.id, o.type, o.house_id, o.cdate, o.edate, o.rooms, 
					o.space_total, o.space_living, o.space_kitchen, o.floor, o.info, 
					o.seller, o.name, o.phone, o.email, o.price, o.description,
					concat(s.type, '. ', s.name) as street, h.num as house,
					h.year, h.material, h.storey, d.name as district, d.city_id,
					h.yaLat as lat, h.yaLng as lng, ht.name as house_type
				from nd_objects o, house h, street s, district d, house_type ht
				/* where o.cdate between curdate() - interval '1' month and curdate() - interval '1' week */
				where o.cdate < curdate() - interval '1' month
					and o.status = 'approved'
					and o.house_id = h.id
					and h.street_id = s.id
					and h.district_id = d.id
					and h.house_type_id = ht.id
				order by 
					o.id desc";

		# перебираем все строки
		foreach ( $this->db->query($sql)->result_array() as $r )
		{
			$this->Mobject->id = $r['id'];

			# добавляем ветку объявления
			$offer = $root->addChild('offer');

			# id объекта
			$offer->id = $r['id'];

			# Вид сделки
			$offer->operation_type = 'продажа';

			# Тип объекта
			$offer->object_type = 'квартира';

			# Дата помещения объекта в базу
			$offer->create_date = $r['cdate'];

			# Дата обновления объекта
			$offer->last_updated = $r['edate'];

			# Регион
			$offer->state = 'Свердловская область';

			# Населенный пункт
			$offer->town = 'Екатеринбург';

			# Район города
			$offer->township = $r['district'];

			# Улица
			$offer->street = $r['street'];

			# Номер дома
			$offer->house = $r['house'];

			# Количество комнат
			$offer->rooms = $r['rooms'];

			# Описание объекта
			$offer->description = $r['description'];

			# Цена объекта
			$offer->price = $r['price'];

			# Вид цены
			$offer->priceunit = 'total';

			# Валюта
			$offer->currency = 'RUR';

			# Этаж
			$offer->floor = $r['floor'];

			# Этажность
			$offer->floors_total = $r['storey'];

			# Общая площадь
			$offer->total_area = $r['space_total'];

			# Жилая площадь
			$offer->living_area = $r['space_living'];

			# Площадь кухни
			$offer->kitchen_area = $r['space_kitchen'];

			# Контактный телефон
			$offer->phone = $r['phone'];

			# Контактное лицо
			$offer->name = $r['name'];

			# Url объекта
			$offer->url = 'http://pn66.ru/flat/info/'.$r['id'];

			# фотография (может быть несколько тегов)
			foreach ( $this->Mobject->get_foto() as $f )
			{
				# добавляем стоку фотки
				$image = $offer -> addChild ( 'image', 'http://pn66.ru/foto/'.$r['id'].'/large/'.$f );
			}
		}

		# сохраняем в файл
		$root->asXML('export_ners.xml');
		echo 'ok';
		return;

		# отображение
		Header ( 'Content-type: text/xml' );
		echo $root->asXML();
	}


}
?>
