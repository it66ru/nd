
	<a name="top"></a>
	<h1 class="red">АН &laquo;<?=$agency['name']?>&raquo;</h1>

	<ul class="bookmark">
		<? foreach ( $sections as $s => $name ) {
			if ( $s == $this->uri->segment(2) ) echo '<li class="current">'.$name.'</li>';
			else echo '<li><a href="/agency/'.$s.'/'.$agency['id'].'">'.$name.'</a></li>';
		} ?>
	</ul>

	<div class="pages">
		Таблица &nbsp; &nbsp; <a href="/agency/objects/<?=$agency['id']?>/map">На карте</a>
	</div>

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
						<a href="/agency/objects/'.$agency['id'].'/'.$f.'/'.( $f == $sort['field'] && $sort['type'] == 'asc' ? 'desc' : 'asc' ).'">
							'.$name.' '.( $f == $sort['field'] ? ( $sort['type'] == 'asc' ? '&#9650;' : '&#9660;' ) : '' ).'
						</a>
					</th>';
				}
			?>
		</tr>
		<? foreach ( $flat_sale as $r ) { ?>
			<tr>
				<td>
					<a href="/flat/info/<?=$r['id']?>"><?=$r['dom']['adr_ul']?>, <?=$r['dom']['adr_d']?></a>
					<a href="/dom/<?=$r['id_dom']?>">.</a>
				</td>
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




