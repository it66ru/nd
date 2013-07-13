
	<? $this->load->view('admin/head'); ?>

	<h1>Статистика. <?=$report['name']?></h1>

	&larr; <a href="/statistics">к списку отчетов</a>

	<table class="tab">
		<tr>
			<? foreach ($fields as $f) { ?>
				<th><?=$f['name']?></th>
			<? } ?>
		</tr>
		<? foreach ($rows as $r) { ?>
			<tr>
				<? foreach ($fields as $f) { ?>
					<td><?=$r[$f['field']]?></td>
				<? } ?>
			</tr>
		<? } ?>
	</table>

	<? $this->load->view('admin/foot'); ?>
