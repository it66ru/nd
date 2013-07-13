<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Недвижимость в Екатеринбурге. PN66 - Поиск недвижимости в Екатеринбурге</title>
		<meta name="title" content="PN66.ru - Поиск недвижимости в Екатеринбурге" />
		<meta name="keywords" content="PN66.ru - Поиск недвижимости в Екатеринбурге" />
		<meta name="description" content="PN66.ru - Поиск недвижимости в Екатеринбурге" />
		<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen, projection" />
		<link rel="stylesheet" href="/css/style_object.css" type="text/css" media="screen, projection" />
		<link rel="stylesheet" href="/css/style_main.css" type="text/css" media="screen, projection" />
		<!--[if lte IE 6]><link rel="stylesheet" href="/css/style_ie.css" type="text/css" media="screen, projection" /><![endif]-->
		<link rel="icon" href="/favicon.ico" type="image/x-icon">
		<script src="/js/jquery-1.7.2.min.js"></script>
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

			<?  $this->load->view('head_search', array('search'=>$search));  ?>

			<div id="content">
				<div id="text">
					<h1>Недвижимость в Екатеринбурге</h1>
					<p class="desc">
						PN66 – база объявлений о продажи недвижимости в Екатеринбурге.
						В нашей базе Вы найдете огромное количество предложений от частных лиц и
						<a href="/agency" title="Агентства недвижимости">агентств недвижимости Екатеринбурга</a>.
					</p>
					<div id="index_objects">
						<div class="block">
							<h3><a href="/search/1k" title="Продажа однокомнатных квартир">Однокомнатные квартиры</a></h3>
							<?=(isset($objects[1][0])?view_object($objects[1][0]):'')?>
							<?=(isset($objects[1][1])?view_object($objects[1][1]):'')?>
							<?=(isset($objects[1][2])?view_object($objects[1][2]):'')?>
							<div class="search_links">
								<? foreach ( $links[1] as $link => $name ) { ?>
									<a href="/search/<?=$link?>"><?=$name?></a><br />
								<? } ?>
							</div>
							<div class="clear"></div>
						</div>
						<div class="block">
							<h3><a href="/search/2k" title="Продажа двухкомнатных квартир">Двухкомнатные квартиры</a></h3>
							
							<?=(isset($objects[2][0])?view_object($objects[2][0]):'')?>
							<?=(isset($objects[2][1])?view_object($objects[2][1]):'')?>
							<?=(isset($objects[2][2])?view_object($objects[2][2]):'')?>
							<div class="search_links">
								<? foreach ( $links[2] as $link => $name ) { ?>
									<a href="/search/<?=$link?>"><?=$name?></a><br />
								<? } ?>
							</div>
							<div class="clear"></div>
						</div>
						<div class="block">
							<h3><a href="/search/3k" title="Продажа трехкомнатных квартир">Трехкомнатные квартиры</a></h3>
							<?=(isset($objects[3][0])?view_object($objects[3][0]):'')?>
							<?=(isset($objects[3][1])?view_object($objects[3][1]):'')?>
							<?=(isset($objects[3][2])?view_object($objects[3][2]):'')?>
							<div class="search_links">
								<? foreach ( $links[3] as $link => $name ) { ?>
									<a href="/search/<?=$link?>"><?=$name?></a><br />
								<? } ?>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div id="b3" class="banner"></div>
				</div>
				<div id="advert">
					<p align="center" style="margin-bottom: 10px">
						<a href="/reklama">Реклама на сайте</a>
					</p>
					<div id="b4" class="banner">
						<a href="/reklama" target="_blank"><img src="/img/248x398.jpg" width="248" height="398" alt="Реклама на сайте"></a>
						<? /* <a href="http://expert-n.su" target="_blank"><img src="/img/banners/expert-n.gif" width="250" height="400"></a> */ ?>
					</div>
					<div id="b6" class="banner"></div>
				</div>
			</div>
		</div>

		<? $this->load->view('footer'); ?>

	</body>

</html>

<? function view_object ( $data ) { ?>
	<div class="object">
		<div class="foto">
			<a href="/flat/info/<?=$data['id']?>"><img src="/foto/<?=$data['id']?>/small/<?=$data['foto']?>" alt=""></a>
		</div>
		<div class="adr">
			<a href="/flat/info/<?=$data['id']?>"><?=$data['addr']?></a>
		</div>
		<div class="pl">
			<?=$data['space_total']?> кв. м.
		</div>
		<div class="price">
			<?=number_format($data['price'],0,'.',' ')?> р.
		</div>
	</div>
<? } ?>
