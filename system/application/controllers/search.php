<?php

	class Search extends Controller {

		function __construct()
		{
			parent::Controller();
		}

		function _remap ()
		{
			# если переданые параметры поиска
			if ( $this->input->post('search') )
			{
				# сохраняем параметры поиска
				$Sid = $this->Msearch->set_param();
				# и сразу редирим на него
				redirect('/search/'.$Sid.'/price/asc', 'refresh');
			}

			# идентификатор поиска
			$sid        = $this->uri->segment(2);
			# поле сортировки
			$orderby    = $this->uri->segment(3) ? $this->uri->segment(3) : 'price';
			# порядок сортировки
			$direction  = $this->uri->segment(4) ? $this->uri->segment(4) : 'asc';
			# страница
			$page       = $this->uri->segment(5);

			# поиск			$this->search($sid, $orderby, $direction, $page);		}

		function search ( $sid, $orderby, $direction, $ot=0 )
		{
			# переопределение полей для сортировки
			if ( $orderby == 'cena' ) $orderby = 'price';
			if ( $orderby == 'kk' )   $orderby = 'rooms';
			if ( $orderby == 'pl_o' ) $orderby = 'space_total';
			if ( $orderby == 'pl_j' ) $orderby = 'space_living';
			if ( $orderby == 'pl_k' ) $orderby = 'space_kitchen';
			if ( $orderby == 'et' )   $orderby = 'floor';
			if ( $orderby == 'date_up' ) $orderby = 'cdate';

			# получаем параметры поиска
			$param = $this->Msearch->get_param($sid);
#			echo '<pre>'.print_r($param, true).'</pre>';

			# преобразуем параметры поиска в строку для sql-запроса
			$search_where = $this->Msearch->where_search($param);

			# тексты для поиска (если есть)
			$data['info'] = $this->Msearch->title_search($sid);

			# заголовок
			$data['head']['title']  = $data['info']['title'];
			$data['head']['keyw']   = $data['info']['keywords'];
			$data['head']['desc']   = $data['info']['description'];
			$data['head']['search'] = $param;

			# данные о сортировке
			$data['sort']['field'] = $orderby;
			$data['sort']['type'] = $direction;

			# собственно сам поиск
			$data['search'] = $this->Msearch->result($search_where, $ot, $orderby, $direction);

			# общее число найденных объектов
			$data['search_count'] = $this->Msearch->count($search_where);

			# линейка страниц
			$this->load->library('pagination');
			$config['base_url'] = '/search/'.$sid.'/'.$orderby.'/'.$direction;
			$config['total_rows'] = $data['search_count'];
			$config['uri_segment'] = 5;
			$config['per_page'] = '20';
			$this->pagination->initialize($config);
			$data['pages'] = $this->pagination->create_links();

			# отображение
			$this->load->view('search', $data);
		}

	}

?>
