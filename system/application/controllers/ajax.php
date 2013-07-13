<?php

class Ajax extends Controller {

	function __construct()
	{
		parent::Controller();

		# параметры запроса
		$url = parse_url($_SERVER['REQUEST_URI']);
		if ( isset($url['query']) )
			parse_str($url['query'], $this->_GET);
		else
			$this->_GET = array();
	}

	# поиск улиц
	function get_street()
	{
		if ( !$this->_GET['term'] ) return;
		
		$name = $this->db->escape_str($this->_GET['term']);
		
		$SQL = "SELECT s.id, CONCAT(s.type, '. ', s.name) as label
				FROM street s
				WHERE CONCAT(s.type, '. ', s.name) LIKE '%".$name."%'
					and s.city_id = ".$this->config->item('city_id')."
				ORDER BY name";
		$query = $this->db->query($SQL);
		$data = $query->result_array();
		echo json_encode($data);
	}

	# поиск зданий
	function get_building($street = 0)
	{
		if ( !$street || !$this->_GET['term'] ) return;
		
		$name = $this->db->escape_str($this->_GET['term']);
		
		$SQL = "SELECT h.id, h.num as label FROM house h
			WHERE h.num LIKE '%".$name."%' AND street_id = ".$street."
			ORDER BY h.num*1, h.num";
		$query = $this->db->query($SQL);
		
		$data = $query->result_array();
		echo json_encode($data);
	}


	function ui()
	{
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>jQuery UI Autocomplete - Remote JSONP datasource</title>
	<link rel="stylesheet" href="/css/ui/base.css">
	<link rel="stylesheet" href="/css/ui/jquery-ui.css">
	<link rel="stylesheet" href="/css/ui/parseTheme.css">
	<link rel="stylesheet" href="/css/ui/ui.theme.css">
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

	<!--script src="http://jqueryui.com/jquery-1.7.2.js"></script-->
	<script src="/js/jquery.ui.core.js"></script>
	<script src="/js/jquery.ui.widget.js"></script>
	<script src="/js/jquery.ui.position.js"></script>
	<script src="/js/jquery.ui.autocomplete.js"></script>
	
	<style>
	.ui-autocomplete-loading { background: white url('images/ui-anim_basic_16x16.gif') right center no-repeat; }
	#city { width: 25em; }
	</style>
	<script>
	$(function() {
		$('#street').autocomplete({
			source: '/ajax/get_street', 
			minLength: 2,
			select: function(event, ui) {
				$('#building').autocomplete('option', 'source', '/ajax/get_building/'+ui.item.id);
			},
		});
		$('#building').autocomplete({
			minLength: 1,
			select: function(event, ui) {
				$('#house_id').val(ui.item.id);
			},
		});
	});
	</script>
</head>
<body>

<div class="demo">

<div class="ui-widget">
	<label for="city">Your city: </label>
	<input id="street"/>
	
	Powered by <a href="http://geonames.org">geonames.org</a>
</div>

	<input id="building"/>
	<input id="house_id"/>
	
search

<div class="ui-widget" style="margin-top:2em; font-family:Arial">
	Result:
	<div id="log" style="height: 200px; width: 300px; overflow: auto;" class="ui-widget-content"></div>
</div>

</div><!-- End demo -->





</body>
</html>



<?
	
	}

}
?>
