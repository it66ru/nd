
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Примеры. Геопоиск.</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
	<script src="http://yandex.st/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		// Как только будет загружен API и готов DOM, выполняем инициализацию
		ymaps.ready(init);
		
		// загруженные квадраты
		var loadBounds = [];

		function init () {
			// Создание экземпляра карты и его привязка к контейнеру с
			//  заданным id ("map")
			var map = new ymaps.Map('map', {
				center: [56.837, 60.60],
				zoom: 15,
				behaviors: ['default', 'scrollZoom'],
			},{
				maxZoom: 18,
				minZoom: 13,
			});
			

			
			map.controls.add('zoomControl', { top: 75, left: 5, minZoom:10 });
			
			// Создаем кластеризатор
			var cluster = new ymaps.Clusterer();
			// задаем размер кластера
			cluster.options.set({gridSize: 64});
			// Добавляем кластер на карту.
			map.geoObjects.add(cluster);
			
			// Создаем шаблон для отображения контента хинта.
			var hintGroup = ymaps.templateLayoutFactory.createClass('Предложений: $[properties.count]');
			var hintOnce  = ymaps.templateLayoutFactory.createClass('$[properties.rooms]к - $[properties.space] кв.м.<br/><b>$[properties.price] р.</b>');
			// Помещаем созданный шаблон в хранилище шаблонов.
			ymaps.layout.storage.add('my#group', hintGroup);
			ymaps.layout.storage.add('my#once', hintOnce);


			
			// изменение видимой области карты
			map.events.add('boundschange', getData);
			
			getData();
			
			function getData() {
				

				
				boundsDiff = array_diff(bounds(),loadBounds);
				for ( i in boundsDiff )
				{
					addMarkers(boundsDiff[i]);

					
					// добавляем в список загруженных
					loadBounds.push(boundsDiff[i]);
				}
				
				
				
				
				$('#loader').html(loadBounds.join(' = '));
			};
			
		function bounds()
		{
			// область карты
			var bounds = map.getBounds();
			var tiles = [];
			
			var lt = [Math.floor(bounds[0][0]*100), Math.ceil(bounds[1][0]*100)]; 
			var lg = [Math.floor(bounds[0][1]*100), Math.ceil(bounds[1][1]*100)]; 
			
			for ( var lat=lt[0]; lat<lt[1]; lat++ ) {
				for ( var lng=lg[0]; lng<lg[1]; lng++ ) {
					tiles.push(lat+'-'+lng);
				}
			}
			
			return(tiles);
		}


			// Функция, генерирующая случайные координаты
			// в пределах области просмотра карты
			function getRandomCoordinates () {
				return [Math.random() * 0.04 + 56.8, Math.random() * 0.1 + 60.6];
			}

			// добавление меток
			function add()
			{
				var district = [1, 2, 3, 4, 5, 7, 8, 9, 10, 12, 13, 14, 15, 17, 18, 19, 20, 22, 26, 27, 29, 30, 31, 32, 33, 34, 37, 38, 39, 40, 41, 42, 43];
				for ( i in district ) addMarkers(district[i]);
			}

			// добавление меток по районам
			function addMarkers (district) {
				// все метки
				var placemarks = [];
				
				// получаем результаты поиска
				$.ajax ({
					url: '/map/search_all/'+district,
					type: 'POST',
					data: <?=json_encode($js_params)?>,
					dataType: 'json',
					success: function(msg) {
						// перебираем все объекты (дома)
						// alert(msg.results.length);
						for ( num in msg.results )
						{
							var obj = msg.results[num];
							
							// координаты метки
							var point = [obj.lat, obj.lng];
							
							// групповая метка
							if ( obj.c )
							{
								var info  = {count: obj.c};
								var style = {preset: 'twirl#'+obj.i+'DotIcon'};
								var layout = 'my#group';
							}
							// одиночная метка
							else 
							{
								var info  = {
									iconContent: obj.k, 
									rooms: obj.k,
									price: obj.p,
									space: obj.s,
								};
								var style = {preset: 'twirl#'+obj.i+'Icon'};
								var layout = 'my#once';
							}
							
							// новая метка
							var placemark = new ymaps.Placemark (point, info, style);
							
							// Задаем наш шаблон для хинта метки.
							placemark.options.set('hintContentLayout', layout);
							
							// добавляем эту метку в массив меток;
							//placemarks.push(placemark);

							// Добавлеяем массив меток в кластер
							cluster.add(placemark);

						}
					},
					error: function(msg) {
						alert('Ошибка');
					}
				});
				
/*				
				
				
				// Количество меток, которое нужно добавить на карту
				var geometry = [];
				
				var bounds = myMap.getBounds(); // определение области просмотра карты


				var icons = [
					// Метки без содержимого
					'twirl#blueIcon',
					'twirl#darkblueIcon',
					'twirl#darkgreenIcon',
					'twirl#darkorangeIcon',
					'twirl#greenIcon',
					'twirl#greyIcon',
					'twirl#lightblueIcon',
					'twirl#nightIcon',
					'twirl#orangeIcon',
					'twirl#pinkIcon',
					'twirl#redIcon',
					'twirl#violetIcon',
					'twirl#whiteIcon',
					'twirl#yellowIcon',
					'twirl#brownIcon',
					'twirl#blackIcon',
					// Метки без содержимого с точкой в центре.
					'twirl#blueDotIcon',
					'twirl#darkblueDotIcon',
					'twirl#darkgreenDotIcon',
					'twirl#darkorangeDotIcon',
					'twirl#greenDotIcon',
					'twirl#greyDotIcon',
					'twirl#lightblueDotIcon',
					'twirl#nightDotIcon',
					'twirl#orangeDotIcon',
					'twirl#pinkDotIcon',
					'twirl#redDotIcon',
					'twirl#violetDotIcon',
					'twirl#whiteDotIcon',
					'twirl#yellowDotIcon',
					'twirl#brownDotIcon',
					'twirl#blackDotIcon',
					// Тянущиеся метки с текстом.
					'twirl#blueStretchyIcon',
					'twirl#darkblueStretchyIcon',
					'twirl#darkgreenStretchyIcon',
					'twirl#darkorangeStretchyIcon',
					'twirl#greenStretchyIcon',
					'twirl#greyStretchyIcon',
					'twirl#lightblueStretchyIcon',
					'twirl#nightStretchyIcon',
					'twirl#orangeStretchyIcon',
					'twirl#pinkStretchyIcon',
					'twirl#redStretchyIcon',
					'twirl#violetStretchyIcon',
					'twirl#whiteStretchyIcon',
					'twirl#yellowStretchyIcon',
					'twirl#brownStretchyIcon',
					'twirl#blackStretchyIcon',
					// Стандартные значки (пиктограммы).
					'twirl#airplaneIcon',
					'twirl#anchorIcon',
					'twirl#badmintonIcon',
					'twirl#bankIcon',
					'twirl#barIcon',
					'twirl#barberShopIcon',
					'twirl#bicycleIcon',
					'twirl#bowlingIcon',
					'twirl#buildingsIcon',
					'twirl#busIcon',
					'twirl#cafeIcon',
					'twirl#campingIcon',
					'twirl#carIcon',
					'twirl#cellularIcon',
					'twirl#cinemaIcon',
					'twirl#downhillSkiingIcon',
					'twirl#dpsIcon',
					'twirl#dryCleanerIcon',
					'twirl#electricTrainIcon',
					'twirl#factoryIcon',
					'twirl#fishingIcon',
					'twirl#gasStationShopIcon',
					'twirl#gymIcon',
					'twirl#hospitalIcon',
					'twirl#houseIcon',
					'twirl#keyMasterIcon',
					'twirl#mailPostIcon',
					'twirl#metroKievIcon',
					'twirl#metroMoscowIcon',
					'twirl#metroStPetersburgIcon',
					'twirl#metroYekaterinburgIcon',
					'twirl#motobikeIcon',
					'twirl#mushroomIcon',
					'twirl#phoneIcon',
					'twirl#photographerIcon',
					'twirl#pingPongIcon',
					'twirl#restaurauntIcon',
					'twirl#shipIcon',
					'twirl#shopIcon',
					'twirl#skatingIcon',
					'twirl#skiingIcon',
					'twirl#smartphoneIcon',
					'twirl#stadiumIcon',
					'twirl#storehouseIcon',
					'twirl#swimmingIcon',
					'twirl#tailorShopIcon',
					'twirl#tennisIcon',
					'twirl#theaterIcon',
					'twirl#tireIcon',
					'twirl#trainIcon',
					'twirl#tramwayIcon',
					'twirl#trolleybusIcon',
					'twirl#truckIcon',
					'twirl#wifiIcon',
					'twirl#wifiLogoIcon',
					'twirl#workshopIcon',
					'twirl#turnLeftIcon',
					'twirl#turnRightIcon',
					'twirl#arrowDownLeftIcon',
					'twirl#arrowDownRightIcon',
					'twirl#arrowLeftIcon',
					'twirl#arrowRightIcon',
					'twirl#arrowUpIcon'];

				// Создаем нужное количество меток
				for (var i = 0; i < 110; i++) {
					// Генерируем координаты метки случайным образом
					geometry = getRandomCoordinates();
					// Создаем метку со случайными координатами
					var placemark = new ymaps.Placemark(geometry, {
						iconContent: '1',
						name: icons[i],
						people: '1 485 267 человек'
					}, {
						preset: icons[i]
                    });
					// Задаем наш шаблон для хинта метки.
					placemark.options.set('hintContentLayout', 'my#superlayout');

					
					// добавляем эту метку в массив меток;
					placemarks[i] = placemark;
				}
*/
				
				
			}



			// Удаление всех меток с карты
			function deleteMarker () {
				// Удаляем все  метки из кластера
				cluster.removeAll();
			}

			//$('#addMarker').bind('click', addMarkers);
			$('#addMarker').bind('click', add);
			$('#deleteMarker').bind('click', deleteMarker);
			
				$("#loader").ajaxStart(function(){
					// alert('1');
					$(this).show();
				});
				
				$("#loader").ajaxStop(function(){
					// alert('2');
					$(this).hide();
				});

		}
		
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
		for ( key in array )
		{
			for ( i = 1; i< argc; i++ )
			{
				found = false;
				for ( key_c in argv[i] )
				{
					if ( argv[i][key_c] == array[key] )
					{
						found = true;
						break;
					}
				}
				if ( !found ) arr_dif.push(array[key]);
			}
		}
		return arr_dif;
	}

    </script>
</head>

<body>
	<p>
		<input type="button" value="Добавить на карту" id="addMarker"/>
		<input type="button" value="Удалить все метки" id="deleteMarker"/>
	</p>
	<div id="loader"></div>
	<div id="map" style="width: 1800px; height: 700px"></div>
</body>

</html>
