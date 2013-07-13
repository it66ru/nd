
	<? $this -> load -> view ( 'admin/head' ); ?>

	<h1>Статистика по модераторам</h1>

	<table class="tab">
		<tr>
			<th>ID</th>
			<th>NAME</th>
			<th>Заработал</th>
			<th>Получил</th>
			<th>Баланс</th>
			<th>Кошелек</th>
			<th>Добавить</th>
		</tr>
		<? foreach ( $stat as $r ) { ?>
		<tr>
			<td><a href="/admin/mstat/<?=$r['id']?>"><?=$r['id']?></a></td>
			<td><?=$r['username']?></td>
			<td><?=$r['parse']?></td>
			<td><?=$r['amount']?></td>
			<td><?=$r['parse']-$r['amount']?></td>
			<td><?=$r['payment']?></td>
		</tr>
		<? } ?>
	</table>

	<? $this -> load -> view ( 'admin/foot' ); ?>
