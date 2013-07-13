
	<? $this->load->view('admin/head'); ?>

	<h1>Объявления - <?=count($objects)?> <?=($date ? ' за '.$date : '' )?></h1>

<style>
	table.tab td {
		white-space: nowrap;
	}
</style>

	<table class="tab">
		<tr>
			<th>ID</th>
			<th>Дата добавления</th>
			<th>Дата модерации</th>
			<th>Ист.</th>
			<th>Ф</th>
			<th>На сайте</th>
			<th>К</th>
			<th>Площадь</th>
			<th>Цена</th>
			<th>Имя</th>
			<th>Телефон</th>
		</tr>
		<? foreach ( $objects as $r ) { ?>
			<tr <?=( $r['status'] == 'rejected' ? 'bgcolor="#fee"' : '' )?> >
				<td><a href="/moderation/obj/<?=$r['id']?>" target="_blank"><?=$r['id']?></a></td>
				<td><?=$r['cdate']?></td>
				<td><?=$r['mdate']?></td>
				<td><a href="<?=$r['url']?>" target="_blank">avito</a></td>
				<td><?=($r['foto'] ? $r['foto'] : '-')?></td>
				<? if ( $r['status'] == 'rejected' ) { ?>
					<td colspan="6"><?=$r['reason_reject']?></td>
				<? } else { ?>
					<td>
						<a href="http://pn66.ru/flat/info/<?=$r['id']?>" target="_blank"><?=$r['id']?></a> <?=( $r['for_index'] ? '+' : '' )?>
					</td>
					<td><?=$r['rooms']?></td>
					<td><?=$r['space_total']?> / <?=$r['space_living']?> / <?=$r['space_kitchen']?></td>
					<td><?=number_format($r['price'],0,'.',' ')?></td>
					<td><?=$r['name']?></td>
					<td><?=$r['phone']?></td>
				<? } ?>
			</tr>
		<? } ?>
	</table>

	<? $this->load->view('admin/foot'); ?>
