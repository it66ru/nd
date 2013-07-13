	<? $this->load->view('admin/head'); ?>

	<h1>Проверка не найденных. <?=$cnt?></h1>

	<? if ( $cnt > 0 ) { ?>
		<div align="center">
			<a href="/admin/check_not_found/<?=$obj['id']?>/removed" style="color:#b00; font-size:24px">Удалить</a>
			&nbsp; &nbsp; &nbsp; &nbsp;
			<a href="/admin/check_not_found/<?=$obj['id']?>/approved" style="color:#060; font-size:24px">Вернуть</a>
			&nbsp; &nbsp; &nbsp; &nbsp;
			<a href="/cron_avito/check/<?=$obj['id']?>" target="_blank" style="color:#00a; font-size:24px">Проверить</a>
			
		</div>
		<iframe style="width:1020px; height:920px;" src="<?=$obj['url']?>"></iframe>
	<? } ?>

	<? $this->load->view('admin/foot'); ?>
