
	<div id="auth">

		<? if ( $this->auth->login() ) { ?>
			<div id="user_name">
				<?=$this->auth->user['username']?>
				<a href="/login/out">выход</a>
			</div>
		<? } else { ?>
			<div id="no_auth">
				Впервые на сайте? <a href="/registration">Регистрация</a> займёт меньше минуты!
			</div>
		<? } ?>

		<img src="/img/add.png" width="16" height="16" align="absmiddle"> <a href="/my/object/add" style="color:#c00">Подать объявление</a>

		&nbsp; &nbsp;

		<img src="/img/key.png" width="16" height="16" align="absmiddle"> <a href="/my">Личный кабинет</a>

	</div>

