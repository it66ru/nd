<?php

	class Dom extends Controller {

		function __construct()
		{
			parent::Controller();
		}

		function _remap ()
		{
			# если второй сегмент число
			if ( preg_match("|^[\d]+$|", $this->uri->segment(2)) )
			{
				# получаем данные о доме
				$dom = $this -> Mdom -> info ( $this->uri->segment(2) );
				# делаем 301 редирект
				redirect ( 'dom/info/'.$dom['id'], 'location', 301 );			}
			# инфомация о доме
			elseif ( $this->uri->segment(2) == 'info' ) {				$this -> info ( $this->uri->segment(3) );			}
		}

		function info ( $id )
		{			$dom = $this -> Mdom -> info ( $id );
			echo '<pre>'.print_r($dom,true).'</pre>';
		}

		# список квартир в этом доме
		function flat ( $id )
		{
			# заголовок
			$Dhead['title'] = $data['agency']['name'].' - агентство недвижимости, г. Екатеринбург';
			$Dhead['keyw'] = 'недвижимость, Екатеринбург';
			$Dhead['desc'] = 'Поиск недвижимости в Екатеринбурге';
			$Dhead['search'] = $param;

			# отображение
			$this -> load -> view ( 'head' , $Dhead );
			$this -> load -> view ( 'agency_id' , $data );
			$this -> load -> view ( 'foot' );
		}

		# фотки дома
		function foto ( $id )
		{

		}
	}

?>
