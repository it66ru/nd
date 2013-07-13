
	<? $this -> load -> view ( 'head' , $head ); ?>

	<?=( $info['title'] ? '<h1>'.$info['title'].'</h1>' : '' )?>
	<?=( $info['description'] ? '<p class="w600">'.$info['description'].'</p>' : '' )?>

	<table class="tab w_full">
		<tr>
			<th><?=view_th('price','Цена',$sort,$this->uri->segment(2))?></th>
			<th><?=view_th('rooms','К',$sort,$this->uri->segment(2))?></th>
			<th><?=view_th('space_total','Площадь',$sort,$this->uri->segment(2))?></th>
			<th><?=view_th('space_living','Жилая',$sort,$this->uri->segment(2))?></th>
			<th><?=view_th('space_kitchen','Кухня',$sort,$this->uri->segment(2))?></th>
			<th>Адрес</th>
			<th><?=view_th('floor','Этаж',$sort,$this->uri->segment(2))?></th>
			<th><?=view_th('cdate','Дата',$sort,$this->uri->segment(2))?></th>
		</tr>
		<? foreach ( $search as $r ) { ?>
			<tr>
				<td nowrap align="right"><a href="/flat/info/<?=$r['id']?>"><?=number_format($r['price'],0,',',' ')?> р.</a></td>
				<td align="center"><?=( $r['rooms'] ? $r['rooms'] : 'к' )?></td>
				<td nowrap align="center">
					<? if ($r['space_total']) { ?>
						<?=$r['space_total']?> м<sup>2</sup>
					<? } else echo '-'; ?>
				</td>
				<td nowrap align="center">
					<? if ($r['space_living']) { ?>
						<?=$r['space_living']?> м<sup>2</sup>
					<? } else echo '-'; ?>
				</td>
				<td nowrap align="center">
					<? if ($r['space_kitchen']) { ?>
						<?=$r['space_kitchen']?> м<sup>2</sup>
					<? } else echo '-'; ?>
				</td>
				<td>
					<div class="show_block_disable" id="di<?=$r['id']?>">
						<span class="button" OnClick="show('di<?=$r['id']?>')">
							<img src="/img/dom.png" width="16" height="16" align="absmiddle">
							<?=$r['dom']['adr']?>
						</span>
						<div class="element w300">
							<div class="close" OnClick="show('di<?=$r['id']?>')">×</div>
							<div class="dom_info">
								<dl>
									<dt>Район</dt>
									<dd><?=$r['dom']['district']?></dd>
								</dl>
								<dl>
									<dt>Материал стен</dt>
									<dd><?=$r['dom']['material']?></dd>
								</dl>
								<dl>
									<dt>Тип дома</dt>
									<dd><?=$r['dom']['house_type']?></dd>
								</dl>
								<dl>
									<dt>Год постройки</dt>
									<dd><?=$r['dom']['year']?></dd>
								</dl>
							</div>
							<div class="dom_foto">
								<img src="/img/no_foto.png" width="150" height="100" alt="Фотографии отсутствуют"><br />
								<a href="/flat/map/<?=$r['id']?>">Объявление на карте</a><br />
								<a href="/flat/dom/<?=$r['id']?>">Объявления в этом доме</a>
							</div>
						</div>
					</div>
				</td>
				<td nowrap align="center"><?=$r['floor']?> / <?=$r['dom']['storey']?></td>
				<td nowrap align="center"><?=$r['cdate']?></td>
			</tr>
		<? } ?>
	</table>

	<div class="pages"><?=$pages?></div>

	<? $this -> load -> view ( 'foot' ); ?>

<?
	function view_th ( $f, $name, $sort, $s )
	{
		return '<a href="/search/'.$s.'/'.$f.'/'.( $f == $sort['field'] && $sort['type'] == 'asc' ? 'desc' : 'asc' ).'">
			'.$name.' '.( $f == $sort['field'] ? ( $sort['type'] == 'asc' ? '&#9650;' : '&#9660;' ) : '' ).'
		</a>';
	}
?>
