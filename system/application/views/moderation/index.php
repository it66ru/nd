
	<? $this->load->view('admin/head'); ?>

	<? if ($cnt_error > 0) { ?>
		<p><a href="/moderation/error" class="red">Объявления с ошибками: <?=$cnt_error?></a></p>
	<? } ?>
	<p>Кол-во свободных объявлений: <?=$cnt_free?></p>
	<br><br>

	<h1>Статистика</h1>

	<table class="tab">
		<tr>
			<th>Неделя</th>
			<th>Пн</th>
			<th>Вт</th>
			<th>Ср</th>
			<th>Чт</th>
			<th>Пт</th>
			<th>Сб</th>
			<th>Вс</th>
			<th>Итог</th>
		</tr>
		<? foreach ( $calendar as $w => $days ) { ?>
			<tr>
				<td><?=$w?></td>
				<? foreach ( $days as $d => $cnt ) { ?>
					<td align="center">
						<? if ( $cnt ) { ?>
							<a href="/moderation/history/<?=$d?>" title="<?=$d?>"><?=$cnt?></a>
						<? } else { ?>
							-
						<? } ?>
					</td>
				<? } ?>
				<td align="right"><b><?=array_sum($days)?></b></td>
			</tr>
		<? } ?>
	</table>

	<br><br>
	<h2>Баланс: &nbsp; <?=number_format($balance['parse'],0,'',' ')?> - <?=number_format($balance['amount'],0,'',' ')?> = <big class="red"><?=number_format($balance['parse']-$balance['amount'],0,'',' ')?> р.</big></h2>

	<br><br><br>
	<h2>Выплаты</h2>
	<table class="tab">
		<tr>
			<th>Дата / Время</th>
			<th>Сумма</th>
			<th>Комментарий</th>
		</tr>
		<? foreach ( $payment as $r ) { ?>
			<tr>
				<td><?=$r['cdate']?></td>
				<td align="right"><?=number_format($r['amount'],0,'',' ')?> р.</td>
				<td><?=$r['type']?></td>
			</tr>
		<? } ?>
	</table>


	<? $this->load->view('admin/foot'); ?>
