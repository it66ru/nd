
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
			<td><a href="/admin/object/<?=$r['id']?>"><?=$r['id']?></a></td>
			<td><?=$r['date_new']?></td>
			<td align="center"><?=$r['user']?></td>
			<td><?=$r['pl_o']?> / <?=$r['pl_k']?> / <?=$r['pl_j']?> м<sup>2</sup></td>
			<td><?=$r['info']?></td>
			<td align="center"><?=( $r['foto'] ? $r['foto'] : '' )?></td>
			<td align="center"><?=$r['id_dom']?></td>
			<td><?=$r['type']?> <?=( $r['for_index'] ? '+' : '' )?></td>
		</tr>
		<? } ?>
	</table>

	<? $this -> load -> view ( 'admin/foot' ); ?>
