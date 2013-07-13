
	<? $this -> load -> view ( 'head' ); ?>

	<h1>Вход</h1>

	<form method="post">
		<div class="form_row">
			<div class="form_label w70">E-mail:</div>
			<div class="form_input w150"><input type="text" name="email" value="" size="30" /></div>
		</div>
		<div class="form_row">
			<div class="form_label w70">Пароль:</div>
			<div class="form_input w150"><input type="password" name="password" value="" size="30" /></div>
		</div>
		<div class="form_button w220"><input type="submit" value="Войти" class="submit" /></div>
	</form>

	<a href="/registration">Зарегистрироваться</a>
	<br /><br />
	<a href="/login/change">Восстановить пароль</a>


	<? $this -> load -> view ( 'foot' ); ?>