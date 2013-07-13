
	<a name="top"></a>
	<h1 class="red">АН &laquo;<?=$agency['name']?>&raquo;</h1>

	<ul class="bookmark">
		<? foreach ( $sections as $s => $name ) {
			if ( $s == $this->uri->segment(2) ) echo '<li class="current">'.$name.'</li>';
			else echo '<li><a href="/agency/'.$s.'/'.$agency['id'].'">'.$name.'</a></li>';
		} ?>
	</ul>

	<div class="pages">
		<a href="/agency/objects/<?=$agency['id']?>">Таблица</a> &nbsp; &nbsp; На карте
	</div>

	<div id="Gmap"></div>

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
	echo implode(' &nbsp; ',$ico);
?>


	<script type="text/javascript">
	//<![CDATA[

	if ( GBrowserIsCompatible() )
	{
		// arrays to hold copies of the markers used by the side_bar
		// because the function closure trick doesnt work there
		var gmarkers = [];

		// A function to create the marker and set up the event window
		function createMarker ( lat, lng, label, html )
		{
			var point = new GLatLng ( lat, lng );
			var marker = new GMarker ( point );
			marker.tooltip = '<div class="tooltip">' + label + '</div>';

			GEvent.addListener ( marker, "click", function() { marker.openInfoWindowHtml ( '<div id="b' + html + '">http://pn66.ru/maps/text/' + html + '</div>' ); } );
			GEvent.addListener ( marker, "infowindowopen", function() { SubmitLink('/maps/text/'+html, 'b'+html ); } );

			gmarkers.push ( marker );
			map.addOverlay ( marker );

			//  ======  The new marker "mouseover" and "mouseout" listeners  ======
			GEvent.addListener ( marker,"mouseover", function() { showTooltip(marker); } );
			GEvent.addListener ( marker,"mouseout", function() { tooltip.style.visibility="hidden"; } );
		}

		// ====== This function displays the tooltip ======
		// it can be called from an icon mousover or a side_bar mouseover
		function showTooltip ( marker )
		{
			tooltip.innerHTML = marker.tooltip;
			var point = map.getCurrentMapType().getProjection().fromLatLngToPixel(map.fromDivPixelToLatLng(new GPoint(0,0),true),map.getZoom());
			var offset = map.getCurrentMapType().getProjection().fromLatLngToPixel(marker.getPoint(),map.getZoom());
			var anchor = marker.getIcon().iconAnchor;
			var width = marker.getIcon().iconSize.width;
			var height = tooltip.clientHeight;
			var size = new GSize( offset.x - point.x - anchor.x + width - 10, offset.y - point.y -anchor.y - height + 50 );
			var pos = new GControlPosition ( G_ANCHOR_TOP_LEFT, size );
			pos.apply(tooltip);
			tooltip.style.visibility="visible";
		}

		// create the map
		var map = new GMap2 ( document.getElementById('Gmap') );
		map.addControl ( new GLargeMapControl() );
		map.addControl ( new GMapTypeControl() );
		map.setCenter ( new GLatLng( 56.83099450,60.62090550), 12 );

		// ====== set up marker mouseover tooltip div ======
		var tooltip = document.createElement("div");
		map.getPane(G_MAP_FLOAT_PANE).appendChild(tooltip);
		tooltip.style.visibility = "hidden";


		// Read the data from example.xml
		GDownloadUrl (
			"/maps/agency/<?=$agency['id']?>",
			function ( doc )
			{
				var xmlDoc = GXml.parse(doc);
				var markers = xmlDoc.documentElement.getElementsByTagName("marker");

				for (var i = 0; i < markers.length; i++)
				{
					var lat = parseFloat(markers[i].getAttribute("lat"));
					var lng = parseFloat(markers[i].getAttribute("lng"));
					var html = markers[i].getAttribute("html");
					var label = markers[i].getAttribute("label");
					createMarker( lat, lng, label, html );
				}

			}
		);

	}

	else
	{
		alert("Sorry, the Google Maps API is not compatible with this browser");
	}

	//]]>
	</script>







