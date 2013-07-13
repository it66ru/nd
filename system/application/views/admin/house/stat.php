
	<? $this -> load -> view ( 'admin/head' ); ?>

	<h1>Дома. Статистика</h1>

	<table class="tab">
		<tr>
			<th>address</th>
			<th>cnt</th>
			<th>max_floor</th>
			<th>material</th>
			<th>year</th>
			<th>storey</th>
			<th>house_type_id</th>
		</tr>
		<? foreach ($stat as $r) { ?>
			<tr>
				<td><a href="/admin/house/<?=$r['id']?>"><?=$r['address']?></a></td>
				<td><?=$r['cnt']?></td>
				<td><?=$r['max_floor']?></td>
				<td><?=$r['material']?></td>
				<td><?=$r['year']?></td>
				<td><?=$r['storey']?></td>
				<td><?=$r['house_type_id']?></td>
			</tr>
		<? } ?>
	</table>

	<? $this -> load -> view ( 'admin/foot' ); ?>
