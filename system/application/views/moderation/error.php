
	<? $this->load->view('admin/head'); ?>

	<h1>Ошибки модерации</h1>

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
			<th>Модератор</th>
			<th>Ошибка</th>
		</tr>
		<? foreach ( $objects as $_ ) { ?>
			<tr>
				<td><a href="/moderation/obj/<?=$_['id']?>" target="_blank"><?=$_['id']?></a></td>
				<td><?=$_['cdate']?></td>
				<td><?=$_['mdate']?></td>
				<td><?=$_['moderator_name']?> (<?=$_['moderator_id']?>)</td>
				<td><?=$_['error']?></td>
			</tr>
		<? } ?>
	</table>

	<? $this->load->view('admin/foot'); ?>
