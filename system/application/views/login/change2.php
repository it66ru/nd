
	<? $this -> load -> view ( 'head' ); ?>

	<h1>Восстановление пароля</h1>

	<? if ( isset($success) ) { ?>

		<div class="block_good"><p><?=$success?></p></div>
		<br /><br />
		<a href="/login">Перейти на страницу авторизации</a>

	<? } elseif ( isset( $not_access ) ) { ?>

		<div class="block_error"><p><?=$not_access?></p></div>

	<? } else { ?>

		<? if ( isset($error) ) { ?>
			<div class="block_error"><p><?=$error?></p></div>
		<? } ?>

		<form method="post">
			<div class="form_row">



				<div class="form_label w100">Новый пароль:</div>
				<div class="form_input w150"><input type="password" name="pass1" value="" size="30" /></div>
			</div>
			<div class="form_row">
				<div class="form_label w100">Подтверждение:</div>
				<div class="form_input w150"><input type="password" name="pass2" value="" size="30" /></div>
			</div>
			<div class="form_button w250"><input type="submit" value="Сохранить" class="submit" /></div>
		</form>

	<? } ?>


	<? $this -> load -> view ( 'foot' ); ?>