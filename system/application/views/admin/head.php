<? $gm_key = 'ABQIAAAAA_9XqpcxJQYWlax9IpoM7BSu770tVCVhqcIDn-B9IvFNju4P3hT53hQKhzu8U3ZfZRf65JfrgYPXzg'; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>PN66.ru</title>

		<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen, projection" />
		<link rel="stylesheet" href="/css/style_object.css" type="text/css" media="screen, projection" />
		<!--[if lte IE 6]><link rel="stylesheet" href="/css/style_ie.css" type="text/css" media="screen, projection" /><![endif]-->

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
		<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=<?=$gm_key?>" type="text/javascript"></script>
		<script src="/js/ajax.js" type="text/javascript"></script>
		<script src="/js/jquery.ocupload.js"></script>
		<script src="/js/jquery.maskedinput.js"></script>

		<script src="/js/jquery.ui.core.js"></script>
		<script src="/js/jquery.ui.widget.js"></script>
		<script src="/js/jquery.ui.position.js"></script>
		<script src="/js/jquery.ui.autocomplete.js"></script>
		<script src="/js/jquery-ui-1.8.22.custom.min.js"></script>
		<link rel="stylesheet" href="/css/ui/jquery-ui.css">

		<link rel="icon" href="/favicon.ico" type="image/x-icon">

	</head>

	<body onunload="GUnload()">

		<div id="wrapper">

			<div id="header">
				<div id="top">
					<div id="logo"><a href="/"><img src="/img/logo.png" width="400" height="60" alt="Поиск недвижимости в Екатеринбурге"></a></div>
					<div id="navi">
						<? if ($this->auth->user['type'] == 'moderator') { ?>
							<a href="/moderation">Статистика</a>
							<a href="/moderation/next">Модерация</a>
						<? } ?>
						<? if ($this->auth->user['type'] == 'admin') { ?>
							<a href="/admin/objects">Объявления</a>
							<a href="/admin/users">Юзеры</a>
							<a href="/admin/mstat">Модераторы</a>
							<a href="/admin/parse_log" target="_blank">Лог парса</a>
							<a href="/admin/check_not_found">Не найдены</a>
							<a href="/admin/unparse">Не распознанные</a>
							<a href="/admin/houses">Дома</a>
						<? } ?>
					</div>
				</div>
			</div>

			<div id="content">
