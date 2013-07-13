		<form action="/search/" method="post">
			<div id="search">
				<div id="sep1"></div>
				<div id="sep2"></div>
				<div id="sep3"></div>
				<div id="search_type">
					<span id="search_type_sale" class="search_object_checkbox">Продажа</span><span id="search_type_rent" class="search_object_checkbox">Аренда</span>
					<input type="hidden" id="type" name="type" value="<?=$search['type']?>">
					<script language="javascript">
						$(document).ready(function() {
							$($('#type').val() == 'sale' ? '#search_type_sale' : '#search_type_rent')
								.addClass('search_object_select');
							
							$("#search_type_sale").click(function() { 
								$('#type').val('sale');
								$('#search_type_sale').addClass('search_object_select');
								$('#search_type_rent').removeClass('search_object_select');
							});
							$("#search_type_rent").click(function() { 
								$('#type').val('rent');
								$('#search_type_sale').removeClass('search_object_select');
								$('#search_type_rent').addClass('search_object_select');
							});
						});
					</script>
				</div>
				<div id="search_district">
					<div class="title">Район:</div>
					<span id="district_select">выбрать</span>
<?
	$district = array (
		1  => 'Автовокзал',
		2  => 'Ботанический',
		3  => 'ВИЗ',
		4  => 'Вокзальный',
		5  => 'Втузгородок',
		7  => 'Елизавет',
		8  => 'ЖБИ',
		9  => 'Завокзальный',
		10 => 'Заречный',
		14 => 'Компрессорный',
		17 => 'Н. Сортировка',
		18 => 'Парковый',
		19 => 'Пионерский',
		22 => 'С. Сортировка',
		25 => 'Семь ключей',
		27 => 'Синие камни',
		29 => 'Уктус',
		30 => 'УНЦ',
		31 => 'Уралмаш',
		32 => 'Химмаш',
		33 => 'Центр',
		34 => 'Чермет',
		38 => 'Шарт. рынок',
		39 => 'Широкая речка',
		40 => 'Эльмаш',
		41 => 'Юго-Западный'
	);
/*
	echo '<br>';
	foreach ( $district as $id => $name )
	{
		echo '<input name="r[]" type="checkbox" value="'.$id.'" '.(in_array($id,$search['r']) ? 'checked' : '' ).'> '.$name.'<br />';
	}
*/
?>
					<div id="district_list">
						<div class="block">
						<? foreach ($district as $id => $name) { ?>
							<input name="r[]" type="checkbox" value="<?=$id?>" <?=(in_array($id,$search['r']) ? 'checked' : '' )?>> <?=$name?> 
							<br />
							<? if ($id == 19) { ?>
								</div>
								<div class="block">
							<? } ?>
						<? } ?>
						</div>
					</div>
					<script language="javascript">
						$(document).ready(function(){
							$("#district_select").click(function() { $('#district_list').toggle(); });
						});
					</script>
				</div>
				<div id="search_rooms">
					<div class="title">Комнат:</div>
					<span class="search_object_checkbox">
						<input name="k[]" type="checkbox" value="1" <?=( in_array('1',$search['k']) ? 'checked' : '' )?>> 1К
					</span>
					&nbsp;
					<span class="search_object_checkbox">
						<input name="k[]" type="checkbox" value="2" <?=( in_array('2',$search['k']) ? 'checked' : '' )?>> 2К 
					</span>
					&nbsp;
					<span class="search_object_checkbox">
						<input name="k[]" type="checkbox" value="3" <?=( in_array('3',$search['k']) ? 'checked' : '' )?>> 3К 
					</span>
					&nbsp;
					<span class="search_object_checkbox">
						<input name="k[]" type="checkbox" value="4" <?=( in_array('4',$search['k']) ? 'checked' : '' )?>> 4К 
					</span>
					&nbsp;
					<span class="search_object_checkbox">
						<input name="k[]" type="checkbox" value="5" <?=( in_array('5',$search['k']) ? 'checked' : '' )?>> > 4
					</span>
					<script language="javascript">
						$(document).ready(function() {
							$("#search_rooms").find('span.search_object_checkbox').each(function(index) {
								if ($(this).find('input[type=checkbox]').attr('checked'))
									$(this).addClass('search_object_select');
								$(this).find('input[type=checkbox]').hide();
							});
							$("#search_rooms").find('span.search_object_checkbox').click(function() { 
								$(this).toggleClass('search_object_select');
								$(this).find('input[type=checkbox]').attr('checked', $(this).hasClass('search_object_select'));
							});
						});
					</script>
				</div>
				<div id="search_storey">
					<div class="title">Этаж</div>
					от <input name="et_ot" type="num" value="<?=( $search['et_ot'] ? $search['et_ot'] : '' )?>" class="w30 tac"> &nbsp;
					до <input name="et_do" type="num" value="<?=( $search['et_do'] ? $search['et_do'] : '' )?>" class="w30 tac"> &nbsp;
					<input name="et_n1" type="checkbox" value="1" <?=( $search['et_n1'] ? 'checked' : '' )?>> не 1-й &nbsp;
					<input name="et_np" type="checkbox" value="1" <?=( $search['et_np'] ? 'checked' : '' )?>> не посл.
				</div>
				<div id="search_area">
					<div class="title">Площадь:</div>
					<span class="w50" style="display:inline-block">Общая:</span>
					от <input name="pl_o_ot" type="num" value="<?=( $search['pl_o_ot'] ? $search['pl_o_ot'] : '' )?>" class="w40 tac"> &nbsp;
					до <input name="pl_o_do" type="num" value="<?=( $search['pl_o_do'] ? $search['pl_o_do'] : '' )?>" class="w40 tac"> &nbsp;м<sup>2</sup><br />
					<span class="w50" style="display:inline-block">Жилая:</span>
					от <input name="pl_j_ot" type="num" value="<?=( $search['pl_j_ot'] ? $search['pl_j_ot'] : '' )?>" class="w40 tac"> &nbsp;
					до <input name="pl_j_do" type="num" value="<?=( $search['pl_j_do'] ? $search['pl_j_do'] : '' )?>" class="w40 tac"> &nbsp;м<sup>2</sup><br />
					<span class="w50" style="display:inline-block">Кухни:</span>
					от <input name="pl_k_ot" type="num" value="<?=( $search['pl_k_ot'] ? $search['pl_k_ot'] : '' )?>" class="w40 tac"> &nbsp;
					до <input name="pl_k_do" type="num" value="<?=( $search['pl_k_do'] ? $search['pl_k_do'] : '' )?>" class="w40 tac"> &nbsp;м<sup>2</sup>
				</div>
				<div id="search_price">
					<div class="title">Цена:</div>
					от <input name="price_ot" type="num" value="<?=( $search['price_ot'] ? $search['price_ot'] : '' )?>" class="w50 tac"> &nbsp;тыс. руб.<br />
					до <input name="price_do" type="num" value="<?=( $search['price_do'] ? $search['price_do'] : '' )?>" class="w50 tac"> &nbsp;тыс. руб.<br />
					<input type="submit" name="search" value="Найти" class="button">
					&nbsp; &nbsp;
					<a href="/map" style="color: #B00; font-weight: bold; font-size: 14px;">На карте</a>
				</div>
			</div>
		</form>
