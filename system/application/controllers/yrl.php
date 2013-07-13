<?php
class Yrl extends Controller {

	function __construct()
	{
		parent::Controller();
	}

	function index()
	{
		# делаем XML
		${'realty-feed'} = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><realty-feed/>');
		${'realty-feed'}['xmlns'] = 'http://webmaster.yandex.ru/schemas/feed/realty/2010-06';
		# дата создания документа
		${'realty-feed'}->{'generation-date'} = $this -> date_ISO();

		# поиск объявлений
		$sql = "select 
					o.id, o.type, o.house_id, o.cdate, o.edate, o.rooms, 
					o.space_total, o.space_living, o.space_kitchen, o.floor, o.info, 
					o.seller, o.name, o.phone, o.email, o.price, o.description,
					concat(s.type, '. ', s.name, ', д. ', h.num) as adr, 
					h.year, h.material, h.storey, d.name as district, d.city_id,
					h.yaLat as lat, h.yaLng as lng, ht.name as house_type
				from nd_objects o, house h, street s, district d, house_type ht
				where o.cdate > CURDATE() - INTERVAL '1' MONTH
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

			# распаковываем информацию по объявлению
			$r['info'] = json_decode($r['info'], true);

			# добавляем ветку объявления
			$offer = ${'realty-feed'}->addChild('offer');


# ------------------------------------------------------------------------------
# Общие параметры
# ------------------------------------------------------------------------------

			# id объявления в базе партнера
			$offer['internal-id'] = $r['id'];

			# тип сделки («продажа», «аренда»)
			if ($r['type'] == 'sale') $offer->type = 'продажа';
			if ($r['type'] == 'rent') $offer->type = 'аренда';

			# тип недвижимости (рекомендуемое значение — «жилая»)
			$offer->{'property-type'} = 'жилая';

			# категория объекта («комната», «квартира»)
			$offer->category = 'квартира';

			# URL страницы с объявлением
			$offer->url = 'http://pn66.ru/flat/info/'.$r['id'];

			# дата создания объявления
			$offer->{'creation-date'} = $this->date_ISO($r['cdate']);

			# оплаченное объявление (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«˗»)
			$offer->{'payed-adv'} = 'false';

			# объявление добавлено вручную (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«˗»)
			$offer->{'manually-added'} = 'true';


# ------------------------------------------------------------------------------
# Местоположение
# ------------------------------------------------------------------------------

			# страна
			$offer->location->country = 'Россия';

			# субъект РФ
			$offer->location->region = 'Свердловская область';

			# название города, деревни, поселка и т.д.
			$offer->location->{'locality-name'} = 'Екатеринбург';

			# улица, дом
			$offer->location->address = $r['adr'];

			# географические координаты (широта)
			$offer->location->latitude = $r['lat'];

			# географические координаты (долгота)
			$offer->location->longitude = $r['lng'];

			# неадминистративный район города или о
			$offer->location->{'non-admin-sub-locality'} = $r['district'];


# ------------------------------------------------------------------------------
# Информация о продавце
# ------------------------------------------------------------------------------

			# имя агента/продавца
			$offer->{'sales-agent'}->name = $r['name'];

			# телефон агента/продавца
			$offer->{'sales-agent'}->phone = $r['phone'];

			# тип продавца («owner», «agency»)
			if ( $r['seller'] ) $offer->{'sales-agent'}->category = $r['seller'];


# ------------------------------------------------------------------------------
# Информация о сделке
# ------------------------------------------------------------------------------

			# Цена (сумма указывается без пробелов)
			$offer->price->value = $r['price'];

			# Валюта, в которой измеряется стоимость ( «RUR», «RUB», «EUR», «USD»)
			$offer->price->currency = 'RUR';


# ------------------------------------------------------------------------------
# Информация об объекте
# ------------------------------------------------------------------------------

			# фотография (может быть несколько тегов)
			foreach ( $this->Mobject->get_foto() as $f )
			{
				# добавляем стоку фотки
				$image = $offer -> addChild ( 'image', 'http://pn66.ru/foto/'.$r['id'].'/large/'.$f );			}

			# ремонт (рекомендуемые значения — «евро», «дизайнерский»)
			if ( $r['info']['renovation'] ) $offer->renovation = $r['info']['renovation'];

			# дополнительная информация (описание в свободной форме, оставленное подателем объявления)
			if ( $r['description'] ) $offer->description = $r['description'];

			# общая площадь
			$offer->area->value = $r['space_total'];
			$offer->area->unit = 'кв.м';

			# жилая площадь (при продаже комнаты — площадь комнаты)
			if ( $r['space_living'] )
			{
				$offer->{'living-space'}->value = $r['space_living'];
				$offer->{'living-space'}->unit = 'кв.м';
			}

			# площадь кухни
			if ( $r['space_kitchen'] )
			{
				$offer->{'kitchen-space'}->value = $r['space_kitchen'];
				$offer->{'kitchen-space'}->unit = 'кв.м';
			}


# ------------------------------------------------------------------------------
# Описание жилого помещения
# ------------------------------------------------------------------------------

			# общее количество комнат в квартире
			$offer -> rooms = $r['rooms'];

			# наличие телефона (строго ограниченные значения — «да»/ «нет», «true»/ «false», «1»/ «0», «+»/ «˗»)
			if ( $r['info']['phone'] ) $offer->phone = ( $r['info']['phone'] == 'Есть' ? 'true' : 'false' );

			# наличие мебели (строго ограниченные значения — «да»/ «нет», «true»/«false», «1»/ «0», «+»/«˗»)
			if ( $r['info']['furniture'] ) $offer->{'room-furniture'} = ( $r['info']['furniture'] == 'Есть' ? 'true' : 'false' );

			# тип балкона (рекомендуемые значения — «балкон», «лоджия», «2 балкона», «2 лоджии»)
			if ( $r['info']['balcony'] ) $offer->balcony = $r['info']['balcony'];

			# тип санузла (рекомендуемые значения — «совмещенный», «раздельный», «2»)
			if ( $r['info']['bathroom'] ) $offer->{'bathroom-unit'} = $r['info']['bathroom'];

			# вид из окон (рекомендуемые значения — «во двор», «на улицу»)
			if ( $r['info']['window'] ) $offer->{'window-view'} = $r['info']['window'];

			# этаж
			$offer->floor = $r['floor'];


# ------------------------------------------------------------------------------
# Описание здания
# ------------------------------------------------------------------------------

			# общее количество этажей в доме
			$offer -> {'floors-total'} = $r['storey'];

			# тип дома (рекомендуемые значения — «кирпичный», «монолит», «панельный»)
			$offer -> {'building-type'} = $r['material'];

			# серия дома
			if ( $r['house_type'] ) $offer -> {'building-series'} = $r['house_type'];

			# год постройки
			if ( $r['year'] ) $offer -> {'built-year'} = $r['year'];

		}

		# сохраняем в файл
		${'realty-feed'}->asXML('/home/srv32537/srv11684/pn66/yrl.xml');
		echo $this->date_ISO()."\n";
		return;

		# отображение
		Header ( 'Content-type: text/xml' );
		echo ${'realty-feed'} -> asXML();
	}

	function date_ISO ( $date = '' )
	{
		if ( !$date ) $date = date('Y-m-d H:i:s');
		return str_replace(' ', 'T', $date).'+04:00';
	}


}
?>
