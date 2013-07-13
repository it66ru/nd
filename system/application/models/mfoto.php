<?php

class Mfoto extends Model {

	function __construct()
	{
		parent::Model();
		# загружаем библиотеку
		$this->load->library('image_lib');
		# путь к папке картинок
		$this->path = $this->config->item('path').'/foto';
	}

	# копирование фоток из временной папки в папку объета
	function copy_foto_object ( $object, $foto, $dir='temp' )
	{
		# делаем папку объекта, если она не существует
		if ( !file_exists($this->path.'/'.$object) ) mkdir($this->path.'/'.$object, 0777);

		# делаем папку папки по размерам
		if ( !file_exists($this->path.'/'.$object.'/small') )  mkdir($this->path.'/'.$object.'/small',  0777);
		if ( !file_exists($this->path.'/'.$object.'/middle') ) mkdir($this->path.'/'.$object.'/middle', 0777);
		if ( !file_exists($this->path.'/'.$object.'/large') )  mkdir($this->path.'/'.$object.'/large',  0777);

		# путь к исходной картинке
		$source = $this->path.'/temp/'.$foto;
		# копируем в маленькую
		$this->copy_resize($this->path.'/'.$dir.'/'.$foto, $this->path.'/'.$object.'/small/'.$foto,  150, 100, FALSE);
		# копируем в среднюю
		$this->copy_resize($this->path.'/'.$dir.'/'.$foto, $this->path.'/'.$object.'/middle/'.$foto, 300, 200, FALSE);
		# копируем в большую
		$this->copy_resize($this->path.'/'.$dir.'/'.$foto, $this->path.'/'.$object.'/large/'.$foto,  600, 400, TRUE);
	}


	# добавление фотки к объекту
	function adding_to_object ($object, $path, $sort=0)
	{
		# делаем папку объекта, если она не существует
		if ( !file_exists($this->path.'/'.$object) ) mkdir($this->path.'/'.$object, 0777);

		# делаем папку папки по размерам
		if ( !file_exists($this->path.'/'.$object.'/small') )     mkdir($this->path.'/'.$object.'/small',  0777);
		if ( !file_exists($this->path.'/'.$object.'/middle') )    mkdir($this->path.'/'.$object.'/middle', 0777);
		if ( !file_exists($this->path.'/'.$object.'/large') )     mkdir($this->path.'/'.$object.'/large',  0777);
		if ( !file_exists($this->path.'/'.$object.'/original') )  mkdir($this->path.'/'.$object.'/original',  0777);

		# копируем оригинал
		$foto = basename($path);
		$original = $this->path.'/'.$object.'/original/'.$foto;
		copy($path, $original);

		# копируем в маленькую
		$this->copy_resize($original, $this->path.'/'.$object.'/small/'.$foto,  150, 100, FALSE);
		# копируем в среднюю
		$this->copy_resize($original, $this->path.'/'.$object.'/middle/'.$foto, 300, 200, FALSE);
		# копируем в большую
		$this->copy_resize($original, $this->path.'/'.$object.'/large/'.$foto,  600, 400, TRUE);

		# пишем базу
		$this->db->set('object', $object);
		$this->db->set('foto', $foto);
		$this->db->set('sort', $sort);
		$this->db->insert('nd_foto');
	}


	# копируем картунку в опредленный размер
	# картинка копируется в папку размера
	# на большую картунку накладывается водяной знак
	function copy_resize ( $from, $to, $width, $height, $wm=FALSE )
	{
		# очищаем настройки
		$this->image_lib->clear();

		# настройки для ресайза
		$config['image_library']  = 'gd2';
		$config['source_image']   = $from;
		$config['new_image']      = $to;
		$config['maintain_ratio'] = TRUE; // сохранять пропорции
		$config['width']          = $width;
		$config['height']         = $height;

		# передаем настройки
		$this->image_lib->initialize($config);

		# меняем размер файла
		$this->image_lib->resize();

		# если водяной знак, то передаем дополнительные настройки
		if ( $wm )
		{
			$config['source_image']     = $to;
			$config['wm_type']          = 'overlay';
			$config['wm_overlay_path']  = $this->config->item('path').'/img/wm.png';
			$config['wm_opacity']       = 10;
			$config['wm_hor_alignment'] = 'center';
			$config['wm_vrt_alignment'] = 'middle';

			# передаем настройки
			$this->image_lib->initialize ( $config );

			# делаем водяной знак
			$this->image_lib->watermark();
		}

		# возвращаем новое имя файла
		return $this->image_lib->full_dst_path;
	}


	# копирование фоток с авито
	function parse_avito($source)
	{
		# очищаем настройки
		$this->image_lib->clear();

		# копируем картинку
		$file_name = md5($source).'.jpg';
		$file_path = $this->path.'/parse/'.$file_name;
		copy($source, $file_path);
		$size = getimagesize($file_path);

		# настройки для ресайза
		$config['image_library']  = 'gd2';
		$config['source_image']   = $file_path;
		$config['maintain_ratio'] = false;
		$config['rotation_angle'] = '180';
		$config['x_axis'] = $size[0] > $size[1] ? 100 : 0;
		$config['y_axis'] = $size[0] > $size[1] ? 0 : 30;
		$config['width']  = 100;
		$config['height'] = 100;
		

		# сначала крутим, потом режем, потом крутим обратно
		$this->image_lib->initialize($config);
		$this->image_lib->rotate();
		$this->image_lib->crop();
		$this->image_lib->rotate();
#		$this->image_lib->resize();
		
		return $file_path;
	}
}

?>
