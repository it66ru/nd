<?php

class Sitemaps extends Controller
{

	# ������ ��������
	function agency()
	{
		# ������ XML
		$urlset = new SimpleXMLElement("<urlset/>");
		$urlset['xmlns'] = 'http://www.sitemaps.org/schemas/sitemap/0.9';

		$this->db->select('id');
		$query = $this->db->get('agency');
		# ���������� ��� ���������
		foreach ( $query -> result_array() as $r )
		{
			# ��������� ����� ����
			$url = $urlset -> addChild('url');
			$url -> loc = 'http://pn66.ru/agency/info/'.$r['id'];
			$url -> changefreq = 'weekly';
			$url -> priority = 0.5;

			# ��������� ����� �������
			$url = $urlset -> addChild('url');
			$url -> loc = 'http://pn66.ru/agency/objects/'.$r['id'];
			$url -> changefreq = 'weekly';
			$url -> priority = 0.8;

			# ��������� ����� ������
			$url = $urlset -> addChild('url');
			$url -> loc = 'http://pn66.ru/agency/comments/'.$r['id'];
			$url -> changefreq = 'weekly';
			$url -> priority = 0.5;		}

		# �����������
		Header ( 'Content-type: text/xml' );
		echo $urlset -> asXML();
	}

	# ������ �������
	function flat()
	{
		# ������ XML
		$urlset = new SimpleXMLElement("<urlset/>");
		$urlset['xmlns'] = 'http://www.sitemaps.org/schemas/sitemap/0.9';

		$this -> db -> select ( 'id' );
		$this -> db -> where ( 'date_del', '0000-00-00' );
		$query = $this -> db -> get ( 'nd_flat_sale' );

		# ���������� ��� ���������
		foreach ( $query -> result_array() as $r )
		{
			# ��������� ����� ����
			$url = $urlset -> addChild('url');
			$url -> loc = 'http://pn66.ru/flat/info/'.$r['id'];
			$url -> changefreq = 'weekly';
			$url -> priority = 0.8;
		}

		# �����������
		Header ( 'Content-type: text/xml' );
		echo $urlset -> asXML();
	}

}
?>
