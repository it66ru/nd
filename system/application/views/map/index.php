<html>
<head>
<title>PN66.ru - Поиск недвижимости в Екатеринбурге</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
	<script src="http://api-maps.yandex.ru/1.1/index.xml?key=<?=$key?>" type="text/javascript"></script>

	<link type="text/css" href="/css/ui-slider.css" rel="stylesheet" media="all" />
	<script type="text/javascript" src="/js/jquery.ui-slider.js"></script>
	<script type="text/javascript" src="/js/jquery.main.js"></script>
	
	
	<script type="text/javascript">
	
	var $ = YMaps.jQuery;
	var count = 0;
	var loadTiles = new Array();
	
	$(function () {
		var map = new YMaps.Map($("#YMap"));
		
		map.setCenter(new YMaps.GeoPoint(60.60, 56.837), 14);
		map.enableScrollZoom();
		map.setMaxZoom(17);
		map.setMinZoom(13);
		
		map.addControl(new YMaps.TypeControl());
		map.addControl(new YMaps.ToolBar());
		map.addControl(new YMaps.SmallZoom({noTips:true}));
		map.addControl(new YMaps.ScaleLine());
		
		// стиль значка для увеличения
		var styleZoom = new YMaps.Style();

		// Создает стиль значка метки
		styleZoom.iconStyle = new YMaps.IconStyle();
		styleZoom.iconStyle.href = "/ico/zoom.png";
		styleZoom.iconStyle.size = new YMaps.Point(36, 37);
		styleZoom.iconStyle.offset = new YMaps.Point(-18, -18);
		
		// Создает диспетчер объектов и добавляет его на карту
		var objManager = new YMaps.ObjectManager();
		map.addOverlay(objManager);
		
		// прогружаем значки на видимую область
		getData();
		
		// любое изменение границ карты (сдвиг, масштаб)
		YMaps.Events.observe ( map, map.Events.BoundsChange,  getData );
		
		function getData()
		{
		//	alert (map.getZoom())
			
			var z = map.getZoom();
			var tilesDiff = new Array();
			
			// получаем тайлы, которые нужно подгрузить
			if ( loadTiles[z] == undefined )
			{ 
				loadTiles[z] = new Array();
				tilesDiff = tiles();
			}
			else 
			{
				tilesDiff = array_diff(tiles(),loadTiles[z]);
			}
			
			// перебираем их все
			tilesDiff.forEach (
				function (item) 
				{ 
					tile = item.split(',');
					$.ajax ({
						url: "/map/search/"+z+"/"+tile[0]+"/"+tile[1]+"/",
						type: "POST",
						data: <?=json_encode($js_params)?>,
						dataType: "json",
						success: function(msg) {
							
							// перебираем все подтайлы
							for ( subTile in msg.response.results )
							{
								var results = msg.response.results[subTile];
								
								// если подтайл - список домов
								if ( results.count == undefined )
								{
									// прогружаем все метки
									makePoints(results);
									
									// добавляем этот подтайл в список загруженных
									var subTileInfo = /z(\d+)x(\d+)y(\d+)/.exec(subTile);
									if ( loadTiles[subTileInfo[1]] == undefined ) loadTiles[subTileInfo[1]] = new Array();
									loadTiles[subTileInfo[1]].push(subTileInfo[2]+','+subTileInfo[3]);
								}
								// если подтайл - группа
								else
								{
									// добавляем метку на увеличение
									makePointZoom(results);
								}
							}
							
							// добавляем текущий тайл в список загруженных
							loadTiles[msg.request.coordinates.zoom].push(msg.request.coordinates.tile);
						},
						error: function(msg) {
							alert('Ошибка');
						}
					});
				} 
			);
			
			$("#info").ajaxComplete(function(request, settings){
				$(this).html('<pre>'+dump(loadTiles)+'</pre>');
			});
			
			$("#loader").ajaxStart(function(){
				$(this).show();
			});
			
			$("#loader").ajaxStop(function(){
				$(this).hide();
			});


			// objManager.add(getPoints(100,"default#violetPoint"), 11, 16);
		
			
		}
		
		// создание метки приближения
		function makePointZoom(data)
		{
			// сама метка
			var pointZoom = new YMaps.GeoPoint ( data.lng, data.lat );
			var pointZoomData = {style:styleZoom,hasHint:1};
			var pz = new YMaps.Placemark(pointZoom, pointZoomData);
			pz.name = data.count+' объектов';
			
			// приближение карты
			YMaps.Events.observe(pz,pz.Events.Click, function (mark, event) {
				map.setCenter(mark.getGeoPoint(), map.getZoom()+1);
			});
			
			// добавляем метку только на текущий зум
			objManager.add(pz, map.getZoom(), map.getZoom(), 24);
		}
		
		// делает точки из полученых данных
		function makePoints(data)
		{
			var arrPoints = [];
			
			// перебираем все дома
			for ( b in data )
			{
				var bild = data[b];
				var point = new YMaps.GeoPoint ( data[b].lng, data[b].lat );
				var pointData = {style:"default#"+data[b].ico+"SmallPoint",hasHint:1};
				var pm = new YMaps.Placemark(point, pointData);
				pm.setBalloonContent("<img src='/img/ajax_loader.gif'>");
				pm.id = data[b].id;
				pm.obj = [];
				for ( i in data[b].obj ) pm.obj.push(data[b].obj[i].id);
				
				// всплывающая подсказка
				pm.name = data[b].obj.length == 1 ? data[b].obj[0].kk + "к - " + data[b].obj[0].pl + " кв.м <br/>" + data[b].obj[0].pr + " р." : "Предложений: " + data[b].obj.length;
				
				// открытие балуна
				YMaps.Events.observe(pm,pm.Events.BalloonOpen, function (pm, event) {
					$.ajax ({
						url: "/map/objects/"+pm.obj.join('-'),
						success: function(msg) {
							pm.setBalloonContent(msg);
						},
						error: function(msg) {
							alert('Ошибка');
						}
					});
				});
				
				// добавляем метку (от текущего зума до последнего)
				objManager.add(pm, map.getZoom());
			}
			
			return;
		}
		
		// Генерирует заданное количество меток
		function getPoints ( count, sKey )
		{
			var arrPoints = [],
				bounds = map.getBounds(),
				pointLb = bounds.getLeftBottom(),
				span = bounds.getSpan();
			
			// Размещает метки случайным образом в текущей видимой области карты
			for ( var i = 0; i < count; i++ )  
			{
				// координаты
				var point = new YMaps.GeoPoint ( 
					pointLb.getLng() + span.x * Math.random(), 
					pointLb.getLat() + span.y * Math.random() );
					
				// данные метки
				var pointData = {style:sKey};
				
				// метка
				var pm = new YMaps.Placemark(point, pointData);
				
				// добавляем в общий массив
				arrPoints.push(pm);
			}
			
			// возвращаем весь массив
			return arrPoints;
		}
		
		// список тайлов текущей области
		function tiles()
		{
			// область карты
			var b = map.getBounds();
			var tiles = new Array();
			
			// левый верхний
			var tLT = map.tileCoordinates.fromPixels (map.coordSystem.fromCoordPoint(map.getBounds().getLeftTop()), map.getZoom());
			var tRB = map.tileCoordinates.fromPixels (map.coordSystem.fromCoordPoint(map.getBounds().getRightBottom()), map.getZoom());
			
			// перебираем все
			for ( var x = tLT.number.x; x <= tRB.number.x; x++ )
				for ( var y = tLT.number.y; y <= tRB.number.y; y++ )
					tiles.push(x+","+y);
			
			return(tiles);
		}
	})

	
