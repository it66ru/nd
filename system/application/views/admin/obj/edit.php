
	<? $this->load->view('admin/head'); ?>

	<h1>Редактирование объявления #<?=$obj['id']?></h1>

	<? if ($this->Mobject->parse) { ?>
		<p>
			<a href="#" onClick="window.open('<?=$this->Mobject->parse['url']?>','','Toolbar=0,Location=0,Directories=0,Status=0,Menubar=0,Scrollbars=0,Resizable=0,Width=700,Height=600');">показать источник</a> / 
			<a href="/cron_avito/get_info/<?=$this->Mobject->parse['id']?>">обновить</a>
		</p>
	<? } ?>

	<form method="post">
		<div class="clear">
			<div class="block">
				Название улицы <span class="red">*</span> :<br />
				<input name="ul" type="text" value="" class="w250" id="street">
			</div>
			<div class="block">
				Номер дома <span class="red">*</span> :<br />
				<input name="d" type="text" value="" class="w50" id="building">
			</div>
			<div class="block">
				ID дома:<br />
				<input name="house_id" type="text" value="<?=$this->Mobject->data['house_id']?>" class="w50" id="house_id">
			</div>
			<div class="block">
				Адрес правильный:<br />
				<input name="adr" type="text" value="<?=$this->Mobject->building['adr']?>" class="w250">
			</div>
			<div class="clear"></div>
			<div class="block w150">
				Цена <span class="red">*</span> :<br />
				<input name="price" type="text" value="<?=$this->Mobject->data['price']?>" style="width:100px"> руб.
			</div>
			<div class="block w100">
				Комнат <span class="red">*</span> :<br />
				<?=form_dropdown ( 'rooms', $this->Mobject->form_data['rooms'], $this->Mobject->data['rooms'], 'style="width:100px"' );?>
			</div>
			<div class="block w100">
				Этаж <span class="red">*</span> :<br />
				<input name="floor" type="text" value="<?=$this->Mobject->data['floor']?>" style="width:50px">
			</div>
			<div class="block w100">
				Общая пл. <span class="red">*</span> :<br />
				<input name="space_total" type="text" value="<?=$this->Mobject->data['space_total']?>" style="width:50px"> м<sup>2</sup>
			</div>
			<div class="block w100">
				Жилая пл. <span class="red">*</span> :<br />
				<input name="space_living" type="text" value="<?=$this->Mobject->data['space_living']?>" style="width:50px"> м<sup>2</sup>
			</div>
			<div class="block w100">
				Пл. кухни <span class="red">*</span> :<br />
				<input name="space_kitchen" type="text" value="<?=$this->Mobject->data['space_kitchen']?>" style="width:50px"> м<sup>2</sup>
			</div>
		</div>
		<div class="clear">
			<div class="block w150">
				Ремонт:<br /><?=form_dropdown ( 'info[renovation]', $this->Mobject->form_data['renovation'], $this->Mobject->data['info']['renovation'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Балкон:<br /><?=form_dropdown ( 'info[balcony]', $this->Mobject->form_data['balcony'], $this->Mobject->data['info']['balcony'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Сан. узел:<br /><?=form_dropdown ( 'info[bathroom]', $this->Mobject->form_data['bathroom'], $this->Mobject->data['info']['bathroom'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Окна выходят:<br /><?=form_dropdown ( 'info[window]', $this->Mobject->form_data['window'], $this->Mobject->data['info']['window'], 'style="width:100px"' );?>
			</div>
			<div class="clear"></div>
			<div class="block w150">
				Телефон:<br /><?=form_dropdown ( 'info[phone]', $this->Mobject->form_data['phone'], $this->Mobject->data['info']['phone'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Мебель:<br /><?=form_dropdown ( 'info[furniture]', $this->Mobject->form_data['furniture'], $this->Mobject->data['info']['furniture'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Краткий комментарий:<br />
				<input name="info[comment]" type="text" value="<?=$this->Mobject->data['info']['comment']?>" style="width:250px">
			</div>
			<div class="clear"></div>
			<div class="block w150">
				Условия продажи:<br /><?=form_dropdown ( 'info[type]', $this->Mobject->form_data['type'], $this->Mobject->data['info']['type'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Ипотека:<br /><?=form_dropdown ( 'info[mortgage]', $this->Mobject->form_data['mortgage'], $this->Mobject->data['info']['mortgage'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Под офис:<br /><?=form_dropdown ( 'info[office]', $this->Mobject->form_data['office'], $this->Mobject->data['info']['office'], 'style="width:100px"' );?>
			</div>
			<div class="block w150">
				Перепланировка:<br /><?=form_dropdown ( 'info[replan]', $this->Mobject->form_data['replan'], $this->Mobject->data['info']['replan'], 'style="width:100px"' );?>
			</div>
			<div class="block">
				Дополнительная информация (описание в свободной форме):<br />
				<textarea name="description" style="height:150px; width:700px;"><?=$this->Mobject->data['description']?></textarea>
			</div>
		</div>
		<div class="clear">
			<div class="block">
				Контактное лицо <span class="red">*</span> :<br />
				<input name="name" type="text" value="<?=$this->Mobject->data['name']?>" class="w200">
			</div>
			<div class="block">
				Контактный телефон <span class="red">*</span> :<br />
				<input name="phone" id="phone" type="text" value="<?=$this->Mobject->data['phone']?>" class="w200">
			</div>
			<script type="text/javascript">
				$(function(){
					$("#phone").mask("(999) 999-99-99",{placeholder:" "});
				});
			</script>
			<div class="block">
				Контактный e-mail:<br />
				<input name="email" type="text" value="<?=$this->Mobject->data['email']?>" class="w200">
			</div>
		</div>
		<div class="clear">
			<div class="title">Модерация</div>
			<div class="block">
				Статус :<br />
				<?=form_dropdown('status', $this->Mobject->form_data['status'], $this->Mobject->data['status'], 'style="width:120px"');?>
			</div>
			<div class="block">
				Комментарий :<br />
				<input name="reason_reject" type="text" value="<?=$this->Mobject->data['reason_reject']?>" class="w200">
			</div>
			<div class="block">
				<br />
				<input name="for_index" type="checkbox" value="1" <?=( $this->Mobject->data['for_index'] ? 'checked' : '' )?>> на главную
			</div>
			<div class="block">
				<br />
				<input name="edit" type="submit" value="Изменить объявление" class="button">
			</div>
		</div>
		
		<? if ( $this->Mobject->foto ) { ?>
		<div class="clear">
			<div class="title">Фотографии</div>
			<ul id="fotos" style="overflow:hidden; padding-left:20px; list-style:none;">
				<? foreach ( $this->Mobject->foto as $n=>$f ) { ?>
					<li id="foto_<?=$n?>">
						<img src="http://pn66.ru/foto/<?=$this->Mobject->id?>/large/<?=$f?>" ondblClick="if (confirm('Удалить?')) $('#foto_<?=$n?>').remove();">
						<input type="hidden" value="<?=$f?>" name="foto[]">
					</li>
				<? } ?>
			</ul>
		</div>
		<? } ?>
	</form>


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
			$("#fotos").sortable();
		});
	</script>

	<? $this->load->view('admin/foot'); ?>
