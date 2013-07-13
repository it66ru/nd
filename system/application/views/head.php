<? $gm_key = 'ABQIAAAAA_9XqpcxJQYWlax9IpoM7BSu770tVCVhqcIDn-B9IvFNju4P3hT53hQKhzu8U3ZfZRf65JfrgYPXzg'; ?>
<? $ya_key = 'AKgVxU4BAAAAW0EGcQIAcVX3PcvNJLoVFqMkRImZfQKEXfAAAAAAAAAAAACvXzKRousBqhlBTh8gc-ZKP1ckJQ=='; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?=( isset($title) ? $title : 'PN66.ru - Поиск недвижимости в Екатеринбурге' )?></title>

		<? if ( isset($property) && 0 ) { ?>
			<meta property="og:title" content="<?=$property['title']?>"/>
			<meta property="og:type" content="city"/>
			<meta property="og:url" content="<?=$property['url']?>"/>
			<meta property="og:image" content="<?=$property['image']?>"/>
			<meta property="og:latitude" content="<?=$property['latitude']?>" />
			<meta property="og:longitude" content="<?=$property['longitude']?>" />
			<meta property="og:site_name" content="PN66.ru"/>
			<meta property="fb:admins" content="100002112378416"/>
			<meta property="og:description" content="<?=$property['description']?>"/>
		<? } ?>

		<meta name="title" content="<?=( isset($title) ? $title : 'PN66.ru - Поиск недвижимости в Екатеринбурге' )?>" />
		<meta name="keywords" content="<?=( isset($keyw) ? $keyw : 'PN66.ru - Поиск недвижимости в Екатеринбурге' )?>" />
		<meta name="description" content="<?=( isset($desc) ? $desc : 'PN66.ru - Поиск недвижимости в Екатеринбурге' )?>" />

		<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen, projection" />
		<link rel="stylesheet" href="/css/style_object.css" type="text/css" media="screen, projection" />
		<link rel="stylesheet" href="/css/style_news.css" type="text/css" media="screen, projection" />
		<!--[if lte IE 6]><link rel="stylesheet" href="/css/style_ie.css" type="text/css" media="screen, projection" /><![endif]-->

		<script src="/js/jquery-1.7.2.min.js"></script>
		<script src="http://api-maps.yandex.ru/1.1/index.xml?key=<?=$ya_key?>" type="text/javascript"></script>
		<script src="/js/ajax.js" type="text/javascript"></script>
		<script src="/js/tools.js" type="text/javascript"></script>
		<script src="/js/jquery.ocupload.js"></script>
		
		<script src="/fancybox/jquery.mousewheel-3.0.4.pack.js" type="text/javascript"></script>
		<script src="/fancybox/jquery.fancybox-1.3.4.pack.js" type="text/javascript"></script>
		<link href="/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" media="screen" />

		<link rel="icon" href="/favicon.ico" type="image/x-icon">

	</head>

	<body>

		<div id="wrapper">

			<div id="header">
				<div id="top">
					<div id="logo"><a href="/"><img src="/img/logo.png" width="400" height="60" alt="Поиск недвижимости в Екатеринбурге"></a></div>
					<? $this -> load -> view ( 'auth' ); ?>
				</div>
			</div>

			<div id="b1" class="banner">

<? /*
				<div style="position: relative;">
					<a target="_blank" href="http://artmoving.ru/content/view/299" style="position:absolute; width:1000px; height:90px; background:url('/img/pix.gif');"></a>
					<embed src="/img/banners/a-m.swf" quality="high" type="application/x-shockwave-flash" wmode="opaque" width="1000" height="90" pluginspage="http://www.macromedia.com/go/getflashplayer" allowScriptAccess="always"></embed>
				</div>
*/ ?>
			</div>

			<? if (isset($search)) { ?>
				<? $this->load->view('head_search', array('search'=>$search)); ?>
			<? } ?>

			<div id="content">
				<div id="text">
					<? if ( in_array($this->uri->segment(1), array('search', 'flat')) ) { ?>
						<div id="b2" class="banner">
							<? $img = rand(0,1) ? 'zastroyshik_728x90.png' : 'nedvijimost_720x90.png'; ?>
							<a href="/jurist"><img src="http://static.leadia.ru/banners/<?=$img?>" width="728" height="90"></a>
						</div>
					<? } ?>
