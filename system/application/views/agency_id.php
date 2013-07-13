
	<h1 class="red"><?=$agency['name']?></h1>

	<table cellSpacing=0 cellPadding=7 border=0>
		<tr>
			<td class="t1">Название</td>
			<td class="t2"><?=$agency['full_name']?></td>
		</tr>
		<tr>
			<td class="t1">Адрес</td>
			<td class="t2"><?=$agency['adr']?></td>
		</tr>
		<tr>
			<td class="t1">Телефон</td>
			<td class="t2"><?=$agency['tel']?></td>
		</tr>
		<tr>
			<td class="t1">Сайт</td>
			<td class="t2"><?=$agency['www']?></td>
		</tr>
	</table>

	<br /><br />

	<div id="map_canvas" class="map"></div>

	<h2>Предлежения агентства <span><?=$agency['name']?></span></h2>

	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th>Цена</th>
				<th>Адрес</th>
				<th>Комнат</th>
				<th>Площадь</th>
				<th>Этаж</th>
				<th>Дата</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ( $flat_sale as $r ) { ?>
			<tr onmouseover="bgColor='#f0f0f0'" onmouseout="bgColor='#FFFFFF'" bgcolor="#FFFFFF">
				<td class="tab_p3">
					<a href="/flat_sale/<?=$r['id']?>"><?=number_format($r['cena'],0,',',' ')?> р.</a>
				</td>
				<td class="tab_p2">
					<a href="/flat_sale/<?=$r['id']?>"><?=$r['adr_ul']?>, <?=$r['adr_d']?></a>
					<a href="/dom/<?=$r['id_dom']?>">.</a>
				</td>
				<td class="tab_p"><?=$r['kk']?></td>
				<td class="tab_p"><?=$r['pl_o']?> / <?=$r['pl_j']?> / <?=$r['pl_k']?></td>
				<td class="tab_p"><?=$r['et']?> / <?=$r['storey']?></td>
				<td><?=$r['date_up']?></td>
			</tr>
			<? } ?>
		</tbody>
	</table>



