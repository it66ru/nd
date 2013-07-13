<?php
class Statistics extends Controller {

	function __construct()
	{
		parent::__construct();
		
		# только для админов. иначе шифруемся, показываем страницу 404
		if ($this->auth->login() == FALSE || $this->auth->user['type'] != 'admin')
			show_404('page');
	}


	# список отчетов
	function index()
	{
		$sql = "select s.id, s.name from t_statistic s order by s.name";
		$data['stat'] = $this->db->query($sql)->result_array();
		$this->load->view('admin/stat/list', $data);
	}


	# отображение отчета
	function report($id)
	{
		# данные отчета
		$sql = "select s.* from t_statistic s where s.id = " . $id;
		$data['report'] = $this->db->query($sql)->row_array();
		
		# поля отчета
		$sql = "select sf.* from t_statistic_field sf where sf.statistic_id = " . $id;
		$data['fields'] = $this->db->query($sql)->result_array();
		
		# данные отчета
		$sql = "select * from " . $data['report']['table'];
		$data['rows'] = $this->db->query($sql)->result_array();
		
		# отображение
		$this->load->view('admin/stat/report', $data);
	}


	# список последних парсов
	function parse()
	{
		$sql = "select p.id, 
					(select o.id from nd_objects o where o.parse_id = p.id ) as object_id,
					p.cdate, p.status, p.sdate, p.url, p.title, p.address, p.id_dom
				from parse p
				order by p.cdate desc
				limit 100";
		$result = $this->db->query($sql)->result_array();
		
		echo '<pre><table border=1>';
		echo '<tr><th nowrap>'.implode("</th><th nowrap>", array_keys($result[0]))."</th></tr>";
		foreach ($result as $r)
			echo '<tr><td nowrap>'.implode("</td><td nowrap>", $r)."</td></tr>";
		echo '</table></pre>';
	}


	function spam()
	{
		$data = array();
		$sql = "select o.ip, o.user_id, count(1) as cnt
				from nd_objects o
				group by o.ip, o.user_id
				order by cnt desc";
		foreach ($this->db->query($sql)->result_array() as $_)
		{
			$data[$_['user_id']][$_['ip']] = $_['cnt'];
		}
		
		
		echo '<pre>'.print_r($data, true).'</pre>';
	}



}

?>
