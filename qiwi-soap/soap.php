<?php

class checkBill {
	public $login;           // string
	public $password;        // string
	public $txn;             // string
}

class checkBillResponse {
	public $user;            // string
	public $amount;          // string
	public $date;            // string
	public $lifetime;        // string
	public $status;          // int
}

class getBillList {
	public $login;            // string
	public $password;         // string
	public $dateFrom;         // string
	public $dateTo;           // string
	public $status;           // int
}

class getBillListResponse {
	public $txns;             // string
	public $count;            // int
}

class cancelBill {
	public $login;            // string
	public $password;         // string
	public $txn;              // string
}

class cancelBillResponse {
	public $cancelBillResult; // int
}

class createBill {
	public $login;            // string логин (id) магазина
	public $password;         // string пароль для магазина
	public $user;             // string идентификатор пользователя (номер телефона)
	public $amount;           // string сумма, на которую выставляется счет (разделитель «.»);
	public $comment;          // string комментарий к счету, который увидит пользователь (максимальная длина 255 байт);
	public $txn;              // string уникальный идентификатор счета (максимальная длина 30 байт);
	public $lifetime;         // string время действия счета (в формате dd.MM.yyyy HH:mm:ss);
	public $alarm;            // int    отправить оповещение пользователю (1 - уведомление SMS-сообщением, 2 - уведомление звонком, 0 - не оповещать)
	public $create;           // boolean флаг для создания нового пользователя (если он не зарегистрирован в системе).
}

class createBillResponse {
	public $createBillResult; // int
}

# расширение стандартного soap-клиента
class QiwiSoap extends SoapClient
{
	public $error;
	
	public function __construct()
	{
		$wsdl = 'https://ishop.qiwi.ru/docs/IShopServerWS.wsdl';
		parent::__construct($wsdl);
		
		$this->error = array (
			0   => 'Успех',
			13  => 'Сервер занят, повторите запрос позже',
			150 => 'Ошибка авторизации (неверный логин/пароль)',
			210 => 'Счет не найден',
			215 => 'Счет с таким txn-id уже существует',
			241 => 'Сумма слишком мала',
			242 => 'Превышена максимальная сумма платежа – 15 000р.',
			278 => 'Превышение максимального интервала получения списка счетов',
			298 => 'Агента не существует в системе',
			300 => 'Неизвестная ошибка',
			330 => 'Ошибка шифрования',
			370 => 'Превышено максимальное',
		);
	}




}

	$qs = new QiwiSoap();
	
	$cb = new createBill();
	$cb->login    = '229549';            // string
	$cb->password = 'geXzH26Wp5';         // string
	$cb->user     = '9263604301';             // string
	$cb->amount   = '100.00';           // string
	$cb->comment  = 'коммент';          // string
	$cb->txn      = '1';              // string
	$cb->lifetime = '22.06.2013 00:00:00';         // string
	$cb->alarm    = 1;            // int
	$cb->create   = 0;           // boolean
	
	$r = $qs->createBill($cb);
	
	echo '<pre>'.print_r($qs->__getFunctions(), true).'</pre>';
	echo '<pre>'.print_r($r, true).'</pre>';


?>