function dump(arr,level)
{
	var dumped_text = "";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if (typeof(arr) == 'object') { //Array/Hashes/Objects 
		for (var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}

	function array_diff (array) 
	{
		var arr_dif = [], i = 1, argc = arguments.length, argv = arguments, key, key_c, found=false;
		for ( key in array ){
		
		for (i = 1; i< argc; i++)
		{
			found = false;
			for (key_c in argv[i])
			{
				if (argv[i][key_c] == array[key]) 
				{
					found = true;
					break;
				}
			}
			
			if (!found) 
				// arr_dif[key] = array[key];
				arr_dif.push(array[key]);
		}
	}
	
	return arr_dif;
}

	
	 
	
</script>
		
<style>
	* {
		margin: 0;
		padding: 0;
		border: none;
		font: 12px Arial;
	}
	#search {
		width: 220px;
		height: 240px;
		float: left;
		position: absolute;
		z-index: 10;
		border: 1px solid #aaa;
		top: 170px;
		left: 20px;
		background: rgba(230, 230, 230, 0.3);
	}
	.search_element {
		padding: 10px;
		width: 200px;
	}
	.search_element input {
		text-align: center;
		width: 40px;
		border: 1px solid #999;
	}
	.search_element .slider {
		margin-top: 10px;
	}
	.search_element .checkbox
	{
		width: 20px;
		display: inline-block;
		text-align: center;
		border: 1px solid #999;
		cursor: pointer;
	}
	.search_element .checkbox input
	{
		display: none;
	}
	.search_element .checked
	{
		background-color: #fff;
	}
	.search_element .title
	{
		width: 70px;
		font-weight: bold;
		color: #333;
		display: inline-block;
	}
	input.button {
		border: 1px solid #999;
		background-color: #ddd;
		cursor: pointer;
		margin: 10px;
		padding: 2px 10px;
	}
	#loader {
		float: right;
		margin: 10px;
	}
	#YMap {
		width: 100%;
		height: 100%;
		float: left;
	}
	
	div.balloonInfo {
		padding: 7px;
	}
	div.balloonInfo div.building {
	}
	div.balloonInfo > div > div {
		margin-bottom: 5px;
		white-space: nowrap;
	}
	div.balloonInfo div.building div.addr {
		font-size: 16px;
		font-weight: bold;
	}
	div.balloonInfo div.building div.info {
	}
	div.balloonInfo div.building div.info span {
		color: #999;
	}
	
	div.balloonInfo div.object {
		margin-top: 15px;
	}
	div.balloonInfo > div.object > div > span {
		color: #999;
		width: 60px;
		display: inline-block;
		text-align: right;
	}
	div.balloonInfo div.object div.title {
		font-size: 18px;
	}
	div.balloonInfo div.object div.param {
		font-size: 14px;
	}
	div.balloonInfo div.object div.price {
		font-size: 16px;
		font-weight: bold;
		color: #b00;
	}
	div.balloonInfo div.object div.link {
		text-align: right;
	}
