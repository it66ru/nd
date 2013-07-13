<?php

	class Stat extends Controller {

		function __construct()
		{
			parent::Controller();
			
			$this->cash = array();   // кэширование результатов SQL-запроса
			
			$this->count = 0;
			
			$this->p = 14;                     // периодичность данных
			$this->s = $this->p * 2.5 -1;      // стартовый период (последний непрогнозный)
			$this->col = array('high','low');  // анализируемые колонки
			
			$this->a = null;  // альфа (уровень)
			$this->b = null;  // бета (стренд)
			$this->c = null;  // гамма (сезонность)
			$this->rss = null;
			$this->optimal = array (
				'a' => null,
				'b' => null,
				'c' => null,
			);
		}

		function hw($date='0000-00-00', $count=150)
		{
			$this->date  = $date;
			$this->count = $count;
			
			# находим оптимальные параметры
			$this->selection();

			# устанавливаем их
			$this->a = $this->optimal['a'];
			$this->b = $this->optimal['b'];
			$this->c = $this->optimal['c'];

			# проставляем начальные данные
			$this->get_data();
			$this->moving_average();
			$this->start_value();

			# и просчитываем прогноз еще раз
			$this->rss = $this->forecast();

			# отображение
			$this->view();
		}

		# подбор параметров
		function selection()
		{
			echo '<pre>';
			
			# перебором находим оптимальные коэффициенты
			for ( $this->a=0; $this->a<=1; $this->a+=0.1 )
			for ( $this->b=0; $this->b<=1; $this->b+=0.1 )
			for ( $this->c=0; $this->c<=1; $this->c+=0.1 )
			{
				# проставляем начальные данные
				$this->get_data();
				$this->moving_average();
				$this->start_value();

				# вычисляем прогноз
				$rss = $this->forecast();
				if ( !$this->rss || $rss < $this->rss )
				{
					$this->optimal = array (
						'a' => $this->a,
						'b' => $this->b,
						'c' => $this->c,
					);
					$this->rss = $rss;
				}
/* 
				echo 'a = '.$this->a."\t";
				echo 'b = '.$this->b."\t";
				echo 'c = '.$this->c."\t";
				echo $rss."\t";
				echo $this->rss."\n";
*/
			}
			
			echo '</pre>';
		}

		# отображение всей таблицы
		function view()
		{
			echo '<pre>';
			foreach ( $this->optimal as $p => $v ) echo $p.' = '.$v."\n";
			echo "\n";
			
			$head = array_keys($this->data[0]);
			echo 'N'."\t";
			foreach ( $head as $h ) echo str_pad($h, 10)."\t";
			echo "\n".str_repeat('-',150)."\n";
			foreach ( $this->data as $n => $q )
			{
				echo $n."\t";
				foreach ( $q as $v ) echo str_pad($v, 10)."\t";
				echo "\n";
			}
			echo '</pre>';
		}


		# получение данных
		function get_data()
		{
			if ( !$this->cash )
			{
				$SQL = "SELECT 
					DATE_FORMAT(date, '%d.%m.%Y') as day, 
					DATE_FORMAT(date, '%H') as hour, 
					ROUND(high) as value,
					null as ma,
					null as level,
					null as trend,
					null as season,
					null as forecast,
					null as sqr_error
				FROM `quotes` 
				WHERE period = 'H' and date > '" . $this->date . "'
				ORDER BY date
				LIMIT " . $this->count;
				$query = $this->db->query($SQL);
				$this->cash = $query->result_array();
			}
			
			$this->data = $this->cash;
		}

		# вычисление скользящего среднего
		function moving_average($k=50)
		{
			foreach ( $this->data as $n => $r )
			{
				if ( isset($this->data[$n-$this->p/2]) && isset($this->data[$n+$this->p/2]) && $n<=$this->s )
				{
					$this->data[$n]['ma'] = 0;
					$s = $n - $this->p/2;
					
					# перебираем всех период
					for ( $i=0; $i<=$this->p; $i++ )
					{
						$val = $this->data[$s+$i]['value'];
						
						# крайние значения дают вес 1/2, все остальные 1
						$val*= ( $i==0 || $i==$this->p ) ? 0.5 : 1;
						
						# прибавляем к текущему значению
						$this->data[$n]['ma']+= $val;
					}
					
					# усредняем и округляем
					$this->data[$n]['ma'] = round($this->data[$n]['ma'] / $this->p, 2);
				}
				else $this->data[$n]['ma'] = null;
			
			}

		}

		# установка начальных значений
		function start_value()
		{
			# уровень
			$this->data[$this->s]['level'] = $this->data[$this->s]['ma'];

			# тренд
			$this->data[$this->s]['trend'] = round($this->data[$this->s]['ma'] - $this->data[$this->s-1]['ma'], 2);

			# сезон
			for ( $i=0; $i<$this->p; $i++ )
			{
				$k1 = $this->s - $i;
				$k2 = $this->s - $i - $this->p;
				$v1 = $this->data[$k1]['value'] / $this->data[$k1]['ma'];
				$v2 = $this->data[$k2]['value'] / $this->data[$k2]['ma'];
				
				# среднее между текущим и точно таким же сезоном в прошлом
				$this->data[$k1]['season'] = ($v1+$v2)/2;
				$this->data[$k1]['season'] = round($this->data[$k1]['season'], 9);
			}
		}

		# расчет прогноза
		function forecast()
		{
			$rss = 0;

			for ( $i=$this->s+1; $i<count($this->data); $i++ )
			{
				$l = $this->level($i-1);
				$t = $this->trend($i-1);
				$s = $this->season($i-$this->p);
				
				# прогнозное значение
				$f = ( $l + $t ) * $s;
				$this->data[$i]['forecast'] = round($f, 2);

				# ошибка
				$e = $this->data[$i]['value'] - $this->data[$i]['forecast'];
				$this->data[$i]['sqr_error'] = round($e*$e);
				$rss+= $this->data[$i]['sqr_error'];
			}

			# возвращаем суммарную ошибку
			return $rss;
		}


		# сумма квадратов ошибки
		function rss()
		{
			$rss = 0;
			foreach ( $this->data as $r ) $rss+= $r['sqr_error'];
			return $rss;
		}



		# Уровень
		function level($n)
		{
			# если есть посчитанное, то возвращаем его
			if ( !is_null($this->data[$n]['level']) )
				return $this->data[$n]['level'];
			
			# при полных вычислениях - эта блокировка не сработает
#			if ( !$this->data[$n-$this->p]['season'] ) return;
			
			# если нет, то вычисляем
			$v = $this->a * ( $this->level($n-1) + $this->trend($n-1) );
			$v+= ( 1 - $this->a ) * ( $this->data[$n]['value'] / $this->season($n-$this->p) );
			$this->data[$n]['level'] = round($v, 2);
			return $this->data[$n]['level'];
		}

		# Тренд
		function trend($n)
		{
			# если есть посчитанное, то возвращаем его
			if ( !is_null($this->data[$n]['trend']) )
				return $this->data[$n]['trend'];
			
			$v = $this->b * $this->trend($n-1);
			$v+= ( 1 - $this->b ) * ( $this->level($n) - $this->level($n-1) );
			$this->data[$n]['trend'] = round($v, 2);
			return $this->data[$n]['trend'];
		}

		# Сезонность
		function season($n)
		{
			# если есть посчитанное, то возвращаем его
			if ( !is_null($this->data[$n]['season']) )
				return $this->data[$n]['season'];
			
			$v = $this->c * $this->season($n-$this->p);
			$v+= ( 1 - $this->c ) * ( $this->data[$n]['value'] / $this->level($n) ) ;
			return $this->data[$n]['season'] = round($v, 9);
		}

	}

?>
