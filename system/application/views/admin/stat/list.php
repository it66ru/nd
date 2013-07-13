
	<? $this->load->view('admin/head'); ?>

	<h1>Статистика</h1>

	<ul>
		<? foreach ($stat as $s) { ?>
			<li><a href="/statistics/report/<?=$s['id']?>"><?=$s['name']?></a></li>
		<? } ?>
	</ul>

	<? $this->load->view('admin/foot'); ?>
