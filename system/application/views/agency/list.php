	<h1>Агентства недвижимости</h1>

	<p style="word-spacing:10px; margin-bottom:20px;" align="center">
		<? foreach ( $letters as $url => $name ) { ?>
			<? if ( $this_letter == $url ) { ?>
				<b><?=$name?></b>
			<? } else { ?>
				<a href="/agency/<?=$url?>"><?=$name?></a>
			<? } ?>
		<? } ?>
	</p>

	<table class="tab">
		<? foreach ( $agency as $r ) { ?>
		<tr>
			<td>
				<a href="/agency/info/<?=$r['id']?>"><?=$r['name']?></a>
				<p><?=$r['full_name']?></p>
			</td>
			<td>
				<p><span class="grey">Адрес: </span><?=$r['adr']?></p>
				<p><span class="grey">Телефон: </span><?=$r['tel']?></p>
			</td>
			<td>-</td>
		</tr>
		<? } ?>
	</table>



