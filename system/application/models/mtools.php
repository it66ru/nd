<?php

class Mtools extends Model {

	function __construct()
	{
		parent::Model();
	}

	# делает правильный телефон
	function phone ( $tel )
	{
		$code = '343';

		# номер в котором только цифры (без пробелов, черточек и т.д.)
		$num = preg_replace ( '/(\D+)/i', '', $tel );

		# делаем для всех равную длинну - 10 цифр
		if ( strlen($num) == 11 ) $num = substr ( $num, 1 );
		if ( strlen($num) == 7 )  $num = $code.$num;

		# ставим скобки и черточки в нужном месте
		$phone = preg_replace ( '/(\d{3})(\d{3})(\d{2})(\d{2})/i', '(\\1) \\2-\\3-\\4', $num );

		return $phone;
	}

}

?>