
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
						<a href="/search/'.$this->uri->segment(2).'/'.$f.'/'.( $f == $sort['field'] && $sort['type'] == 'asc' ? 'desc' : 'asc' ).'">
							'.$name.' '.( $f == $sort['field'] ? ( $sort['type'] == 'asc' ? '&#9650;' : '&#9660;' ) : '' ).'
						</a>
					</th>';				}
			?>
		</tr>
		<? foreach ( $search as $r ) { ?>
			<tr>
				<td>
					<a href="/flat/info/<?=$r['id']?>"><?=$r['adr_ul']?>, <?=$r['adr_d']?></a>
					<a href="/dom/<?=$r['id_dom']?>">.</a>
				</td>
				<td align="center"><?=( $r['kk'] ? $r['kk'] : 'к' )?></td>
				<td nowrap align="center"><?=$r['pl_o']?> м<sup>2</sup></td>
				<td nowrap align="center"><?=$r['pl_j']?> м<sup>2</sup></td>
				<td nowrap align="center"><?=$r['pl_k']?> м<sup>2</sup></td>
				<td nowrap align="right"><a href="/flat/info/<?=$r['id']?>"><?=number_format($r['cena'],0,',',' ')?> р.</a></td>
				<td nowrap align="center"><?=$r['et']?> / <?=$r['storey']?></td>
				<td nowrap align="center"><?=$r['date_up']?></td>
			</tr>
		<? } ?>
	</table>

	<div class="pages"><?=$pages?></div>
