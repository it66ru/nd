<? $this->load->view('admin/head'); ?>

	<h1>Ошибки объявлений. <?=$title?></h1>

	<table class="tab">
		<tr>
			<th>ID</th>
			<th>M</th>
			<th>Адрес</th>
			<th>К</th>
			<th>Цена</th>
			<th>Площадь</th>
			<th>Этаж</th>
			<th>Телефон</th>
		</tr>
		<? foreach ( $objects as $obj ) { ?>
		<tr>
			<td><a href="/admin/object/<?=$obj['id']?>"><?=$obj['id']?></a></td>
			<td><?=$obj['moderator']?></td>
			<td><?=$obj['address']?></td>
			<td align="center"><?=$obj['rooms']?></td>
			<td align="right"><?=number_format($obj['price'], 0, '', ' ')?></td>
			<td align="center"><?=$obj['space_total']?> / <?=$obj['space_living']?> / <?=$obj['space_kitchen']?></td>
			<td align="center"><?=$obj['floor']?> / <?=$obj['storey']?></td>
			<td><?=$obj['phone']?></td>
		</tr>
		<? } ?>
	</table>

<? $this->load->view('admin/foot'); ?>

