
	<? $this->load->view('admin/head'); ?>

	<h1><?=($house['id'] ? 'Дом. '. $house['address'] : 'Добавление нового дома')?></h1>

	<form method="post">
		<div class="block">
			Название улицы:<br />
			<?=form_dropdown('street_id', $streets, $house['street_id'], 'class="w300"' );?>
		</div>
		<div class="block">
			Номер дома:<br />
			<input name="num" type="text" value="<?=$house['num']?>" class="w50">
		</div>
		<div class="block">
			Район:<br />
			<?=form_dropdown('district_id', $districts, $house['district_id'], 'class="w200"' );?>
		</div>
		<div class="clear"></div>
		<div class="block">
			Яндекс-адрес:<br />
			<input name="ya_address" type="text" value="<?=$house['ya_address']?>" class="w400">
		</div>
		<div class="block">
			Яндекс Lat:<br />
			<input name="yaLat" type="text" value="<?=$house['yaLat']?>" class="w100">
		</div>
		<div class="block">
			Яндекс Lng:<br />
			<input name="yaLng" type="text" value="<?=$house['yaLng']?>" class="w100">
		</div>
		<div class="clear"></div>
		<div class="block">
			Тип дома:<br />
			<?=form_dropdown('house_type_id', $this->Mhouse->form_data['type'], $house['house_type_id'], 'class="w200"' );?>
		</div>
		<div class="block">
			Этажность:<br />
			<input name="storey" type="text" value="<?=$house['storey']?>" class="w50">
		</div>
		<div class="block">
			Материал стен:<br />
			<?=form_dropdown('material', $this->Mhouse->form_data['material'], $house['material'], 'class="w200"' );?>
		</div>
		<div class="block">
			Год:<br />
			<input name="year" type="text" value="<?=$house['year']?>" class="w70">
		</div>
		<div class="clear"></div>
		<div class="block">
			Название дома:<br />
			<input name="name" type="text" value="<?=$house['name']?>" class="w400">
		</div>
		<div class="block">
			Ссылка на источник:<br />
			<input name="source" type="text" value="<?=$house['source']?>" class="w400">
		</div>
		<div class="clear"></div>
		<div class="block">
			Описание дома:<br />
			<textarea name="description" class="w800 h300"><?=$house['description']?></textarea>
		</div>
		<div class="clear"></div>
		<div class="block">
			<br />
			<input name="edit" type="submit" value="Изменить данные" class="button">
		</div>
		<div class="clear"></div>

		
	</form>

	<? $this->load->view('admin/foot'); ?>
