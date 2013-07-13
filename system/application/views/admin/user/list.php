
	<? $this -> load -> view ( 'admin/head' ); ?>

	<h1>Зарегистрированные пользователи</h1>

	<table class="tab">
		<tr>
			<th>ID</th>
			<th>EMAIL</th>
			<th>NAME</th>
			<th>DATE</th>
			<th>CNT</th>
			<th>UNS</th>
			<th>-</th>
		</tr>
		<? foreach ( $users as $r ) { ?>
		<tr>
			<td align="center"><?=$r['id']?></td>
			<td><?=$r['email']?></td>
			<td><?=$r['username']?></td>
			<td><?=$r['date']?></td>
			<td align="right"><?=$r['cnt']?></td>
			<td align="right"><?=$r['cnt_un_status']?></td>
			<td><a href="/admin/users/<?=$r['id']?>/all_del">del</a></td>
		</tr>
		<? } ?>
	</table>


	<? $this -> load -> view ( 'admin/foot' ); ?>
