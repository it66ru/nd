<?php

class CRUD_Model extends Model
{
	private $table  = null;
	public  $data   = array();

	public function __construct($table = null)
	{
		parent::__construct();
		$this->table = $table;
	}

	public function __get($key)
	{
		if (array_key_exists($key, $this->data))
			return $this->data[$key];
		
		if ($key == 'table')
			return $this->table;
		
		return parent::__get($key);
	}


	/**
	* Загрузка записи из бд
	*/
	public function load($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get($this->table, 1);
		$this->data = $query->row_array();
		return $this->data;
	}


	/**
	* Загрузка всех записей из таблицы
	*/
	public function all($rows = null, $offset = null)
	{
		return $this->db->get($this->table)->result_array();
	}


	/**
	* список всех (только имена)
	*/
	function allNames()
	{
		$data = array(0 => '');
		foreach ($this->all() as $_)
			$data[$_['id']] = $_['name'];
		return $data;
	}


	/**
	* Сохранение записи в бд
	*/
	public function save()
	{
		# установка значений полей
		foreach ($this->db->list_fields($this->table) as $field)
		{
			if (array_key_exists($field, $this->data))
			{
				$this->db->set($field, $this->data[$field]);
			}
		}

		# редактирование записи
		if (array_key_exists('id', $this->data))
		{
			$this->db->where('id', $this->id);
			$this->db->update($this->table);
		}
		# добавление новой записи
		else
		{
			$this->db->insert($this->table);
			$this->data['id'] = $this->db->insert_id();
		}
	}


	/**
	 * удаление записи
	 */
	public function delete()
	{
		if ($this->id)
		{
			$this->db->where('id', $this->id);
			$this->db->delete($this->table);
		}
	}



	public function debug()
	{
		echo '<pre>'.print_r($this->data, true).'</pre>';
	}


}
