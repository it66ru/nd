<?php

class Sitemaps extends Controller
{

	# список агентств
	function agency()
	{
		# делаем XML
		$urlset = new SimpleXMLElement("<urlset/>");
		$urlset['xmlns'] = 'http://www.sitemaps.org/schemas/sitemap/0.9';

		$this->db->select('id');
		$query = $this->db->get('agency');
		# перебираем все агентства
		foreach ( $query -> result_array() as $r )
		{
			# добавляем ветку ИНФО
			$url = $urlset -> addChild('url');
			$url -> loc = 'http://pn66.ru/agency/info/'.$r['id'];
			$url -> changefreq = 'weekly';
			$url -> priority = 0.5;

			# добавляем ветку ОБЪЕКТЫ
			$url = $urlset -> addChild('url');
			$url -> loc = 'http://pn66.ru/agency/objects/'.$r['id'];
			$url -> changefreq = 'weekly';
			$url -> priority = 0.8;

			# добавляем ветку ОТЗЫВЫ
			$url = $urlset -> addChild('url');
			$url -> loc = 'http://pn66.ru/agency/comments/'.$r['id'];
			$url -> changefreq = 'weekly';
			$url -> priority = 0.5;		}

		# отображение
		Header ( 'Content-type: text/xml' );
		echo $urlset -> asXML();
	}

	# список квартир
	function flat()
	{
		# делаем XML
		$urlset = new SimpleXMLElement("<urlset/>");
		$urlset['xmlns'] = 'http://www.sitemaps.org/schemas/sitemap/0.9';

		$this -> db -> select ( 'id' );
		$this -> db -> where ( 'date_del', '0000-00-00' );
		$query = $this -> db -> get ( 'nd_flat_sale' );

		# перебираем все агентства
		foreach ( $query -> result_array() as $r )
		{
			# добавляем ветку ИНФО
			$url = $urlset -> addChild('url');
			$url -> loc = 'http://pn66.ru/flat/info/'.$r['id'];
			$url -> changefreq = 'weekly';
			$url -> priority = 0.8;
		}

		# отображение
		Header ( 'Content-type: text/xml' );
		echo $urlset -> asXML();
	}

}
?>
