<?php

	class Mdom extends Model {

		function __construct()
		{
		}

		# возвращает массив данных о доме
		function info ( $id=0 )
		{
			if ( !$id ) return false;
			
			$sql = "select h.id, concat(s.type, '. ', s.name, ', д. ', h.num) as adr, 
				h.year, h.material, h.storey, d.name as district,
				h.yaLat as lat, h.yaLng as lng, ht.name as house_type
				from house h, street s, district d, house_type ht
				where h.id = ?
					and h.street_id = s.id
					and h.district_id = d.id
					and h.house_type_id = ht.id";
			$query = $this->db->query($sql, $id);
			return $query->row_array();
		}


		# возвращает список продающихся квартир в данном доме
		function objects_on_sale ( $id )
		{
			$objects = array();
			# поиск всех объектов
			$this -> db -> select ( 'id' );
			$this -> db -> where ( 'id_dom', $id );
			$this -> db -> order_by ( 'date_new', 'desc' );
			$query = $this -> db -> get ( 'nd_flat_sale' );

			# перебираем все и формируем массив
			foreach ( $query -> result_array() as $r ) $objects[] = $r['id'];
			# возвращаем список в виде массива
			return $objects;
		}

		# фотки дома
		function foto ( $id )
		{
			$this -> db -> where ( 'dom', $id );
			$query = $this -> db -> get ( 'nd_dom_foto' );
			if ( $query -> num_rows() )
			{
				return $query -> result_array();
			}
			else return FALSE;
		}


		# поиск id дома по улице и номеру дома
		function search($ul, $d)
		{
			$ul = $this->db->escape_str(trim($ul));
			$d  = $this->db->escape_str(trim($d));
			
			$sql = "select a.house_id
					from nd_address a
					where lower(a.adr) like lower('".$ul.", д. ".$d."') 
						and a.city_id = ".$this->config->item('city_id');
			$r = $this->db->query($sql)->row_array();
			return $r ? $r['house_id'] : null;
		}

	}

?>
