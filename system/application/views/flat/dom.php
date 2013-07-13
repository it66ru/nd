
	<? $this->load->view('head', $head); ?>

	<? $this->load->view('flat/top_object'); ?>

	<div class="clear"></div>

	<ul class="bookmark">
		<? foreach ( $sections as $url => $name ) { ?>
			<? if ( $this->uri->segment(2) == $url ) { ?>
				<li class="current"><?=$name?></li>
			<? } else { ?>
				<li><a href="/flat/<?=$url?>/<?=$this->Mobject->id?>"><?=$name?></a></li>
			<? } ?>
		<? } ?>
	</ul>

	<script type="text/javascript">
		$(document).ready(function() {
			$("a[rel=example_group]").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'titlePosition' 	: 'over',
			});
		});
	</script>

	<div style="float:left; width:308px;">
		<? if ( 0 && $foto ) { ?>
			<div style="border: 1px solid #ddd; text-align:center; margin:1px;">
				<a rel="example_group" href="/foto_dom/<?=$foto[0]['dom']?>/large/<?=$foto[0]['foto']?>"><img src="/foto_dom/<?=$foto[0]['dom']?>/middle/<?=$foto[0]['foto']?>" alt="Фотография 1"></a>
			</div>
			<? foreach ( $foto as $f ) { ?>
			<div style="border: 1px solid #ddd; text-align:center; float:left; width:150px; margin:1px;">
				<a rel="example_group" href="/foto_dom/<?=$f['dom']?>/large/<?=$f['foto']?>"><img src="/foto_dom/<?=$f['dom']?>/small/<?=$f['foto']?>"></a>
			</div>
			<? } ?>
		<? } else { ?>
		<img src="/img/no_foto.png" width="300" height="200" alt="Фотографии отсутствуют" style="border: 1px solid #ddd">
		<? } ?>
		<div class="clear"></div>
	</div>

	<div style="float:left; margin-left:20px; width:400px;">
		<p class="red" style="font-size:16px; margin-bottom:10px">Характеристики дома</p>
		<p style="line-height: 20px;">
			&nbsp;–&nbsp; Район: <?=$this->Mobject->building['district']?><br />
			&nbsp;–&nbsp; Адрес: <?=$this->Mobject->building['adr']?><br />
			&nbsp;–&nbsp; Тип дома: <?=$this->Mobject->building['house_type']?><br />
			&nbsp;–&nbsp; Материал стен: <?=$this->Mobject->building['material']?><br />
			&nbsp;–&nbsp; Год постройки: <?=$this->Mobject->building['year']?><br />
			&nbsp;–&nbsp; Этажность: <?=$this->Mobject->building['storey']?><br />
		</p>
		<? /*
		<p class="red" style="font-size:16px; margin:20px 0 10px">Средняя стоимость кв. м.</p>
		<table class="tab">
			<? foreach ( $kk as $k => $title ) if ( $k!=0 && ( isset($objects_cena[$k]) && isset($objects_pl[$k]) ) ) { ?>
				<tr>
					<td><?=$title?></td>
					<td align="right"><?=number_format($objects_cena[$k]/$objects_pl[$k],0,',',' ')?> р.</td>
				</tr>
			<? } ?>
			<tr class="no_border">
				<td><b>Средняя стоимость</b></td>
				<td><b><?=number_format(array_sum($objects_cena)/array_sum($objects_pl),0,',',' ')?> р.</b></td>
			</tr>
		</table>
		*/ ?>
	</div>

	<div class="clear"></div>

	<? foreach ( $kk as $k => $title ) if ( isset($objects_on_sale[$k]) ) { ?>
		<p class="red" style="font-size:16px; margin:20px 0 10px"><?=$title?> в этом доме</p>
		<table class="tab">
			<tr>
				<th>К</th>
				<th>Площадь</th>
				<th>Жилая</th>
				<th>Кухня</th>
				<th>Цена</th>
				<th>кв. м.</th>
				<th>Этаж</th>
				<th>Выставлено</th>
				<th>Удалено</th>
			</tr>
			<? foreach ( $objects_on_sale[$k] as $r ) { ?>
			<tr>
				<td align="center"><?=( $r['kk'] ? $r['kk'] : 'к' )?></td>
				<td nowrap align="center"><?=$r['pl_o']?> м<sup>2</sup></td>
				<td nowrap align="center"><?=$r['pl_j']?> м<sup>2</sup></td>
				<td nowrap align="center"><?=$r['pl_k']?> м<sup>2</sup></td>
				<td nowrap align="right"><a href="/flat/info/<?=$r['id']?>"><?=number_format($r['cena'],0,',',' ')?> р.</a></td>
				<td nowrap align="right"><a href="/flat/info/<?=$r['id']?>" style="color:#c00"><?=number_format($r['cena']/($r['pl_o']?$r['pl_o']:$r['pl_j']),0,',',' ')?> р.</a></td>
				<td nowrap align="right"><?=$r['et']?> / <?=$r['dom']['storey']?></td>
				<td nowrap align="center"><?=$r['date_new']?></td>
				<td nowrap align="center"><?=( $r['date_del']!='0000-00-00' ? $r['date_del'] : '' )?></td>
			</tr>
			<? } ?>
		</table>
	<? } ?>

	<? $this -> load -> view ( 'foot' ); ?>
