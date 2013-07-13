<?php

	class Mailing extends Controller {

		function __construct()
		{
			parent::Controller();
		}

		function index()
		{
$text = 'Здравствуйте!

Предлагаем, разместить объявления о продаже Ваших объектов в нашей базе http://pn66.ru.
Объявления размещаются абсолютно бесплатно!
К каждому объявлению можно прикрепить неограниченной количество фотографий.
Возможно настроить автоматическую подачу и обновление с вашего сайта (в формате XML)


--
С уважением, Павел С.';


			$this -> db -> where ( 'send', 0 );
			$query = $this -> db -> get ( 'nd_mailing', 1 );
			if ( $query -> num_rows() )
			{
				$r = $query -> row_array();

				$this -> load -> library('email');

				$config['wordwrap'] = FALSE;
				$this -> email -> initialize ( $config );

				$this -> email -> from ( 'info@pn66.ru', 'PN66.ru' );
				$this -> email -> to ( $r['email'] );
				$this -> email -> subject ( 'PN66.ru – бесплатная реклама Ваших объектов' );
				$this -> email -> message ( $text );
				$this -> email -> send();

				# пишем в базу
				$this -> db -> set ( 'debug', $this -> email -> print_debugger() );
				$this -> db -> set ( 'send', 1 );
				$this -> db -> where ( 'id', $r['id'] );
				$this -> db -> update ( 'nd_mailing' );

				echo $r['email'];
			}
		}

	}

?>