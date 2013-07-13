<?
	$ico = array (
		'<img src="/ico/red.png" width="25" height="30" title="Полнометражка"> ПМ',
		'<img src="/ico/yellow.png" width="25" height="30" title="Малосемейка"> МC',
		'<img src="/ico/orange.png" width="25" height="30" title="Хрущевка"> ХР',
		'<img src="/ico/pink.png" width="25" height="30" title="Брежневка"> БР',
		'<img src="/ico/purple.png" width="25" height="30" title="Пентагон"> ПН',
		'<img src="/ico/blue.png" width="25" height="30" title="Улучш. планировка"> УП',
		'<img src="/ico/green.png" width="25" height="30" title="Спец. планировка"> СП',
		'<img src="/ico/grey.png" width="25" height="30" title="Не известно"> ?',
	);

?>

	<? $this->load->view('head', $head); ?>

	<? $this->load->view('flat/top_object'); ?>

	<div class="clear"></div>

	<ul class="bookmark">
		<? foreach ( $sections as $url => $name ) { ?>
			<? if ( $this->uri->segment(2) == $url ) { ?>
				<li class="current"><?=$name?></li>
			<? } else { ?>
				<li><a href="/flat/<?=$url?>/<?=$this->Mobject->id?>"><?=$name?></a></li>
			<? } ?>
		<? } ?>
	</ul>
<!--
	<div style="margin-bottom:20px;"><a href="">Показать похожие предложения</a></div>
-->

	<script type="text/javascript">
		// Создание обработчика для события window.onLoad
		YMaps.jQuery(function () {
			// Создание экземпляра карты и его привязка к созданному контейнеру
			var map = new YMaps.Map(YMaps.jQuery("#YMapsID")[0]);
			// Установка для карты ее центра и масштаба
			map.setCenter(new YMaps.GeoPoint(<?=$this->Mobject->building['lng']?>, <?=$this->Mobject->building['lat']?>), 15);
			// Добавление элементов управления
			map.addControl(new YMaps.ToolBar());
			map.addControl(new YMaps.Zoom());
			map.enableScrollZoom();
			map.setMaxZoom(17);
			map.setMinZoom(13);
			// Создание метки с созданным стилем и добавление ее на карту
			var placemark = new YMaps.Placemark(new YMaps.GeoPoint(<?=$this->Mobject->building['lng']?>, <?=$this->Mobject->building['lat']?>));
			placemark.description = "<?=$this->Mobject->building['adr']?>";
			map.addOverlay(placemark);
		});
	</script>
	<div id="YMapsID" style="width:730px;height:400px; border:1px solid #999"></div>

<!--
	<div class="map_legend"><?=implode(' &nbsp; ',$ico);?></div>
-->


	<? $this->load->view('foot'); ?>
