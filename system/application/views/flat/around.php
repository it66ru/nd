<?

	$sale_object = array (
		'Продажа комнаты',
		'Продажа однокомнатной квартиры',
		'Продажа двух комнатной квартиры',
		'Продажа трехкомнатной квартиры',
		'Продажа четырех комнатной квартиры',
		'Продажа многокомнатной квартиры',
	);
	if ( $object['kk'] > 5 ) $object['kk'] = 5;

?>

	<? $this -> load -> view ( 'head' , $head ); ?>

	<? $this -> load -> view ( 'flat/top_object' , $object ); ?>

	<div class="clear"></div>

	<ul class="bookmark">
		<? foreach ( $sections as $url => $name ) { ?>
			<? if ( $this->uri->segment(2) == $url ) { ?>
				<li class="current"><?=$name?></li>
			<? } else { ?>
				<li><a href="/flat/<?=$url?>/<?=$object['id']?>"><?=$name?></a></li>
			<? } ?>
		<? } ?>
	</ul>


	<? if ( $objects ) { ?>

		<table class="tab w_full">
			<tr>
				<th>Адрес</th>
				<?
					$field = array (
						'kk' => 'К',
						'pl_o' => 'Площадь',
						'pl_j' => 'Жилая',
						'pl_k' => 'Кухня',
						'cena' => 'Цена',
						'et' => 'Этаж',
						'date_up' => 'Дата',
					);
					foreach ( $field as $f => $name )
					{
						echo '<th>
							<a href="/flat/around/'.$object['id'].'/'.$f.'/'.( $f == $sort['field'] && $sort['type'] == 'asc' ? 'desc' : 'asc' ).'">
								'.$name.' '.( $f == $sort['field'] ? ( $sort['type'] == 'asc' ? '&#9650;' : '&#9660;' ) : '' ).'
							</a>
						</th>';
					}
				?>
			</tr>
			<? foreach ( $objects as $r ) { ?>
				<tr>
					<td><a href="/flat/info/<?=$r['id']?>"><?=$r['dom']['adr']?></a></td>
					<td align="center"><?=( $r['kk'] ? $r['kk'] : 'к' )?></td>
					<td nowrap align="center"><?=$r['pl_o']?> м<sup>2</sup></td>
					<td nowrap align="center"><?=$r['pl_j']?> м<sup>2</sup></td>
					<td nowrap align="center"><?=$r['pl_k']?> м<sup>2</sup></td>
					<td nowrap align="right"><a href="/flat/info/<?=$r['id']?>"><?=number_format($r['cena'],0,',',' ')?> р.</a></td>
					<td nowrap align="center"><?=$r['et']?> / <?=$r['dom']['storey']?></td>
					<td nowrap align="center"><?=$r['date_up']?></td>
				</tr>
				<? } ?>
		</table>

	<? } else { ?>

		Похожие объекты не найдены

	<? } ?>


	<? $this -> load -> view ( 'foot' ); ?>