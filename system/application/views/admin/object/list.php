
	<? $this -> load -> view ( 'admin/head' ); ?>

	<h1>Объявления</h1>

	<p>
		<? foreach ( $type as $t => $name ) { ?>
			<a href="/admin/objects/<?=$t?>"><?=( $this_type == $t ? '<b>'.$name.'</b>' : $name )?></a> &nbsp; &nbsp; &nbsp;
		<? } ?>
	</p>

	<table class="tab">
		<tr>
			<th>ID</th>
			<th>DATE</th>
			<th>USER</th>
			<th>PL</th>
			<th>ADR</th>
			<th>F</th>
			<th>DOM</th>
			<th>TYPE</th>
		</tr>
		<? foreach ( $objects as $r ) { ?>
		<tr>
			<td><a href="/moderation/obj/<?=$r['id']?>"><?=$r['id']?></a></td>
			<td><?=$r['cdate']?></td>
			<td align="center"><?=$r['user_id']?></td>
			<td><?=$r['space_total']?> / <?=$r['space_living']?> / <?=$r['space_kitchen']?> м<sup>2</sup></td>
			<td align="center"><?=$r['ul']?> <?=$r['d']?></td>
			<td align="center"><?=( $r['foto'] ? $r['foto'] : '' )?></td>
			<td align="center"><?=$r['house_id']?></td>
			<td><?=$r['type']?> <?=( $r['for_index'] ? '+' : '' )?></td>
		</tr>
		<? } ?>
	</table>

	<? $this -> load -> view ( 'admin/foot' ); ?>
