
	<h1 class="red">АН &laquo;<?=$agency['name']?>&raquo;</h1>

	<ul class="bookmark">
		<? foreach ( $sections as $s => $name ) {
			if ( $s == $this->uri->segment(2) ) echo '<li class="current">'.$name.'</li>';
			else echo '<li><a href="/agency/'.$s.'/'.$agency['id'].'">'.$name.'</a></li>';
		} ?>
	</ul>

	<p>Ваш отзыв может быть первым!</p>

	<br /><br />

	<h2>Добавить отзыв</h2>
	<br />

	<? if ( isset($add_success) ) { ?>
		<div class="block_ok">Ваш отзыв успешно добавлен и будет опубликован в ближайшее время</div>
	<? } ?>

	<? if ( $this -> auth -> login() ) { ?>

		<form method="post">

			<div class="block" style="width:550px;">
				<textarea name="comment" class="w_full h100"></textarea>
			</div>
			<div style="width:580px;">
				<div style="float:left">
					<span class="red">Внимание!</span> Каждое объявление проходит ручную модерацию.
				</div>
				<div style="float:right">
					<input type="submit" value="Добавить отзыв" class="button" />
				</div>
			</div>



		</form>

	<? } else { ?>

		<p>Для того, чтобы опубликовать отзыв Вам необходимо <a href="/login">авторизоваться</a> или <a href="/registration">зарегистрироваться</a>.</p>

	<? } ?>




