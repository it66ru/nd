

	<table cellSpacing=0 cellPadding=0 border=0>
		<tr>
			<td><h1 class=red><?=$info['ob']?> - <?=$info['kk']?>к</h1></td>
			<td align="right"><h1 class=red><?=number_format($info['cena'],0,',',' ')?> р.</h1></td>
		</tr>
		<tr>
			<td valign="top">
				<table cellSpacing=0 cellPadding=7 border=0>
					<tr><td class="t1">Обьект</td><td class="t2"><?=$info['ob']?></td></tr>
					<tr><td class="t1">Адрес</td><td class="t2"><a href="/<?=$dom['id']?>"><?=$dom['adr_ul']?>, <?=$dom['adr_d']?></a></td></tr>
					<tr><td class="t1">Район</td><td class="t2"><a href="/<?=$dom['r']?>"><?=$dom['title']?></a></td></tr>
					<tr><td class="t1">Комнат</td><td class="t2"><?=$info['kk']?> (из. - <?=$info['ik']?>)</td></tr>
					<tr><td class="t1">Площадь</td><td class="t2"><?=$info['pl_o']?> / <?=$info['pl_j']?> / <?=$info['pl_k']?> м<sup>2</sup></td></tr>
					<tr><td class="t1">Дом</td><td class="t2"><?=$dom['type']?> / <?=$dom['material']?> / <?=$dom['gp']?> г.</td></tr>
					<tr><td class="t1">Этаж</td><td class="t2"><?=$info['et']?> / <?=$dom['storey']?></td></tr>
				</table>
			</td>
			<td valign="top" style="padding-left:30px;">
				<div align="right">
					<table cellSpacing=0 cellPadding=7 border=0>
						<tr><td class="t1">Дата создания</td><td class="t2"><?=$info['date_new']?></td></tr>
						<tr><td class="t1">Дата изменения</td><td class="t2"><?=$info['date_up']?></td></tr>
					</table>
				</div>
				<table cellSpacing=0 cellPadding=7 border=0>
					<tr><td colspan=2><a href="/agency/<?=$agency['id']?>.html" class=ag><?=$agency['name']?></a></td></tr>
					<tr><td class="t1">Название</td><td class="t2"><?=$agency['full_name']?></td></tr>
					<tr><td class="t1">Адрес</td><td class="t2"><?=$agency['adr']?></td></tr>
					<tr><td class="t1">Телефон</td><td class="t2"><?=$agency['tel']?></td></tr></table>
			</td>
		</tr>
	</table>

	<br />

<!--
# <div id="flat_info"><b><?=$info['ob']?> - .$k.к</b><br />.$r[r].,
# <a href="./.$type./.$r[id]..html">.$r[adr].</a><br>Площадь: .$r[pl_o]. / .$r[pl_j]./ .$r[pl_k]. м<sup>2</sup><br />
# Этаж: .$r[et]. / .$r[etn].<br><b>Цена: <a href="./.$type./.$r[id]..html">.repl_price($r[ena],$type).</a></b><br />&nbsp;</div>
-->


	<div style="width:600px;">
		<?=( $info['bl'] ? '<b>Балкон</b>: '.$info['bl'].'; &nbsp;' : '' )?>
		<?=( $info['su'] ? '<b>Сан. узлы</b>: '.$info['su'].'; &nbsp;' : '' )?>
		<?=( $info['rem'] ? '<b>Ремонт</b>: '.$info['rem'].'; &nbsp;' : '' )?>
		<?=( $info['te'] ? '<b>Телефон</b>: '.$info['te'].'; &nbsp;' : '' )?>
		<?=( $info['pp'] ? '<b>Перепланировка</b>: '.$info['pp'].'; &nbsp;' : '' )?>
		<?=( $info['up'] ? '<b>Условия продажи</b>: '.$info['up'].'; &nbsp;' : '' )?>
		<?=( $info['ipo'] ? '<b>Ипотека</b>: '.$info['ipo'].'; &nbsp;' : '' )?>
		<?=( $info['kom'] ? '<b>Комментарий</b>: '.$info['kom'].'; &nbsp;' : '' )?>
		<br />
		<?=( $info['ktel'] ? '<b>Контактный телефон</b>: '.$info['ktel'].'; &nbsp;' : '' )?>
		<?=( $info['atel'] ? '<b>Телефон агента</b>: '.$info['atel'].'; &nbsp;' : '' )?>
	</div>

	<div id="Gmap"></div>
	<img src="/ico/green.png" width="25" height="30"> - Цена &darr;  Площадь &uarr; &nbsp; &nbsp;
	<img src="/ico/purple.png" width="25" height="30"> - Цена &uarr;  Площадь &uarr; &nbsp; &nbsp;
	<img src="/ico/orange.png" width="25" height="30"> - Цена &darr;  Площадь &darr; &nbsp; &nbsp;
	<img src="/ico/blue.png" width="25" height="30"> - Цена &uarr;  Площадь &darr; <br>

	<h2>Похожие предложения в этом районе</h2>

	<table class="tab">
		<tr>
			<th>Цена</th>
			<th>Адрес</th>
			<th>Комнат</th>
			<th>Площадь</th>
			<th>Этаж</th>
			<th>Дата</th>
		</tr>

		<? foreach ( $around as $r ) { ?>
			<tr>
				<td align="right">
					<a href="/flat_sale/<?=$r['id']?>"><?=number_format($r['cena'],0,',',' ')?> р.</a>
				</td>
				<td>
					<a href="/flat_sale/<?=$r['id']?>"><?=$r['adr_ul']?>, <?=$r['adr_d']?></a>
					<a href="/dom/<?=$r['id_dom']?>">.</a>
				</td>
				<td align="center"><?=$r['kk']?></td>
				<td align="center"><?=$r['pl_o']?> / <?=$r['pl_j']?> / <?=$r['pl_k']?></td>
				<td class="center"><?=$r['et']?> / <?=$r['storey']?></td>
				<td align="center"><?=$r['date_up']?></td>
			</tr>
		<? } ?>
	</table>

	<script type="text/javascript">
		//<![CDATA[
		if ( GBrowserIsCompatible() )
		{
			function createMarker(point,html)
			{
				var marker = new GMarker(point);
				GEvent.addListener(marker, "click", function() {
				marker.openInfoWindowHtml(html);
				});
				return marker;
			}

			// Display the map, with some controls and set the initial location
			var map = new GMap2(document.getElementById("Gmap"));
			map.addControl(new GLargeMapControl());
			map.addControl(new GMapTypeControl());
			map.setCenter(new GLatLng(<?=$dom['gLat']?>,<?=$dom['gLng']?>),15);

			// Set up three markers with info windows
			var point = new GLatLng(<?=$dom['gLat']?>,<?=$dom['gLng']?>);
			var marker = createMarker(point,'здесь');
			map.addOverlay(marker);
		}
		else
		{
			alert("Sorry, the Google Maps API is not compatible with this browser");
		}
		//]]>
	</script>
