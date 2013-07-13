	<? $this->load->view('moderator/head'); ?>

<div style="width: 2000px; overflow: hidden;">
	<div style="float:left; width:850px">
		<h1>Объявление #<?=$parse['id']?></h1>

		<script src="/js/jquery.ui.core.js"></script>
		<script src="/js/jquery.ui.widget.js"></script>
		<script src="/js/jquery.ui.position.js"></script>
		<script src="/js/jquery.ui.autocomplete.js"></script>
		<link rel="stylesheet" href="/css/ui/jquery-ui.css">

		<script>
		$(function() {
			$('#street').autocomplete({
				source: '/ajax/get_street', 
				minLength: 2,
				select: function(event, ui) {
					$('#building').autocomplete('option', 'source', '/ajax/get_building/'+ui.item.id);
					$('#building').val('');
					$('#house_id').val(0);
					
				},
			});
			$('#building').autocomplete({
				minLength: 1,
				select: function(event, ui) {
					$('#house_id').val(ui.item.id);
				},
			});
		});
		</script>

		<form method="post">

			<div class="block">
				Название улицы <span class="red">*</span> :<br />
				<input name="ul" type="text" value="<?=$object['info_ul']?>" class="w250" id="street">
			</div>

			<div class="block">
				Номер дома <span class="red">*</span> :<br />
				<input name="d" type="text" value="<?=$object['info_d']?>" class="w50" id="building">
			</div>

			<div class="block">
				ID дома:<br />
				<input name="id_dom" type="text" value="<?=$object['id_dom']?>" class="w50" id="house_id">
			</div>

			<div class="block">
				Адрес правильный:<br />
				<input name="adr" type="text" value="<?=$object['dom']['adr']?>" class="w250">
			</div>

			<div class="clear"></div>

			<div class="block w150">
				Цена <span class="red">*</span> :<br />
				<input name="cena" type="text" value="<?=$object['cena']?>" style="width:100px"> руб.
			</div>

			<div class="block w100">
				Комнат <span class="red">*</span> :<br />
				<?=form_dropdown ( 'kk', $form['kk'], $object['kk'], 'style="width:100px"' );?>
			</div>

			<div class="block w100">
				Этаж <span class="red">*</span> :<br />
				<input name="et" type="text" value="<?=$object['et']?>" style="width:50px">
			</div>

			<div class="block w100">
				Общая пл. <span class="red">*</span> :<br />
				<input name="pl_o" type="text" value="<?=$object['pl_o']?>" style="width:50px"> м<sup>2</sup>
			</div>

			<div class="block w100">
				Пл. кухни <span class="red">*</span> :<br />
				<input name="pl_k" type="text" value="<?=$object['pl_k']?>" style="width:50px"> м<sup>2</sup>
			</div>

			<div class="block w100">
				Жилая пл. <span class="red">*</span> :<br />
				<input name="pl_j" type="text" value="<?=$object['pl_j']?>" style="width:50px"> м<sup>2</sup>
			</div>

			<div class="clear"></div>

			<div class="block w150">
				Ремонт:<br /><?=form_dropdown ( 'rem', $form['rem'], $object['rem'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Балкон:<br /><?=form_dropdown ( 'bl', $form['bl'], $object['bl'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Сан. узел:<br /><?=form_dropdown ( 'su', $form['su'], $object['su'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Окна выходят:<br /><?=form_dropdown ( 'okna', $form['okna'], $object['okna'], 'style="width:100px"' );?>
			</div>

			<div class="clear"></div>

			<div class="block w150">
				Телефон:<br /><?=form_dropdown ( 'te', $form['te'], $object['te'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Мебель:<br /><?=form_dropdown ( 'mb', $form['mb'], $object['mb'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Краткий комментарий:<br />
				<input name="kom" type="text" value="<?=$object['kom']?>" style="width:250px">
			</div>
			<div class="clear"></div>

			<div class="block w150">
				Условия продажи:<br /><?=form_dropdown ( 'up', $form['up'], $object['up'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Ипотека:<br /><?=form_dropdown ( 'ipo', $form['ipo'], $object['ipo'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Под офис:<br /><?=form_dropdown ( 'pof', $form['pof'], $object['pof'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Перепланировка:<br /><?=form_dropdown ( 'pp', $form['pp'], $object['pp'], 'style="width:100px"' );?>
			</div>

			<div class="clear"></div>

			<div class="block">
				Дополнительная информация (описание в свободной форме):<br />
				<textarea name="comment" style="height:150px; width:700px;"><?=$object['comment']?></textarea>
			</div>

			<div class="clear"></div>


			<div class="block">
				Контактное лицо <span class="red">*</span> :<br />
				<input name="kname" type="text" value="<?=$object['kname']?>" class="w200">
			</div>

			<div class="block">
				Контактный телефон <span class="red">*</span> :<br />
				<input name="ktel" type="text" value="<?=$object['ktel']?>" class="w200">
			</div>

			<div class="block">
				Контактный e-mail:<br />
				<input name="kemail" type="text" value="<?=$object['kemail']?>" class="w200">
			</div>

			<div class="clear"></div>


			<div class="title">Модерация</div>

			<div class="block">
				Статус :<br />
				<?=form_dropdown ( 'type', $form['type'], $object['type'], 'style="width:100px"' );?>
			</div>

			<div class="block">
				Комментарий :<br />
				<input name="cause" type="text" value="<?=$object['cause']?>" class="w200">
			</div>

			<div class="block">
				<br />
				<input name="for_index" type="checkbox" value="1" <?=( $object['for_index'] ? 'checked' : '' )?>> на главную
			</div>

			<div class="block">
				<br />
				<input name="edit" type="submit" value="Изменить объявление" class="button">
			</div>

			<div class="clear"></div>
			
			
			<? if ( isset($foto) ) { ?>

				<div id="result_upload" style="overflow:hidden; padding-left:20px;">
					<? foreach ( $foto as $f ) { ?>
						<img src="/foto/<?=$f['object']?>/large/<?=$f['foto']?>"><br>
					<? } ?>
				</div>

				<div class="clear"></div>

			<? } ?>


			<div style="font-size: 16px; line-height: 25px;">
				<pre><?print_r($object)?></pre>
			</div>


		</form>
	</div>
	<div style="float:left; width:1000px">
		<iframe style="width:1020px; height:920px;" src="<?=$parse['url']?>"></iframe>
		<a href="/cron_avito/get_info/<?=$parse['id']?>">обновить</a>
		<pre><?print_r($parse)?></pre>
	</div>
</div>

	<? $this->load->view('admin/foot'); ?>
