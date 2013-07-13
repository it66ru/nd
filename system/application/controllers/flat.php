<?php

	class Flat extends Controller {

		var $dir = 'falt_sale/';   // папка для вьюшек

		function __construct()
		{
			parent::Controller();

			$this -> sections = array (
				'info'   => 'Информация',
				'map'    => 'На карте',
				'around' => 'Похожие предложения',
#				'price'  => 'История цены',
				'dom' => 'Данные о доме'
			);
		}

		# информация об объекте
		function info ( $id = 0 )
		{
			# информация об объекте
			$this->Mobject->id = $id;
			# если объект не найден, то выводим заглушку
			if ( !$this->Mobject->get() ) return $this -> _not_found();

			# доп. инфо
			$data['i'] = array (
				'renovation' => 'Ремонт',
				'balcony'    => 'Балкон',
				'bathroom'   => 'Сан. узел',
				'window'     => 'Окна выходят',
				'phone'      => 'Телефон',
				'furniture'  => 'Мебель',
				'comment'    => 'Комментарий',
				'type'       => 'Условия продажи',
				'mortgage'   => 'Ипотека',
				'office'     => 'Под офис',
				'replan'     => 'Перепланировка',
			); 

			# список вкладок
			$data['sections'] = $this->sections;

			# получаем параметры поиска
			$Sid = $this->session->userdata ( 'session_id' );
			$data['head']['search'] = $this->Msearch->get_param('');

			# заголовок
			$data['head']['title'] = 'PN66.ru - Поиск недвижимости в Екатеринбурге';
			$data['head']['keyw'] = 'недвижимость, Екатеринбург';
			$data['head']['desc'] = 'Поиск недвижимости в Екатеринбурге';
/*
			# property - нужно для Facebook
			$data['head']['property']['title'] = 'Продается '.$data['object']['kk'].'-комнатная квартира';
			$data['head']['property']['url']   = 'http://pn66.ru/flat/info/'.$data['object']['id'];
			$data['head']['property']['image'] = $data['foto'] ? 'http://pn66.ru/foto/'.$data['foto'][0]['object'].'/small/'.$data['foto'][0]['foto'] : 'http://pn66.ru/img/logo_pn66.png';
			$data['head']['property']['latitude'] = $data['object']['dom']['yaLat'];
			$data['head']['property']['longitude'] = $data['object']['dom']['yaLng'];
			$data['head']['property']['description'] = $data['object']['dom']['adr'].' - '.$data['object']['dom']['type'].' / '.$data['object']['dom']['material'].', Этаж: '.$data['object']['et'].'/'.$data['object']['dom']['storey'].', Площадь: '.$data['object']['pl_o'].'/'.$data['object']['pl_j'].'/'.$data['object']['pl_k'].' кв.м. Цена: '.number_format($data['object']['cena'],0,',',' ').' р.';
*/
			# отображение
			$this -> load -> view ( 'flat/info' , $data );
		}

		# объект на карте
		function map ( $id = 0 )
		{
			# информация об объекте
			$this->Mobject->id = $id;
			# если объект не найден, то выводим заглушку
			if ( !$this->Mobject->get() ) return $this -> _not_found();

			# список вкладок
			$data['sections'] = $this->sections;

			# получаем параметры поиска
			$Sid = $this->session->userdata('session_id');
			$data['head']['search'] = $this->Msearch->get_param('');

			# заголовок
			$data['head']['title'] = 'PN66.ru - Поиск недвижимости в Екатеринбурге';
			$data['head']['keyw'] = 'недвижимость, Екатеринбург';
			$data['head']['desc'] = 'Поиск недвижимости в Екатеринбурге';

			# отображение
			$this->load->view('flat/map', $data);
		}

		# похожие предложения
		function around ( $id = 0, $sort_field='cena', $sort_type='asc' )
		{
			return $this->info($id);
		}

		# динамика цен
		function price ( $id = 0 )
		{			return $this->info($id);		}

		# информация о доме
		function dom ( $id = 0 )
		{
			# информация об объекте
			$this->Mobject->id = $id;
			# если объект не найден, то выводим заглушку
			if ( !$this->Mobject->get() ) return $this -> _not_found();

/*
			# фотки дома
			$data['foto'] = $this -> Mdom -> foto ( $data['object']['dom']['id'] );

			# квартиры выставленные на продажу в этом доме
			foreach ( $this -> Mdom -> objects_on_sale ( $data['object']['dom']['id'] ) as $object )
			{
				# информация об объекте
				$info_object = $this -> Mobject -> info ( $object );
				# список квартир по кк
				$data['objects_on_sale'][$info_object['kk']][] = $info_object;

				# сумма площадей
				if ( isset( $data['objects_pl'][$info_object['kk']] ) )
					$data['objects_pl'][$info_object['kk']]+= $info_object['pl_o'];
				else
					$data['objects_pl'][$info_object['kk']] = $info_object['pl_o'];

				# сумма цен
				if ( isset( $data['objects_cena'][$info_object['kk']] ) )
					$data['objects_cena'][$info_object['kk']]+= $info_object['cena'];
				else
					$data['objects_cena'][$info_object['kk']] = $info_object['cena'];
			}
*/

			# кк
			$data['kk'] = array (
				'Комнаты',
				'Однокомнатные квартиры',
				'Двухкомнатные квартиры',
				'Трехкомнатные квартиры',
				'Четырехкомнатные квартиры',
				'Многокомнатные квартиры',
			);

			# список вкладок
			$data['sections'] = $this->sections;

			# получаем параметры поиска
			$Sid = $this->session->userdata('session_id');
			$data['head']['search'] = $this->Msearch->get_param('');

			# заголовок
			$data['head']['title'] = 'PN66.ru - Поиск недвижимости в Екатеринбурге';
			$data['head']['keyw'] = 'недвижимость, Екатеринбург';
			$data['head']['desc'] = 'Поиск недвижимости в Екатеринбурге';

			# отображение
			$this->load->view('flat/dom', $data);
		}

		# страница-заглушка
		function _not_found()
		{			# получаем параметры поиска
			$Sid = $this->session->userdata('session_id');
			$Dhead['search'] = $this->Msearch->get_param($Sid);

			# заголовок
			$Dhead['title'] = 'PN66.ru - Поиск недвижимости в Екатеринбурге';
			$Dhead['keyw'] = 'недвижимость, Екатеринбург';
			$Dhead['desc'] = 'Поиск недвижимости в Екатеринбурге';

			# отображение
			$this->load->view('head' , $Dhead);
			$this->load->view('flat_id_not_found');
			$this->load->view('foot');
		}
	}

?>
