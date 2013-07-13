
	<? $this -> load -> view ( 'admin/head' ); ?>

	<h1>Дома &nbsp; <a href="/admin/house/add">+</a></h1>

	<div style="overflow:hidden">
		<div style="float:left; overflow-y:scroll; height:700px; width:200px;">
			<? foreach ($streets as $s) { ?>
				<a href="/admin/houses/<?=$s['id']?>"><?=$s['type']?>. <?=$s['name']?></a><br>
				<? if ($s['id'] == $street_id) $street_name = $s['type'].'. '.$s['name']; ?>
			<? } ?>
		</div>
		<div style="float:left; overflow:scroll; height:700px; margin-left:30px; width:770px;">
			<? if ( $street_id ) { ?>
				<h2><?=$street_name?></h2>
				<br>
				<table class="tab">
					<tr>
						<th>num</th>
						<th>district_id</th>
						<th>yaLat</th>
						<th>yaLng</th>
						<th>ya_address</th>
						<th>material</th>
						<th>year</th>
						<th>storey</th>
						<th>house_type_id</th>
					</tr>
					<? foreach ($houses as $h) { ?>
						<tr>
							<td><a href="/admin/house/<?=$h['id']?>"><?=$h['num']?></a></td>
							<td><?=$h['district_id']?></td>
							<td><?=$h['yaLat']?></td>
							<td><?=$h['yaLng']?></td>
							<td></td>
							<td><?=$h['material']?></td>
							<td><?=$h['year']?></td>
							<td><?=$h['storey']?></td>
							<td><?=$h['house_type_id']?></td>
						</tr>
					<? } ?>
				</table>
			<? } ?>
		</div>
	</div>

	<? $this -> load -> view ( 'admin/foot' ); ?>
