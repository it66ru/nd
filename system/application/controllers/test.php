<?php

	class Test extends Controller {

		function __construct()
		{
			parent::Controller();
			
			include('simple_html_dom.php');
		}
		
		function pi()
		{
			phpinfo();
		}
		
		function dat()
		{
			$f = 'SxGeoCity.dat';
			
			$fh = fopen($f, 'rb');

			$header = fread($fh, 32);
			
			$info = unpack('Cver/Ntime/Ctype/Ccharset/Cb_idx_len/nm_idx_len/nrange/Ndb_items/Cid_len/nmax_region/nmax_city/Nregion_size/Ncity_size', substr($header, 3));
			echo '<pre>'.print_r($info, true).'</pre>';
			
		

			
		}

		function copy_finam()
		{
			$url_base = 'http://195.128.78.52/SPFB.RTS-9.12_120822_120822.txt?market=14&em=81379&code=SPFB.RTS-9.12&df=22&mf=7&yf=2012&dt=22&mt=7&yt=2012&p=1&f=SPFB.RTS-9.12_120822_120822&e=.txt&cn=SPFB.RTS-9.12&dtf=1&tmf=3&MSOR=0&mstime=on&mstimever=1&sep=3&sep2=1&datf=6';

# <a href="#" index="83" value="82637">GOLD-12.12(GDZ2)</a>
# <a href="#" index="84" value="82319">GOLD-9.12(GDU2)</a>

			$address = 'http://195.128.78.52/?';
			$params = array (
				'market' => 14,
				'em'     => 81379,
				'code'   => 'SPFB.RTS-9.12',
				
				'df'     => 22,
				'mf'     => 7,
				'yf'     => 2012,
				
				'dt'     => 22,
				'mt'     => 7,
				'yt'     => 2012,
				
				'p'      => 1,
				'f'      => 'SPFB.RTS-9.12_120822_120822',
				'e'      => '.txt',
				'cn'     => 'SPFB.RTS-9.12',
				'dtf'    => 1,
				'tmf'    => 3,
				'MSOR'   => 0,
				'mstime'    => 'on',
				'mstimever' => 1,
				'sep'   => 3,
				'sep2'  => 1,
				'datf'  => 6,
			);
			
			echo '<pre>'.print_r($params, true).'</pre>';
			echo http_build_query($params);
			
			copy($address.http_build_query($params), '1.txt');
			echo 'ok';
			
		}

		function copy()
		{
			$urls = array (
				'page=analytics&year=2007&month=1&day=0&id=544',
				'page=analytics&year=2007&month=2&day=0&id=2935',
				'page=analytics&year=2007&month=2&day=0&id=2931',
				'page=analytics&year=2007&month=3&day=0&id=2938',
				'page=analytics&year=2007&month=3&day=0&id=2937',
				'page=analytics&year=2007&month=3&day=0&id=2936',
				'page=analytics&year=2007&month=4&day=0&id=3181',
				'page=analytics&year=2007&month=4&day=0&id=3180',
				'page=analytics&year=2007&month=4&day=0&id=3031',
				'page=analytics&year=2007&month=4&day=0&id=2973',
				'page=analytics&year=2007&month=4&day=0&id=2972',
				'page=analytics&year=2007&month=4&day=0&id=2947',
				'page=analytics&year=2007&month=5&day=0&id=6750',
				'page=analytics&year=2007&month=5&day=0&id=6753',
				'page=analytics&year=2007&month=5&day=0&id=6330',
				'page=analytics&year=2007&month=5&day=0&id=6329',
				'page=analytics&year=2007&month=5&day=0&id=5947',
				'page=analytics&year=2007&month=5&day=0&id=5758',
				'page=analytics&year=2007&month=6&day=0&id=6747',
				'page=analytics&year=2007&month=6&day=0&id=6756',
				'page=analytics&year=2007&month=6&day=0&id=6755',
				'page=analytics&year=2007&month=6&day=0&id=6754',
				'page=analytics&year=2007&month=6&day=0&id=7108',
				'page=analytics&year=2007&month=7&day=0&id=7061',
				'page=analytics&year=2007&month=7&day=0&id=7034',
				'page=analytics&year=2007&month=7&day=0&id=7012',
				'page=analytics&year=2007&month=7&day=0&id=6971',
				'page=analytics&year=2007&month=7&day=0&id=6966',
				'page=analytics&year=2007&month=7&day=0&id=6917',
				'page=analytics&year=2007&month=7&day=0&id=7107',
				'page=analytics&year=2007&month=7&day=0&id=6852',
				'page=analytics&year=2007&month=8&day=0&id=7209',
				'page=analytics&year=2007&month=8&day=0&id=7173',
				'page=analytics&year=2007&month=8&day=0&id=7137',
				'page=analytics&year=2007&month=8&day=0&id=7129',
				'page=analytics&year=2007&month=8&day=0&id=7106',
				'page=analytics&year=2007&month=8&day=0&id=7099',
				'page=analytics&year=2007&month=8&day=0&id=7061',
				'page=analytics&year=2007&month=9&day=0&id=7436',
				'page=analytics&year=2007&month=9&day=0&id=7394',
				'page=analytics&year=2007&month=9&day=0&id=7395',
				'page=analytics&year=2007&month=9&day=0&id=7330',
				'page=analytics&year=2007&month=9&day=0&id=7265',
				'page=analytics&year=2007&month=9&day=0&id=7263',
				'page=analytics&year=2007&month=10&day=0&id=7689',
				'page=analytics&year=2007&month=10&day=0&id=7630',
				'page=analytics&year=2007&month=10&day=0&id=7567',
				'page=analytics&year=2007&month=10&day=0&id=7518',
				'page=analytics&year=2007&month=10&day=0&id=7517',
				'page=analytics&year=2007&month=10&day=0&id=7444',
				'page=analytics&year=2007&month=11&day=0&id=7783',
				'page=analytics&year=2007&month=11&day=0&id=7721',
				'page=analytics&year=2007&month=11&day=0&id=7720',
				'page=analytics&year=2007&month=11&day=0&id=7729',
				'page=analytics&year=2007&month=12&day=0&id=8011',
				'page=analytics&year=2007&month=12&day=0&id=7988',
				'page=analytics&year=2007&month=12&day=0&id=7986',
				'page=analytics&year=2007&month=12&day=0&id=7931',
				'page=analytics&year=2007&month=12&day=0&id=7928',
				'page=analytics&year=2007&month=12&day=0&id=7903',
				'page=analytics&year=2008&month=1&day=0&id=8170',
				'page=analytics&year=2008&month=2&day=0&id=8287',
				'page=analytics&year=2008&month=2&day=0&id=8292',
				'page=analytics&year=2008&month=2&day=0&id=8212',
				'page=analytics&year=2008&month=2&day=0&id=8207',
				'page=analytics&year=2008&month=2&day=0&id=8165',
				'page=analytics&year=2008&month=3&day=0&id=8448',
				'page=analytics&year=2008&month=3&day=0&id=8404',
				'page=analytics&year=2008&month=3&day=0&id=8336',
				'page=analytics&year=2008&month=3&day=0&id=8334',
				'page=analytics&year=2008&month=4&day=0&id=8645',
				'page=analytics&year=2008&month=4&day=0&id=8546',
				'page=analytics&year=2008&month=4&day=0&id=8545',
				'page=analytics&year=2008&month=4&day=0&id=8503',
				'page=analytics&year=2008&month=5&day=0&id=8836',
				'page=analytics&year=2008&month=5&day=0&id=8834',
				'page=analytics&year=2008&month=5&day=0&id=8758',
				'page=analytics&year=2008&month=6&day=0&id=9008',
				'page=analytics&year=2008&month=6&day=0&id=9009',
				'page=analytics&year=2008&month=6&day=0&id=9010',
				'page=analytics&year=2008&month=6&day=0&id=8835',
				'page=analytics&year=2008&month=6&day=0&id=8837',
				'page=analytics&year=2008&month=7&day=0&id=9150',
				'page=analytics&year=2008&month=7&day=0&id=9115',
				'page=analytics&year=2008&month=7&day=0&id=9457',
				'page=analytics&year=2008&month=7&day=0&id=9114',
				'page=analytics&year=2008&month=7&day=0&id=9460',
				'page=analytics&year=2008&month=7&day=0&id=9003',
				'page=analytics&year=2008&month=8&day=0&id=9289',
				'page=analytics&year=2008&month=8&day=0&id=9675',
				'page=analytics&year=2008&month=8&day=0&id=9208',
				'page=analytics&year=2008&month=8&day=0&id=9459',
				'page=analytics&year=2008&month=8&day=0&id=9177',
				'page=analytics&year=2008&month=9&day=0&id=9586',
				'page=analytics&year=2008&month=9&day=0&id=9587',
				'page=analytics&year=2008&month=9&day=0&id=9676',
				'page=analytics&year=2008&month=9&day=0&id=9492',
				'page=analytics&year=2008&month=9&day=0&id=9455',
				'page=analytics&year=2008&month=10&day=0&id=9817',
				'page=analytics&year=2008&month=10&day=0&id=9769',
				'page=analytics&year=2008&month=10&day=0&id=9723',
				'page=analytics&year=2008&month=10&day=0&id=9714',
				'page=analytics&year=2008&month=11&day=0&id=9954',
				'page=analytics&year=2008&month=11&day=0&id=9955',
				'page=analytics&year=2008&month=11&day=0&id=9936',
				'page=analytics&year=2008&month=11&day=0&id=9886',
				'page=analytics&year=2008&month=11&day=0&id=9887',
				'page=analytics&year=2008&month=12&day=0&id=10287',
				'page=analytics&year=2008&month=12&day=0&id=10288',
				'page=analytics&year=2008&month=12&day=0&id=10203',
				'page=analytics&year=2008&month=12&day=0&id=10202',
				'page=analytics&year=2008&month=12&day=0&id=10185',
				'page=analytics&year=2008&month=12&day=0&id=10157',
				'page=analytics&year=2008&month=12&day=0&id=10029',
				'page=analytics&year=2008&month=12&day=0&id=10028',
				'page=analytics&year=2009&month=1&day=0&id=10348',
				'page=analytics&year=2009&month=1&day=0&id=10335',
				'page=analytics&year=2009&month=1&day=0&id=10314',
				'page=analytics&year=2009&month=2&day=0&id=10417',
				'page=analytics&year=2009&month=2&day=0&id=10389',
				'page=analytics&year=2009&month=2&day=0&id=10401',
				'page=analytics&year=2009&month=2&day=0&id=10400',
				'page=analytics&year=2009&month=2&day=0&id=10373',
				'page=analytics&year=2009&month=3&day=0&id=10594',
				'page=analytics&year=2009&month=3&day=0&id=10552',
				'page=analytics&year=2009&month=3&day=0&id=10536',
				'page=analytics&year=2009&month=3&day=0&id=10520',
				'page=analytics&year=2009&month=3&day=0&id=10450',
				'page=analytics&year=2009&month=4&day=0&id=10683',
				'page=analytics&year=2009&month=4&day=0&id=10676',
				'page=analytics&year=2009&month=4&day=0&id=10675',
				'page=analytics&year=2009&month=4&day=0&id=10589',
				'page=analytics&year=2009&month=4&day=0&id=10594',
				'page=analytics&year=2009&month=5&day=0&id=10741',
				'page=analytics&year=2009&month=5&day=0&id=10704',
				'page=analytics&year=2009&month=5&day=0&id=10693',
				'page=analytics&year=2009&month=6&day=0&id=11110',
				'page=analytics&year=2009&month=6&day=0&id=11068',
				'page=analytics&year=2009&month=6&day=0&id=11058',
				'page=analytics&year=2009&month=6&day=0&id=11036',
				'page=analytics&year=2009&month=6&day=0&id=10964',
				'page=analytics&year=2009&month=7&day=0&id=11131',
				'page=analytics&year=2009&month=7&day=0&id=11128',
				'page=analytics&year=2009&month=7&day=0&id=11117',
				'page=analytics&year=2009&month=7&day=0&id=11109',
				'page=analytics&year=2009&month=7&day=0&id=11110',
				'page=analytics&year=2009&month=8&day=0&id=11174',
				'page=analytics&year=2009&month=8&day=0&id=11173',
				'page=analytics&year=2009&month=8&day=0&id=11175',
				'page=analytics&year=2009&month=8&day=0&id=11172',
				'page=analytics&year=2009&month=8&day=0&id=11157',
				'page=analytics&year=2009&month=8&day=0&id=11138',
				'page=analytics&year=2009&month=9&day=0&id=11269',
				'page=analytics&year=2009&month=9&day=0&id=11263',
				'page=analytics&year=2009&month=9&day=0&id=11262',
				'page=analytics&year=2009&month=9&day=0&id=11236',
				'page=analytics&year=2009&month=9&day=0&id=11187',
				'page=analytics&year=2009&month=10&day=0&id=11368',
				'page=analytics&year=2009&month=10&day=0&id=11354',
				'page=analytics&year=2009&month=10&day=0&id=11353',
				'page=analytics&year=2009&month=10&day=0&id=11333',
				'page=analytics&year=2009&month=10&day=0&id=11315',
				'page=analytics&year=2009&month=11&day=0&id=11420',
				'page=analytics&year=2009&month=11&day=0&id=11408',
				'page=analytics&year=2009&month=11&day=0&id=11397',
				'page=analytics&year=2009&month=11&day=0&id=11392',
				'page=analytics&year=2009&month=11&day=0&id=11386',
				'page=analytics&year=2009&month=11&day=0&id=11388',
				'page=analytics&year=2009&month=12&day=0&id=11512',
				'page=analytics&year=2009&month=12&day=0&id=11511',
				'page=analytics&year=2009&month=12&day=0&id=11513',
				'page=analytics&year=2009&month=12&day=0&id=11502',
				'page=analytics&year=2009&month=12&day=0&id=11490',
				'page=analytics&year=2009&month=12&day=0&id=11458',
				'page=analytics&year=2009&month=12&day=0&id=11450',
				'page=analytics&year=2010&month=1&day=0&id=11550',
				'page=analytics&year=2010&month=1&day=0&id=11546',
				'page=analytics&year=2010&month=1&day=0&id=11538',
				'page=analytics&year=2010&month=2&day=0&id=11593',
				'page=analytics&year=2010&month=2&day=0&id=11584',
				'page=analytics&year=2010&month=2&day=0&id=11586',
				'page=analytics&year=2010&month=2&day=0&id=11568',
				'page=analytics&year=2010&month=2&day=0&id=11560',
				'page=analytics&year=2010&month=2&day=0&id=11559',
				'page=analytics&year=2010&month=3&day=0&id=11674',
				'page=analytics&year=2010&month=3&day=0&id=11664',
				'page=analytics&year=2010&month=3&day=0&id=11654',
				'page=analytics&year=2010&month=3&day=0&id=11647',
				'page=analytics&year=2010&month=3&day=0&id=11617',
				'page=analytics&year=2010&month=4&day=0&id=11743',
				'page=analytics&year=2010&month=4&day=0&id=11742',
				'page=analytics&year=2010&month=4&day=0&id=11726',
				'page=analytics&year=2010&month=4&day=0&id=11718',
				'page=analytics&year=2010&month=4&day=0&id=11686',
				'page=analytics&year=2010&month=4&day=0&id=11685',
				'page=analytics&year=2010&month=4&day=0&id=11693',
				'page=analytics&year=2010&month=5&day=0&id=11804',
				'page=analytics&year=2010&month=5&day=0&id=11782',
				'page=analytics&year=2010&month=5&day=0&id=11781',
				'page=analytics&year=2010&month=5&day=0&id=11766',
				'page=analytics&year=2010&month=5&day=0&id=11756',
				'page=analytics&year=2010&month=5&day=0&id=11752',
				'page=analytics&year=2010&month=6&day=0&id=11852',
				'page=analytics&year=2010&month=6&day=0&id=11841',
				'page=analytics&year=2010&month=6&day=0&id=11834',
				'page=analytics&year=2010&month=6&day=0&id=11824',
				'page=analytics&year=2010&month=6&day=0&id=11804',
				'page=analytics&year=2010&month=7&day=0&id=11910',
				'page=analytics&year=2010&month=7&day=0&id=11900',
				'page=analytics&year=2010&month=7&day=0&id=11891',
				'page=analytics&year=2010&month=7&day=0&id=11871',
				'page=analytics&year=2010&month=8&day=0&id=11987',
				'page=analytics&year=2010&month=8&day=0&id=11978',
				'page=analytics&year=2010&month=8&day=0&id=11971',
				'page=analytics&year=2010&month=8&day=0&id=11970',
				'page=analytics&year=2010&month=8&day=0&id=11954',
				'page=analytics&year=2010&month=8&day=0&id=11933',
				'page=analytics&year=2010&month=9&day=0&id=12047',
				'page=analytics&year=2010&month=9&day=0&id=12035',
				'page=analytics&year=2010&month=9&day=0&id=12019',
				'page=analytics&year=2010&month=9&day=0&id=12002',
				'page=analytics&year=2010&month=10&day=0&id=12107',
				'page=analytics&year=2010&month=10&day=0&id=12074',
				'page=analytics&year=2010&month=10&day=0&id=12073',
				'page=analytics&year=2010&month=10&day=0&id=12056',
				'page=analytics&year=2010&month=10&day=0&id=12118',
				'page=analytics&year=2010&month=11&day=0&id=12166',
				'page=analytics&year=2010&month=11&day=0&id=12157',
				'page=analytics&year=2010&month=11&day=0&id=12146',
				'page=analytics&year=2010&month=11&day=0&id=12130',
				'page=analytics&year=2010&month=11&day=0&id=12122',
				'page=analytics&year=2010&month=11&day=0&id=12117',
				'page=analytics&year=2010&month=12&day=0&id=12221',
				'page=analytics&year=2010&month=12&day=0&id=12199',
				'page=analytics&year=2010&month=12&day=0&id=12198',
				'page=analytics&year=2010&month=12&day=0&id=12182',
				'page=analytics&year=2010&month=12&day=0&id=12181',
				'page=analytics&year=2011&month=1&day=0&id=12259',
				'page=analytics&year=2011&month=1&day=0&id=12244',
				'page=analytics&year=2011&month=1&day=0&id=12226',
				'page=analytics&year=2011&month=1&day=0&id=12220',
				'page=analytics&year=2011&month=2&day=0&id=12302',
				'page=analytics&year=2011&month=2&day=0&id=12282',
				'page=analytics&year=2011&month=2&day=0&id=12260',
				'page=analytics&year=2011&month=2&day=0&id=12259',
				'page=analytics&year=2011&month=3&day=0&id=12345',
				'page=analytics&year=2011&month=3&day=0&id=12340',
				'page=analytics&year=2011&month=3&day=0&id=12338',
				'page=analytics&year=2011&month=3&day=0&id=12325',
				'page=analytics&year=2011&month=3&day=0&id=12315',
				'page=analytics&year=2011&month=4&day=0&id=12403',
				'page=analytics&year=2011&month=4&day=0&id=12402',
				'page=analytics&year=2011&month=4&day=0&id=12387',
				'page=analytics&year=2011&month=4&day=0&id=12370',
				'page=analytics&year=2011&month=5&day=0&id=12508',
				'page=analytics&year=2011&month=5&day=0&id=12458',
				'page=analytics&year=2011&month=5&day=0&id=12428',
				'page=analytics&year=2011&month=5&day=0&id=12415',
				'page=analytics&year=2011&month=5&day=0&id=12412',
				'page=analytics&year=2011&month=6&day=0&id=12570',
				'page=analytics&year=2011&month=6&day=0&id=12559',
				'page=analytics&year=2011&month=6&day=0&id=12538',
				'page=analytics&year=2011&month=7&day=0&id=12632',
				'page=analytics&year=2011&month=7&day=0&id=12630',
				'page=analytics&year=2011&month=7&day=0&id=12589',
				'page=analytics&year=2011&month=7&day=0&id=12590',
				'page=analytics&year=2011&month=8&day=0&id=12723',
				'page=analytics&year=2011&month=8&day=0&id=12707',
				'page=analytics&year=2011&month=8&day=0&id=12695',
				'page=analytics&year=2011&month=8&day=0&id=12686',
				'page=analytics&year=2011&month=8&day=0&id=12685',
				'page=analytics&year=2011&month=8&day=0&id=12653',
				'page=analytics&year=2011&month=9&day=0&id=12818',
				'page=analytics&year=2011&month=9&day=0&id=12791',
				'page=analytics&year=2011&month=9&day=0&id=12785',
				'page=analytics&year=2011&month=9&day=0&id=12782',
				'page=analytics&year=2011&month=9&day=0&id=12735',
				'page=analytics&year=2011&month=10&day=0&id=12893',
				'page=analytics&year=2011&month=10&day=0&id=12887',
				'page=analytics&year=2011&month=10&day=0&id=12854',
				'page=analytics&year=2011&month=10&day=0&id=12825',
				'page=analytics&year=2011&month=11&day=0&id=12963',
				'page=analytics&year=2011&month=11&day=0&id=12955',
				'page=analytics&year=2011&month=11&day=0&id=12942',
				'page=analytics&year=2011&month=11&day=0&id=12930',
				'page=analytics&year=2011&month=11&day=0&id=12918',
				'page=analytics&year=2011&month=11&day=0&id=12906',
				'page=analytics&year=2011&month=12&day=0&id=13022',
				'page=analytics&year=2011&month=12&day=0&id=13007',
				'page=analytics&year=2011&month=12&day=0&id=12990',
				'page=analytics&year=2011&month=12&day=0&id=12980',
				'page=analytics&year=2012&month=1&day=0&id=13083',
				'page=analytics&year=2012&month=1&day=0&id=13069',
				'page=analytics&year=2012&month=1&day=0&id=13064',
				'page=analytics&year=2012&month=1&day=0&id=13044',
				'page=analytics&year=2012&month=2&day=0&id=13171',
				'page=analytics&year=2012&month=2&day=0&id=13165',
				'page=analytics&year=2012&month=2&day=0&id=13140',
				'page=analytics&year=2012&month=2&day=0&id=13126',
				'page=analytics&year=2012&month=2&day=0&id=13094',
				'page=analytics&year=2012&month=3&day=0&id=13233',
				'page=analytics&year=2012&month=3&day=0&id=13204',
				'page=analytics&year=2012&month=3&day=0&id=13194',
				'page=analytics&year=2012&month=3&day=0&id=13181',
				'page=analytics&year=2012&month=3&day=0&id=13171',
				'page=analytics&year=2012&month=4&day=0&id=13311',
				'page=analytics&year=2012&month=4&day=0&id=13302',
				'page=analytics&year=2012&month=4&day=0&id=13276',
				'page=analytics&year=2012&month=4&day=0&id=13262',
				'page=analytics&year=2012&month=4&day=0&id=13253',
				'page=analytics&year=2012&month=5&day=0&id=13359',
				'page=analytics&year=2012&month=5&day=0&id=13355',
				'page=analytics&year=2012&month=5&day=0&id=13346',
				'page=analytics&year=2012&month=5&day=0&id=13334',
				'page=analytics&year=2012&month=5&day=0&id=13330',
				'page=analytics&year=2012&month=5&day=0&id=13318',
				'page=analytics&year=2012&month=6&day=0&id=13372',
				'page=analytics&year=2012&month=6&day=0&id=13359',
			);
			
			foreach ( $urls as $url )
			{
				$url = 'http://upn.ru/index.aspx?'.$url;
				$file = 'upn_stat/'.md5($url).'.txt';
				
				if ( !file_exists($file) )
				{
					$html = file_get_html($url);
					$tab = $html->find('table[class=blue]', 0);
					file_put_contents($file, $tab->outertext);
					echo $url.' / '.md5($url).'<br>';
					unset($html);
				}
				else
				{
					echo file_get_contents($file);
				}
			}
		}



		function q($p)
		{
			$SQL = "SELECT DATE_FORMAT(date, '%x-%v') as yw, DATE_FORMAT(min(date), '%d.%m.%Y') as date,
					ROUND( (avg(open) + avg(high) + avg(low) + avg(close)) / 4) as cnt
				FROM quotes
				WHERE ticker LIKE 'SPFB.".$p."'
				GROUP BY yw";
				
			$q = $this->db->query($SQL);
			
			echo '<table border=1>';
			foreach ( $q->result_array() as $r )
			{
				echo '<tr>
					<td>'.$r['yw'].'</td>
					<td>'.$r['date'].'</td>
					<td>'.$r['cnt'].'</td>
				</tr>';
			}
			echo '</table>';
		}


		function import()
		{

			$f = 'SPFB.Si_070101_120618.txt';
			
			$fdata = file($f);
			array_shift($fdata);
			
			foreach ( $fdata as $r )
			{
				$s = explode(',', $r);
				$d = preg_replace('/(\d{4})(\d{2})(\d{2})/', '\\1-\\2-\\3', $s[2]);
				
				
				$this->db->set('ticker', $s[0]);
				$this->db->set('period', $s[1]);
				$this->db->set('date', $d);
				$this->db->set('open', $s[4]);
				$this->db->set('high', $s[5]);
				$this->db->set('low', $s[6]);
				$this->db->set('close', $s[7]);
				$this->db->insert('quotes');
				
				echo $s[0].'<br>';
				echo $s[1].'<br>';
				echo $s[2].'<br>';
				echo $d.'<br>';
				
				echo $s[3].'<br>';
				echo $s[4].'<br>';
				echo $s[5].'<br>';
				
			}
			
		}


		# вынос оригинальных фоток
		function foto_original()
		{
			$this->load->helper('directory');
			
			foreach ( directory_map('foto/', true) as $f )
			{
				echo $f.'<br>';
				foreach ( directory_map('foto/'.$f, true) as $s )
				{
					if ( $s == 'original' ) rename( 'foto/'.$f.'/original', 'foto_original/'.$f );
					echo $s.'<br>';
				}
				echo '<br>';
				
			}
			
			echo '<pre>'.print_r($map, true).'</pre>';
		}


		# вынос удаленных фоток
		function foto_removed()
		{
			$sql = "select * from";
		}

		function yad()
		{
			$fp = fsockopen('webdav.yandex.ru', 80, $errno, $errstr, 30);
			
			if (!$fp)
			{
				echo "$errstr ($errno)<br />\n";
			}
			else
			{
				
				$out = array();
				$out[] = "GET /readme.pdf HTTP/1.1";
				$out[] = "Host: webdav.yandex.ru";
				$out[] = "Accept: */*";
				$out[] = "Authorization: OAuth 020362d9c10e436ab216f055e230fc22";
				$out[] = "Connection: Close";
				$out[] = "";
				fwrite($fp, implode("\r\n", $out));
				while (!feof($fp))
				{
					echo fgets($fp, 128);
				}
				fclose($fp);
			}
		}


		function copydt()
		{
			$url = 'http://exist.ru/cat/TO/Cars/Audi/5110?EngineID=1AF0000D';
			$html = file_get_html($url);
		
			$tab = $html->find('#gvParts',0);
			echo $tab->innertext;
			
			$data = array();
			
			# все строки
			foreach ( $tab->find('tr') as $i => $tr )
			{
				$data[$i] = array();
				
				foreach ( $tr->find('td') as $j => $td )
				{
					$data[$i][$j] = $td->innertext;
					
					echo $td->innertext;
					echo '<br>';
					
				}
				
				if (count($data[$i]) == 3) array_unshift($data[$i], $data[$i-1][0]);

				if ( count($data[$i]) == 4 )
				{}
			
				echo '<br><br>---------------<br><br>';
			}
			
			echo '<pre>'.print_r($data, true).'</pre>';
		
		}
		
		
		function view_modify()
		{
			
			$SQL = "select * from modification";
				
			$q = $this->db->query($SQL);
			
			echo '<pre>';
			foreach ( $q->result_array() as $r )
			{
				foreach ( $r as $k=>$v ) echo $v."\t";
				echo "\n";
			}
			
			echo '</pre>';
		
		}


		function pp()
		{
			echo 'jpUYGpsfa$1DSAadfa';
		}

	}

?>
