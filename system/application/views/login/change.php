
	<? $this -> load -> view ( 'head' ); ?>

	<h1>Восстановление пароля</h1>

	<? if ( isset($success) ) { ?>

		<div class="block_good"><p><?=$success?></p></div>

	<? } else { ?>

		<? if ( isset($error) ) { ?>
			<div class="block_error"><p><?=$error?></p></div>
		<? } ?>

		<form method="post">
			<div class="form_row">
				<div class="form_label w70">E-mail:</div>
				<div class="form_input w150"><input type="text" name="email" value="" size="30" /></div>
			</div>
			<div class="form_button w220"><input type="submit" value="Восстановить" class="submit" /></div>
		</form>

		<a href="/registration">Зарегистрироваться</a>
		<br /><br />
		<a href="/login">Авторизоваться</a>

	<? } ?>


	<? $this -> load -> view ( 'foot' ); ?>