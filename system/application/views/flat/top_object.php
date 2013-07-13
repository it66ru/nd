<?
	$type_object = array (
		'rent' => 'Аренда',
		'sale' => 'Продажа',
	);
	
	$sale_object = array (
		'комнаты',
		'однокомнатной квартиры',
		'двух комнатной квартиры',
		'трехкомнатной квартиры',
		'четырех комнатной квартиры',
		'многокомнатной квартиры',
	);
?>

<? if ($this->Mobject->data['status'] == 'removed') { ?>
	<div class="flat_block_removed">
		Это объявление удалено. Оно более не актуально.
	</div>
<? } else { ?>
	<div class="flat_block_top">
		<div class="info">
			<p class="grey"><?=$type_object[$this->Mobject->data['type']]?> <?=$sale_object[$this->Mobject->data['rooms']]?></p>
			<p class="big"><?=$this->Mobject->building['adr']?></p>
			<p>
				<span class="grey">Дом:</span> <?=$this->Mobject->building['house_type']?> / <?=$this->Mobject->building['material']?>, 
				<span class="grey">Этаж:</span> <?=$this->Mobject->data['floor']?> / <?=$this->Mobject->building['storey']?>
			</p>
			<p>
				<span class="grey">Площадь:</span> <?=$this->Mobject->data['space_total']?> м<sup>2</sup>
				<? if ($this->Mobject->data['space_living']) { ?>
					, жилая <?=$this->Mobject->data['space_living']?> м<sup>2</sup>
				<? } ?>
				<? if ($this->Mobject->data['space_kitchen']) { ?>
					, кухня <?=$this->Mobject->data['space_kitchen']?> м<sup>2</sup>
				<? } ?>
			</p>
			<? if ( $this->Mobject->data['info']['comment'] ) { ?>
				<p><span class="grey">Комментарий:</span> <?=$this->Mobject->data['info']['comment']?></p>
			<? } ?>
		</div>
		<div class="price">
			<p class="grey">Цена</p>
			<p class="big red"><?=$this->Mobject->data['price']?> р.</p>
			<p class="grey" style="font-size:11px; margin-top:10px;">за кв.м</p>
			<p class="red"><?=number_format(round(str_replace(' ', '', $this->Mobject->data['price'])/$this->Mobject->data['space_total']),0,',',' ')?> р.</p>
		</div>
		<div class="contact">
			<p class="grey">Контактная информация</p>
			<p class="big"><?=$this->Mobject->data['phone']?></p>
			<p><?=$this->Mobject->data['name']?></p>
			<p class="grey" style="font-size:11px; margin-top:15px;"><span class="red">Обязательно</span> сообщите автору, что вы нашли информацию на сайте <span class="red">PN66.ru</span></p>
		</div>
	</div>
<? } ?>

<? /*
	<div class="flat_block_top_advert">
		<script type="text/javascript">
			<!--
			google_ad_client = "ca-pub-3392689871181236";
			google_ad_slot = "9593473762";
			google_ad_width = 728;
			google_ad_height = 90;
			//-->
		</script>
		<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	</div>
*/ ?>
