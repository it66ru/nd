<?php

class My extends Controller
{
	function __construct()
	{
		parent::Controller();
		
		# если пользователь не авторизован, то переводим на страницу авторизации
		if (!$this->auth->login()) redirect('login', 'refresh');
	}


	# главная страница личного кабиета
	function index()
	{
		$this->objects('new');
	}


	# список объектов
	function objects($status)
	{
		$data = array (
			'objects' => array(),
		);
		
		# поиск всех "моих" объявлений по данному статусу
		$this->db->where('user_id', $this->auth->user['id'] );
		$this->db->where('status', $status);
		foreach ( $this->db->get('nd_objects')->result_array() as $r )
		{
			# приводим даты к нормальному виду
			$r['cdate'] = preg_replace('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', '\\3.\\2.\\1', $r['cdate']);
			$r['edate'] = preg_replace('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', '\\3.\\2.\\1', $r['edate']);
			$r['mdate'] = preg_replace('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', '\\3.\\2.\\1', $r['mdate']);
			
			$data['objects'][] = $r;
		}

		# данные для шапки
		$data['head']['title'] = 'PN66.ru - Личный кабинет';

		# отображение
		$this->load->view('my/index', $data);
	}

	function object ( $id )
	{
		$this->Mobject->id = (int) $id;
		
		# если такое объявление существует
		if ( $this->Mobject->get() )
		{
			# проверяем доступ пользователя к данному объявлению
			if ( $this->Mobject->data['user_id'] != $this->auth->user['id'] )
				return show_404('page');
		}
		# иначе перекидываем на страницу добавления
		elseif ( $id != 'add' ) redirect('/my/object/add');


		# подключаем валидатор формы
		$this->load->library('validation');

		# настройка правил
		$rules['ul'] = "required";
		$rules['d']  = "required";
		$rules['price'] = "required";
		$rules['rooms'] = "required";
		$rules['floor'] = "required";
		$rules['space_total']   = "required";
		$rules['space_living']  = "required";
		$rules['space_kitchen'] = "required";
		$rules['name']  = "required";
		$rules['phone'] = "required";
		$rules['email'] = "valid_email";

		# установка правил
		$this->validation->set_rules($rules);

		# сообщения об ошибках
		$this->validation->set_message('required', 'Не заполнено поле "%s"');
		$this->validation->set_message('valid_email', 'E-mail указан не верно');

		# названия полей
		$fields['type'] = "Тип объявления";
		$fields['ul'] = "Название улицы";
		$fields['d']  = "Номер дома";
		$fields['house_id'] = "ID дома";
		$fields['price'] = "Цена";
		$fields['rooms'] = "Кол-во комнат";
		$fields['floor'] = "Этаж";
		$fields['space_total']   = "Общая площадь";
		$fields['space_living']  = "Площадь кухни";
		$fields['space_kitchen'] = "Жилая площадь";
		$fields['description'] = "Дополнительная информация";
		$fields['foto'] = "Фотографии";
		
		$fields['info']['renovation']  = "Ремонт";
		$fields['info']['balcony']     = "Балкон";
		$fields['info']['bathroom']    = "Сан. узел";
		$fields['info']['window']      = "Окна выходят";
		$fields['info']['phone']       = "Телефон";
		$fields['info']['furniture']   = "Мебель";
		$fields['info']['comment']     = "Краткий комментарий";
		$fields['info']['type']        = "Условия продажи";
		$fields['info']['mortgage']    = "Ипотека";
		$fields['info']['office']      = "Под офис";
		$fields['info']['replan']      = "Перепланировка";
		
		$fields['name']  = "Контактное лицо";
		$fields['phone'] = "Контактный телефон";
		$fields['email'] = "Контактный e-mail";

		# установка полей
		$this->validation->set_fields($fields);

		# подключаем хэлпер
		$this->load->helper('form');

		# если форма заполнена правильно
		if ( $this->validation->run() )
		{
			# добавляем данные в объект
			$this->Mobject->complete_data_from_post();
			$this->Mobject->data['user_id'] = $this->auth->user['id'];
			$this->Mobject->data['edate']   = date('Y-m-d H:i:s');
			$this->Mobject->data['status']  = 'new';
			
			# сохраняем объект
			$this->Mobject->save();

			# добавляем новые фотки
			foreach ( array_diff($this->input->post('foto'), $this->Mobject->foto) as $f )
				$this->Mobject->add_foto('foto/temp/'.$f);
			
			# убираем удаленные фотки
			foreach ( array_diff($this->Mobject->foto, $this->input->post('foto')) as $f )
				$this->Mobject->del_foto($f);

			# перекидываем на список объявлений
			redirect('my');
		}
		else
		{
			# ошибки заполнения			$data['error'] = $this->validation->error_string;

			# если это редактирование и ошибки нет, то значит это первая страница редактирования
			if ( /* $id != 'add' && */ $data['error'] == '' )
			{
				# устанавливаем значения объекта в форму
				foreach ( $fields as $f => $name )
				{
					if ( $f == 'foto' )
						$this->validation->$f = $this->Mobject->foto;
					else 
						$this->validation->$f = $this->Mobject->data[$f];
				}
			}

			# отображение
			$this->load->view('my/object', $data);		}	}


	function del_object ( $id )
	{
		# отправляем в архив
		$sql = "UPDATE nd_objects SET status = 'removed', ddate = now() WHERE id = ".(int)$id." AND user_id = ".(int)$this->auth->user['id'];
		$this->db->query($sql);

		# перекидываем на список объявлений
		redirect ( 'my', 'refresh' );
	}

}
?>
