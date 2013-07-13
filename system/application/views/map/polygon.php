
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?=$ds['name']?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <!--
        Подключаем API карт 2.x
        Параметры:
          - load=package.full - полная сборка. Также подключаем модуль util.json для работы с JSON-объектами 
	      - lang=ru-RU - язык русский.
    -->
    <script src="http://api-maps.yandex.ru/2.0/?load=package.full,util.json&lang=ru-RU"
            type="text/javascript"></script>

    <!--
        Основная библиотека JQuery.
        Яндекс предоставляет хостинг JavaScript-библиотек:
    -->
    <script src="http://yandex.st/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        // Как только будет загружен API и готов DOM, выполняем инициализацию
        ymaps.ready(init);

        function init () {
            var myMap = new ymaps.Map("map", {
                    center: [56.76, 60.64],
                    zoom: 14
                }),
                myGeometry = {
                    type: 'Polygon',
                    coordinates: <?=$ds['polygon']?>
                },
                myOptions = {
                    strokeWidth: 2,
                    opacity: 0.5,
                    strokeColor: '#0000FF', // синий
                    fillColor: '#FFFF00', // желтый
                    draggable: false      // объект можно перемещать, зажав левую кнопку мыши
                };

				<? foreach ( $dom as $d ) if (1) { ?>
					properties = {
						balloonContent: '<div id="d<?=$d['id']?>"><a href="#" onClick="setR(<?=$d['id']?>, 0, \'dom\')">del</a></div>'
					},
					options = { balloonCloseButton: true },
					placemark = new ymaps.Placemark([<?=$d['yaLat']?>, <?=$d['yaLng']?>], properties, options);
					myMap.geoObjects.add(placemark);
				<? } ?>

            // Создаем геообъект с определенной (в switch) геометрией.
            var myGeoobject = new ymaps.GeoObject({geometry: myGeometry}, myOptions);

            // При визуальном редактировании геообъекта изменяется его геометрия.
            // Тип геометрии измениться не может, однако меняются координаты.
            // При изменении геометрии геообъекта будем выводить массив его координат.
            myGeoobject.events.add('geometrychange', function (event) {
                printGeometry(myGeoobject.geometry.getCoordinates());
            });

            // Размещаем геообъект на карте
            myMap.geoObjects.add(myGeoobject);
            // ... и выводим его координаты.
            printGeometry(myGeoobject.geometry.getCoordinates());
            // Подключаем к геообъекту редактор, позволяющий
            // визуально добавлять/удалять/перемещать его вершины.
            myGeoobject.editor.startEditing();
        }

        // Выводит массив координат геообъекта в <div id="geometry">
        function printGeometry (coords) {
            $('#geometry').html('Координаты: ' + stringify(coords));

            function stringify (coords) {
                var res = '';
                if ($.isArray(coords)) {
                    res = '[ ';
                    for (var i = 0, l = coords.length; i < l; i++) {
                        if (i > 0) {
                            res += ', ';
                        }
                        res += stringify(coords[i]);
                    }
                    res += ' ]';
                } else if (typeof coords == 'number') {
                    res = coords.toPrecision(6);
                } else if (coords.toString) {
                    res = coords.toString();
                }

                return res;
            }
        }
                function setR(id, rg, type)
		{
				$.ajax ({
					url: '/map/set_r/'+type+'/'+id+'/'+rg,
					success: function(msg) {
						$('#d'+id).html(msg);
					},
					error: function(msg) {
						alert('Ошибка');
					}
				});
		}
    </script>
</head>

<body>
<div id="geometry"></div>

<div id="map" style="width: 1800px; height: 700px"></div>

</body>

</html>
