
	<? $this->load->view('head', $head); ?>

	<h1>Мои объявления</h1>
	
	<div style="text-align:right; font-weight:bold">
		<a href="/my/object/add" style="color:#080">Подать объявление</a>
	</div>

<?
	$object_status = array (
		'new'      => 'Ожидают модерации',
		'approved' => 'Одобренные',
		'rejected' => 'Отклоненные',
		'removed'  => 'Удаленные',
	);
?>

	<ul class="bookmark">
		<? foreach ( $object_status as $url => $name ) { ?>
			<? if ( $this->uri->segment(3) == $url ) { ?>
				<li class="current"><?=$name?></li>
			<? } else { ?>
				<li><a href="/my/objects/<?=$url?>"><?=$name?></a></li>
			<? } ?>
		<? } ?>
	</ul>

	<? if ( isset($objects) ) { ?>
		<table class="tab w_full">
			<tr>
				<th>ID</th>
				<th>Объект</th>
				<th></th>
				<th>Цена</th>
				<th>Контакты</th>
				<th>Дата</th>
				<th></th>
			</tr>
			<? foreach ($objects as $object) { $r = $object; ?>
			<tr>
				<td align="center"><?=$r['id']?></td>
				<td width="100%">
					Квартира <?=$r['rooms']?>К ( <?=$r['space_total']?> / <?=$r['space_living']?> / <?=$r['space_kitchen']?> м<sup>2</sup> )<br />
					<b><?=$r['comment']?></b>
				</td>
				<td><a href="/my/object/<?=$r['id']?>"><img src="/img/edit.png" width="16" height="16" titel="Редактировать" alt="Редактировать"></a></td>
				<td nowrap style="font-size:18px;"><?=number_format($r['price'],0,',',' ')?> р.</td>
				<td nowrap><?=$r['phone']?><br /><?=$r['name']?></td>
				<td nowrap>
					<? if ($object['status'] == 'new') { ?>
						<div style="font-size:10px;"><?=$r['cdate']?> <?=( $r['edate'] ? '<br>'.$r['edate'] : '' )?></div>
						<div style="color:#999">Ожидает проверки</div>
					<? } ?>
					<? if ($object['status'] == 'approved') { ?>
						<div style="color:#080">Одобрено модератором</div>
					<? } ?>
					<? if ($object['status'] == 'rejected') { ?>
						<div style="color:#c00">Отклонено модератором</div>
						<?=$r['reason_reject']?>
					<? } ?>
					<? if ($object['status'] == 'removed') { ?>
						<div style="color:#c00">Удалено</div>
					<? } ?>
				</td>
				<td>
					<a href="/my/del_object/<?=$r['id']?>" OnClick="return confirm('Вы действительно хотите удалить это объявление?')">
						<img src="/img/del.png" width="16" height="16" titel="Удалить" alt="Удалить">
					</a>
				</td>
			</tr>
			<? } ?>
		</table>
	<? } else { ?>
		Объявления отсутствую.
	<? } ?>

	<? $this->load->view('foot'); ?>
