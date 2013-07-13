
	// полученией тайлов координат по географическим

		$.getJSON('/test/notile', function(data) {
			
			var sql = '';
			
			for(var d in data) 
			{
				var t = new YMaps.GeoPoint(data[d].yaLng, data[d].yaLat);
				var z13 = map.tileCoordinates.fromPixels (map.coordSystem.fromCoordPoint(t), 13);
				var z14 = map.tileCoordinates.fromPixels (map.coordSystem.fromCoordPoint(t), 14);
				var z15 = map.tileCoordinates.fromPixels (map.coordSystem.fromCoordPoint(t), 15);
				var z16 = map.tileCoordinates.fromPixels (map.coordSystem.fromCoordPoint(t), 16);
				var z17 = map.tileCoordinates.fromPixels (map.coordSystem.fromCoordPoint(t), 17);
				
				sql += 'UPDATE nd_ekb_building SET '+
					' z13x = ' + z13.number.x + ', z13y = ' + z13.number.y + ', ' +
					' z14x = ' + z14.number.x + ', z14y = ' + z14.number.y + ', ' +
					' z15x = ' + z15.number.x + ', z15y = ' + z15.number.y + ', ' +
					' z16x = ' + z16.number.x + ', z16y = ' + z16.number.y + ', ' +
					' z17x = ' + z17.number.x + ', z17y = ' + z17.number.y + ' WHERE id = ' + data[d].id + '; <br>';
			}
			$('#info').html(sql);



		});
