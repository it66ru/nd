
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Примеры. Задание стиля многоугольника.</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <!--
        Подключаем API карт 2.x
        Параметры:
          - load=package.full - полная сборка;
	      - lang=ru-RU - язык русский.
    -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"
            type="text/javascript"></script>
    <script type="text/javascript">
        // Как только будет загружен API и готов DOM, выполняем инициализацию
        ymaps.ready(init);

        function init () {
            var myMap = new ymaps.Map("map", {
                    center: [56.837, 60.60],
                    zoom: 13
                });
                
                <? foreach ( $districts as $d ) { ?>
                	// Создаем многоугольник
					myPolygon = new ymaps.Polygon(<?=$d['polygon']?>, 
					{ hintContent: "<?=$d['id'].' '.$d['title']?>" }, 
					{
						fillColor: '#00FF00',
						strokeColor: '#0000FF',
						opacity: 0.3,
						strokeWidth: 2,
					});
					myMap.geoObjects.add(myPolygon);
                <? } ?>


            
        }
        
    </script>
</head>

<body>
	<div id="map" style="width:100%; height:900px; overflow:hidden"></div>
</body>

</html>
