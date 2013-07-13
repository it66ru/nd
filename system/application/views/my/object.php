<?
	$head['title'] = 'PN66.ru - Личный кабинет';
?>

	<? $this -> load -> view ( 'head' , $head ); ?>

	<h1>Новое объявление</h1>

	<? if ( $error ) { ?>
		<div class="block_error"><?=$error?></div>
	<? } ?>

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

	<div class="title">Тип объявления</div>

	<div class="block">
		&nbsp; &nbsp; &nbsp;
		<input name="type" type="radio" value="sale" <?=($this->validation->type != 'rent' ? 'checked="checked"' : '')?> id="type_sale"> Продажа
		&nbsp; &nbsp; &nbsp;
		<input name="type" type="radio" value="rent" <?=($this->validation->type == 'rent' ? 'checked="checked"' : '')?> id="type_rent"> Аренда
	</div>
	
	<script language="javascript">
		$(document).ready(function(){
			if ($('#type_rent').attr('checked') == 'checked') $('#only_sale').hide();
			$("#type_sale").click(function() { $('#only_sale').show(); });
			$("#type_rent").click(function() { $('#only_sale').hide(); });
		});
	</script>

	<div class="clear"></div>


	<div class="title">Адрес</div>

	<div class="block">
		Название улицы <span class="red">*</span> :<br />
		<input name="ul" type="text" value="<?=$this->validation->ul?>" class="w250" id="street">
	</div>

	<div class="block">
		Номер дома <span class="red">*</span> :<br />
		<input name="d" type="text" value="<?=$this->validation->d?>" class="w50" id="building">
		<input name="house_id" type="hidden" value="<?=$this->validation->house_id?>" id="house_id">
	</div>

	<div class="clear"></div>


	<div class="title">Основные характеристики</div>

	<div class="block w150">
		Цена <span class="red">*</span> :<br />
		<input name="price" type="text" value="<?=$this->validation->price?>" style="width:100px"> руб.
	</div>

	<div class="block w150">
		Комнат <span class="red">*</span> :<br />
		<?=form_dropdown ( 'rooms', $this->Mobject->form_data['rooms'], $this->validation->rooms, 'style="width:100px"' );?>
	</div>

	<div class="block w150">
		Этаж <span class="red">*</span> :<br />
		<input name="floor" type="text" value="<?=$this->validation->floor?>" style="width:50px">
	</div>

	<div class="clear"></div>

	<div class="block w150">
		Общая площадь <span class="red">*</span> :<br />
		<input name="space_total" type="text" value="<?=$this->validation->space_total?>" style="width:100px"> м<sup>2</sup>
	</div>

	<div class="block w150">
		Жилая площадь <span class="red">*</span> :<br />
		<input name="space_living" type="text" value="<?=$this->validation->space_living?>" style="width:100px"> м<sup>2</sup>
	</div>

	<div class="block w150">
		Площадь кухни <span class="red">*</span> :<br />
		<input name="space_kitchen" type="text" value="<?=$this->validation->space_kitchen?>" style="width:100px"> м<sup>2</sup>
	</div>

	<div class="clear"></div>


	<div class="title">Фотографии</div>

	<style>
		.foto {			border: 1px solid #BBBBBB;
			float: left;
			height: 100px;
			margin: 10px;
			position: relative;
			text-align: center;
			width: 150px;		}
		.foto .del {
			float: left;
			position: absolute;
			right: 2px;
			top: 2px;
			cursor: pointer;
		}

	</style>

	<p>К своему объявлению Вы можете добавить несколько фотографий в формате <b>.jpg</b></p><br />

	<a id="upload" href="#">Добавить фотографию</a>  &nbsp; <span id="process_upload"></span>

	<div id="result_upload" style="overflow:hidden;">
	<? if ( $this->validation->foto ) foreach ( $this->validation->foto as $f ) { ?>
		<div class="foto">
			<? if ( $this->Mobject->id && file_exists('foto/'.$this->Mobject->id.'/small/'.$f) ) { ?>
				<img src="<?='/foto/'.$this->Mobject->id.'/small/'.$f?>">
			<? } else { ?>
				<img src="/pic/tmp/<?=$f?>">
			<? } ?>
			<input type="hidden" value="<?=$f?>" name="foto[]">
			<div class="del">
				<img src="/img/del.png" width="16" height="16" alt="Удалить" OnClick="del_foto(this)">
			</div>
		</div>
	<? } ?>
	</div>

		<script language="javascript">
			$(function(){
				$('#upload').upload({
					onSubmit: function(){
						$('#process_upload').html('<img src="/img/ajax_loader/38.gif" width="16" height="11" alt="Загружается...">');
					},
					onComplete: function(data){
						$('#process_upload').html('');
						var res = data.split('/=/');
						var text = '<div id="foto' + res[2] + '" class="foto">';
						if ( res[0] == 'ok' ) {
							text+= '<img src="/pic/tmp/' + res[1] + '">';
							text+= '<input type="hidden" value="' + res[1] + '" name="foto[]">';
						}
						if ( res[0] == 'error' ) {
							text+= 'Ошибка: ' + res[1];
						}
						text+= '<div class="del"><img src="/img/del.png" width="16" height="16" alt="Удалить" OnClick="if (confirm(\'Вы действительно хотите удалить эту фотографию?\')) $(\'#foto' + res[2] + '\').remove()"></div>';
						text+= '</div>';
						$('#result_upload').append(text);
					}
				});
			});
			
			function del_foto(f)
			{
				if ( confirm('Вы действительно хотите удалить эту фотографию?') )
					$(f).closest('div.foto').remove();
			}
		</script>



	<div class="clear"></div>


	<div class="title">Дополнительные характеристики</div>

	<div class="block w150">
		Ремонт:<br /><?=form_dropdown ( 'info[renovation]', $this->Mobject->form_data['renovation'], $this->validation->info['renovation'], 'style="width:100px"' );?>
	</div>
	<div class="block w150">
		Балкон:<br /><?=form_dropdown ( 'info[balcony]', $this->Mobject->form_data['balcony'], $this->validation->info['balcony'], 'style="width:100px"' );?>
	</div>
	<div class="block w150">
		Сан. узел:<br /><?=form_dropdown ( 'info[bathroom]', $this->Mobject->form_data['bathroom'], $this->validation->info['bathroom'], 'style="width:100px"' );?>
	</div>
	<div class="block w150">
		Окна выходят:<br /><?=form_dropdown ( 'info[window]', $this->Mobject->form_data['window'], $this->validation->info['window'], 'style="width:100px"' );?>
	</div>

	<div class="clear"></div>

	<div class="block w150">
		Телефон:<br /><?=form_dropdown ( 'info[phone]', $this->Mobject->form_data['phone'], $this->validation->info['phone'], 'style="width:100px"' );?>
	</div>
	<div class="block w150">
		Мебель:<br /><?=form_dropdown ( 'info[furniture]', $this->Mobject->form_data['furniture'], $this->validation->info['furniture'], 'style="width:100px"' );?>
	</div>
	<div class="block w150">
		Краткий комментарий:<br />
		<input name="info[comment]" type="text" value="<?=$this->validation->info['comment']?>" style="width:250px">
	</div>
	<div class="clear"></div>

	<div id="only_sale">
		<div class="block w150 ">
			Условия продажи:<br /><?=form_dropdown ( 'info[type]', $this->Mobject->form_data['type'], $this->validation->info['type'], 'style="width:100px"' );?>
		</div>
		<div class="block w150">
			Ипотека:<br /><?=form_dropdown ( 'info[mortgage]', $this->Mobject->form_data['mortgage'], $this->validation->info['mortgage'], 'style="width:100px"' );?>
		</div>
		<div class="block w150">
			Под офис:<br /><?=form_dropdown ( 'info[office]', $this->Mobject->form_data['office'], $this->validation->info['office'], 'style="width:100px"' );?>
		</div>
		<div class="block w150">
			Перепланировка:<br /><?=form_dropdown ( 'info[replan]', $this->Mobject->form_data['replan'], $this->validation->info['replan'], 'style="width:100px"' );?>
		</div>
		<div class="clear"></div>
	</div>

	<div class="block">
		Дополнительная информация (описание в свободной форме):<br />
		<textarea name="description" class="w500 h100"><?=$this->validation->description?></textarea>
	</div>

	<div class="clear"></div>


	<div class="title">Контактная информация</div>

	<div style="float:left">

		<div class="block">
			Контактное лицо <span class="red">*</span> :<br />
			<input name="name" type="text" value="<?=$this->validation->name?>" class="w200">
		</div>

		<div class="clear"></div>

		<div class="block">
			Контактный телефон <span class="red">*</span> :<br />
			<input name="phone" id="phone" type="text" value="<?=$this->validation->phone?>" class="w200">
			<script src="/js/jquery.maskedinput.js"></script>
			<script type="text/javascript">
				jQuery(function($){
					$("#phone").mask("(999) 999-99-99",{placeholder:" "});
				});
			</script>
		</div>

		<div class="clear"></div>

		<div class="block">
			Контактный e-mail:<br />
			<input name="email" type="text" value="<?=$this->validation->email?>" class="w200">
		</div>

	</div>

	<div style="float:left; margin-left:50px; width:375px; font-size:11px;">
		<p style="margin-bottom:15px;" class="red">Внимание!</p>
		<p style="margin-bottom:15px;">Все поля отмеченные обязательны для заполнения.</p>
		<p style="margin-bottom:15px;">Каждое объявление проходит ручную модерацию.</p>
		<p style="margin-bottom:25px;">Размещая объявление, Вы соглашаетесь со всеми вышеперечисленными условиями.</p>
		<? if ( $this->Mobject->id ) { ?>
			<input type="submit" value="Изменить объявление" class="button">
		<? } else { ?>
			<input type="submit" value="Разместить объявление" class="button">
		<? } ?>
	</div>


</form>












	<? $this -> load -> view ( 'foot' ); ?>
