<?
	# временная мера. изменение УРЛ

	$page = $this -> uri -> segment(2);
	if ( $page!='objects' && $page!='comments' ) $page = 'info';

?>


	<h1 class="red">АН &laquo;<?=$agency['name']?>&raquo;</h1>

	<ul class="bookmark">
		<? foreach ( $sections as $s => $name ) {
			if ( $s == $page ) echo '<li class="current">'.$name.'</li>';
			else echo '<li><a href="/agency/'.$s.'/'.$agency['id'].'">'.$name.'</a></li>';
		} ?>
	</ul>

	<table class="tab">
		<tr>
			<td class="t1">Название</td>
			<td class="t2"><?=$agency['full_name']?></td>
		</tr>
		<tr>
			<td class="t1">Адрес</td>
			<td class="t2"><?=$agency['adr']?></td>
		</tr>
		<tr>
			<td class="t1">Телефон</td>
			<td class="t2"><?=$agency['tel']?></td>
		</tr>
		<tr>
			<td class="t1">Сайт</td>
			<td class="t2"><?=$agency['www']?></td>
		</tr>
	</table>

	<div class="description">
		<?=$agency['description']?>
	</div>