</style>


	
</head>
<body>
	<div id="search">
		<form method="get">
			<div class="search_element">
				<span class="title">Комнат:</span>
				<? for ( $i=1; $i<=5; $i++ ) { ?>
					<? $chk = in_array($i,$search['room']) ? 'checked' : ''; ?>
					<span class="checkbox <?=$chk?>">
						<?=$i?> <input type="checkbox" name="room[]" value="<?=$i?>"  <?=$chk?>/>
					</span>
				<? } ?>
			</div>
			<div class="search_element">
				<span class="title">Цена:</span>
				<label for="minPrice">от</label> <input type="text" name="minPrice" id="minPrice" value="<?=$search['minPrice']?>" maxlength="4"/> &nbsp;
				<label for="maxPrice">до</label> <input type="text" name="maxPrice" id="maxPrice" value="<?=$search['maxPrice']?>" maxlength="4"/>
				<div class="slider" id="sliderPrice"></div>
			</div>
			<div class="search_element">
				<span class="title">Площадь:</span>
				<label for="minSpace">от</label> <input type="text" name="minSpace" id="minSpace" value="<?=$search['minSpace']?>" maxlength="2"/> &nbsp;
				<label for="maxSpace">до</label> <input type="text" name="maxSpace" id="maxSpace" value="<?=$search['maxSpace']?>" maxlength="2"/>
				<div class="slider" id="sliderSpace"></div>
			</div>
			<div class="search_element">
				<span class="title">Этаж:</span>
				<label for="minFloor">от</label> <input type="text" name="minFloor" id="minFloor" value="<?=$search['minFloor']?>" maxlength="2"/> &nbsp;
				<label for="maxFloor">до</label> <input type="text" name="maxFloor" id="maxFloor" value="<?=$search['maxFloor']?>" maxlength="2"/>
				<div class="slider" id="sliderFloor"></div>
			</div>
			<div style="float:left">
				<input type="submit" value="Найти" class="button"/>
			</div>
			<div id="loader" style="display:none">
				<img src="/img/ajax-loader.gif"/>
			</div>
		</form>
	</div>
	<div id="YMap"></div>
	<div id="info" style="background:#eef; border:1px solid #f00; float:left; margin-left:10px; display:none;"></div>
	<? $this->load->view('counter_metrika'); ?>
</body>
</html>
