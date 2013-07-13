<html>
<head>
<title>!!!</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
	<script src="http://api-maps.yandex.ru/1.1/index.xml?key=<?=$key?>" type="text/javascript"></script>
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
		

		// Добавляет объекты в диспетчер
//		objManager.add(getPoints(1,"default#whitePoint"), 1, 7);
//		objManager.add(getPoints(10, "default#redPoint"), 8, 10);

		// создание метки приближения
		function makePointZoom(data)
		{
			// сама метка
			var pointZoom = new YMaps.GeoPoint ( data.lng, data.lat );
			var pointZoomData = {style:styleZoom,hasHint:1};
