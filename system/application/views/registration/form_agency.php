
	<? $this -> load -> view ( 'head' ); ?>

	<h1>Регистрация</h1>

	<? if ( $this->validation->error_string ) { ?>
		<div class="block_error"><?=$this->validation->error_string?></div>
	<? } ?>

	<ul class="bookmark">
		<li><a href="/registration/user">Частное лицо</a></li>
		<li class="current"><a href="/registration/agency">Агентство недвижимости</a></li>
	</ul>

	<form method="post">
		<div class="form_row">
			<div class="form_label w150">Название организации: <r>*</r></div>
			<div class="form_input w200"><input type="text" name="username" value="<?=$this->validation->username;?>" size="50" /></div>
		</div>
		<div class="form_row">
			<div class="form_label w150">Контактное лицо: <r>*</r></div>
			<div class="form_input w200"><input type="text" name="contact" value="<?=$this->validation->contact;?>" size="50" /></div>
		</div>
		<div class="form_row">
			<div class="form_label w150">Телефон: <r>*</r></div>
			<div class="form_input w200"><input type="text" name="phone" value="<?=$this->validation->phone;?>" size="50" /></div>
		</div>
		<div class="form_row">
			<div class="form_label w150">E-mail: <r>*</r></div>
			<div class="form_input w200"><input type="text" name="email" value="<?=$this->validation->email;?>" size="50" /></div>
		</div>
		<div class="form_row">
			<div class="form_label w150">Адрес:</div>
			<div class="form_input w200"><input type="text" name="address" value="<?=$this->validation->address;?>" size="50" /></div>
		</div>
		<div class="form_row">
			<div class="form_label w150">Сайт:</div>
			<div class="form_input w200"><input type="text" name="site" value="<?=$this->validation->site;?>" size="50" /></div>
		</div>
		<div class="form_row">
			<div class="form_label w150">Пароль: <r>*</r></div>
			<div class="form_input w200"><input type="password" name="password" value="" size="50" /></div>
		</div>
		<div class="form_row">
			<div class="form_label w150">Подтверждение: <r>*</r></div>
			<div class="form_input w200"><input type="password" name="passconf" value="" size="50" /></div>
		</div>
		<div class="form_button w350">
			<input type="hidden" name="type" value="agency" />
			<input type="submit" name="confirm" value="Регистрация" class="submit" />
		</div>
	</form>

	<? $this->load->view('foot'); ?>
