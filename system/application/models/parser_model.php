<?php

class Parser_model extends CRUD_Model {

	public function __construct()
	{
		parent::__construct('parse');
	}


	# добавление нового URL
	public function insertUrl($url)
	{
		$path = parse_url($url);
		$source = $path['host'];
		
		$sql = "insert ignore
					into ".$this->table." (source, url, cdate)
					values ('".$source."', '".$url."', now())";
		$this->db->query($sql);
	}



	#==================================================================#
	#===== получение списков ==========================================#
	#==================================================================#

	# www.estate.spb.ru
	public function getListEstate($page = 0)
	{
		$result = array();
		$siteDomain = "http://www.estate.spb.ru";
		$url = "/apartments/?apa_filter=%CF%EE%E8%F1%EA&list=" . $page;
		
		$html = file_get_contents($siteDomain . $url);
		$html = iconv("windows-1251", "UTF-8", $html);
		
		preg_match_all("/<td class=\"pk\"><a href='\/apartments\/([0-9]+)\/index\.html/", $html, $matches);
		
		$idarray = array_unique($matches[1]);
		
		foreach ($idarray as $id)
			$result[] = $siteDomain . "/apartments/" . $id . "/index.html";
		
		return $result;
	}

	# www.rosrealt.ru
	public function getListRosrealt($page = 1)
	{
		$result = array();
		$siteDomain = "http://www.rosrealt.ru";
		$url = "/Sankt_Peterburg/kvartira/prodam/" . $page;
		
		$html = file_get_contents($siteDomain . $url);
		$html = iconv("windows-1251", "UTF-8", $html);
		
		preg_match_all("/\/Sankt_Peterburg\/kvartira\/([0-9]+)'/", $html, $matches);
		
		$idarray = array_unique($matches[1]);
		
		foreach ($idarray as $id)
			$result[] = $siteDomain . "/Sankt_Peterburg/kvartira/" . $id;
		
		return $result;
	}

	# www.eip.ru
	public function getListEip($page = 1)
	{
		$result = array();
		$siteDomain = "http://www.eip.ru";
		$url = "/view/flats/?city=118&&sortby=data%20desc&p=" . $page;
		
		$html = file_get_contents($siteDomain . $url);
		$html = iconv("windows-1251", "UTF-8", $html);
		
		preg_match_all("/(view\/inf[a-z-]+\/([0-9]+))\//", $html, $matches);
		$links = array_unique($matches[1]);
		$idarray = array_unique($matches[2]);        
		
		foreach ($idarray as $key => $id)
			$result[] = $siteDomain.'/'.$links[$key].'/?ncity=118';
		
		return $result;
	}



	#==================================================================#
	#===== получение данных со страницы ===============================#
	#==================================================================#

	# www.eip.ru
	public function getAdvertEip($url)
	{
		$advert = new stdClass();
		
		$html = file_get_contents($url);
		$html = iconv("windows-1251", "UTF-8", $html);
		
		preg_match("/<td>Комнат в квартире<\/td><td.*?>(.*?)<\/td>/", $html, $matches);
		$advert->room_count = (int) $matches[1];
		
		preg_match("/<td>Район<\/td><td.*?>(.*?)<\/td>/", $html, $matches);
		$advert->rayon = $matches[1];
		
		preg_match("/<td>Адрес<\/td><td.*?>(.*?)<\/td>/", $html, $matches);
		$advert->address = $matches[1];
		
		preg_match("/<td>Метро<\/td><td.*?>(.*?)<\/td>/", $html, $matches);
		$advert->metro = $matches[1];
		
		preg_match("/<td>Стоимость квартиры<\/td><td.*?>(.*?)<\/td>/", $html, $matches);
		$advert->price = $matches[1];
		
		preg_match("/<td>Площадь.*?<\/td><td.*?>(.*?)<\/td>/", $html, $matches);
		$advert->square = $matches[1];
		
		preg_match("/<td>Этаж.*?<\/td><td.*?>(.*?)<\/td>/", $html, $matches);
		$advert->floor = (int) $matches[1];
		
		preg_match("/<td>Дополнительная.*?<\/td><td.*?>(.*?)<\/td>/", $html, $matches);
		$advert->extra = strip_tags($matches[1]);
		
		preg_match("/<td>Контактная.*?<\/td><td.*?>(.*?)<\/td>/", $html, $matches);
		$advert->contacts = strip_tags($matches[1]);
		
		return $advert;
	}

	# www.rosrealt.ru
	public function getAdvertRosrealt($url)
	{
		$advert = new stdClass();
		
		$html = file_get_contents($url);
		$html = iconv("windows-1251", "UTF-8", $html);
		
		preg_match("/<p class='obj_profile'>Общая стоимость:<br><b>(.*?)<\/b>/", $html, $matches);
		$advert->price = preg_replace("/[^0-9]/", '', $matches[1]);
		
		preg_match("/Общая площадь: <b>(.*?)кв\.м\.<\/b>/", $html, $matches);
		$advert->square = preg_replace("/[^0-9\.]/", '', $matches[1]);
		
		preg_match("/Контактная информация<br><b>(.*?)<\/b>/", $html, $matches);
		$advert->contacts = $matches[1];
		
		preg_match("/<b>([0-9]?)-комнатная<\/b>/", $html, $matches);
		$advert->rooms = $matches[1];
		
		preg_match("/<td width=100%><p>(.*?)<\/p>/", $html, $matches);
		$advert->content = strip_tags($matches[1]);
		
		preg_match("/<td width=100%><p>(.*?)<\/td>/si", $html, $matches);
		
		preg_match_all("/<img src='(.*?)'/", $matches[1], $matches);
		$advert->images = $matches[1];
		
		return $advert;
	}

	# www.estate.spb.ru
	public function getAdvertEstate($url)
	{
		$siteDomain = "http://www.estate.spb.ru";
		$advert = new stdClass();
		
		$html = file_get_contents($url);
		$html = iconv("windows-1251", "UTF-8", $html);
		
		preg_match("/<td.*?>Цена.*?<\/td><td.*?>(.*?)<\/td>/", $html, $matches);
		$advert->price = preg_replace("/[^0-9]/", '', $matches[1]);
		
		preg_match("/<td.*?>Комнат.*?<\/td><td.*?>([0-9]+)<\/td>/", $html, $matches);
		$advert->rooms = (int)$matches[1];
		
		preg_match("/<td.*?>Метро.*?<\/td><td.*?>(.*?)<\/td>/", $html, $matches);
		$advert->metro = $matches[1];
		
		preg_match("/<td.*?>Общая площадь.*?<\/td><td.*?>(.*?)<\/td>/", $html, $matches);
		$advert->square = preg_replace("/[^0-9\.]/", '', $matches[1]);
		
		preg_match_all("/<img src='(\/img\/uploads\/small.*?jpg)'/", $html, $matches);
		
		foreach($matches[1] as $img)
			$advert->images[] = $siteDomain.str_replace("small", "normal", $img);
		
		return $advert;
	}

}

?>
