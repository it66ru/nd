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
	</head>

	<body>
		<div id="wrapper">
			<div id="header">
				<div id="top">
					<div id="logo"><a href="/"><img src="/img/logo.png" width="400" height="60" alt="Поиск недвижимости в Екатеринбурге"></a></div>
					<? $this->load->view('auth'); ?>
				</div>
			</div>
			<div id="content">
				<div id="text">
					<h1>Импорт объявлений</h1>
					<p>
						По всем вопросам автоматического размещения объявлений на сайте, обращайтесь:<br />
						<img src="/img/email.png" width="16" height="16" align="absmiddle"> <a href="mailto:info@pn66.ru">info@pn66.ru</a> 
						&nbsp; &nbsp;
						<img src="http://wwp.icq.com/scripts/online.dll?icq=317721&amp;img=5" align="absmiddle"> 31-77-21
					</p>
					
					<div class="clear"><br /><br /></div>
					
					<h2>Требования к файлу объявлений</h2>
					<br />
					<ol>
						<li>Объявления предоставляются в xml-формате.</li>
						<li>В файле должны передаваться только актуальные объявления.</li>
						<li>В данных не допускается присутствие HTML-тегов.</li>
						<li>URL файла должен быть постоянным и доступным по протоколу HTTP.</li>
						<li>URL объявления должен быть постоянным. Объявления, должны обновляться, а не удаляться и создаваться заново.</li>
					</ol>
					
					<div class="clear"><br /><br /></div>
					
					<h2>Описание формата данных</h2>
					<br />
					<p>Заголовок документа (XML header)</p>
					<div class="code">
						<?=htmlspecialchars('<?xml version="1.0" encoding="utf-8"?>')?>
					</div>
					<p>Стандартный XML-заголовок. Заголовок должен начинаться с первой строки, с нулевого символа.</p>
					<p>Документ должен содержать корневой элемент realty-feed.</p>
					<p>Файл должен быть в кодировке UTF-8.</p>
					<br />
					<h3>Элемент realty-feed</h3>
					<p>Элемент realty-feed должен содержать следующие элементы:</p>
					<div class="code">
						<?=htmlspecialchars('<realty-feed>')?><br />
						<div style="padding-left:1em">
							<?=htmlspecialchars('<generation-date>2010-10-05T16:36:00+04:00</generation-date>')?><br />
							<?=htmlspecialchars('...')?>
						</div>
						<?=htmlspecialchars('</realty-feed>')?>
					</div>
					<p><b>generation-date</b> — содержит информацию о дате и времени создания данного файла</p>
					<br />
					<h3>Формат даты</h3>
					<p>Формат даты YYYY-MM-DDTHH:mm:ss+04:00. Стандартный формат ISO 8601 (<a href="http://en.wikipedia.org/wiki/ISO_8601" target="_blank">http://en.wikipedia.org/wiki/ISO_8601</a>):
						<div style="padding-left:1em">
							YYYY — год<br />
							MM — месяц<br />
							DD — день<br />
							HH — час<br />
							mm — минута<br />
							ss — секунда<br />
							+ 04:00 — указание часового пояса (в данном случае — для Москвы).
						</div>
					</p>
					<br />
					<h3>Описание параметров, входящих в элемент <?=htmlspecialchars('<offer>')?></h3>
					<p>У элемента <?=htmlspecialchars('<offer>')?> есть обязательный атрибут internal-id — id объявления в базе партнера (в вашей базе).</p>
					<p>Обязательные элементы отмечены символом «*».</p>
					<p>Элементы, содержащие текстовые поля с пометкой «строго ограниченные значения », должны содержать только те значения, которые указаны. Использование других значений будет считаться ошибкой.</p>
					<br />
					<table class="tabe">
						<tr>
							<th>Элементы</th>
							<th>Описание</th>
						</tr>
						<tr>
							<td nowrap>type*</td>
							<td>тип сделки («продажа», «аренда»)</td>
						</tr>
						<tr>
							<td nowrap>category*</td>
							<td>категория объекта («комната», «квартира»)</td>
						</tr>
						<tr>
							<td nowrap>url *</td>
							<td>URL страницы с объявлением</td>
						</tr>
						<tr>
							<td nowrap>creation-date*</td>
							<td>дата создания объявления формат даты такой же, как в поле generation-date</td>
						</tr>
						<tr>
							<td nowrap>last-update-date</td>
							<td>дата последнего обновления объявления формат даты такой же, как в поле generation-date</td>
						</tr>
						<tr>
							<td nowrap>address*</td>
							<td>город, улица, дом</td>
						</tr>
					</table>







					
					
					
					<h2>Рекламные баннеры на главной странице PN66.ru</h2>
					<img src="/img/advert/index.png" width="300" height="370" style="float:left">
					<table class="tab" style="float:left; margin-left:25px;">
						<tr>
							<th>Позиция</th>
							<th>Размер</th>
							<th>Цена в мес.</th>
						</tr>
						<tr>
							<td>1. Верхний баннер</td>
							<td align="center">1000 &times; 90</td>
							<td align="right">1 000 руб.</td>
						</tr>
						<tr>
							<td>2. Боковой баннер</td>
							<td align="center">250 &times; 400</td>
							<td align="right">1 000 руб.</td>
						</tr>
						<tr>
							<td>3. Боковой баннер</td>
							<td align="center">250 &times; 400</td>
							<td align="right">1 000 руб.</td>
						</tr>
						<tr>
							<td>4. Нижний баннер</td>
							<td align="center">730 &times; 90</td>
							<td align="right">1 000 руб.</td>
						</tr>
					</table>
					<div class="clear"><br /><br /></div>
					
					<h2>Рекламные баннеры на странице поиска</h2>
					<img src="/img/advert/search.png" width="300" height="470" style="float:left">
					<table class="tab" style="float:left; margin-left:25px;">
						<tr>
							<th>Позиция</th>
							<th>Размер</th>
							<th>Цена в мес.</th>
						</tr>
						<tr>
							<td>5. Верхний баннер</td>
							<td align="center">1000 &times; 90</td>
							<td align="right">1 000 руб.</td>
						</tr>
						<tr>
							<td>6. Верхний баннер</td>
							<td align="center">730 &times; 90</td>
							<td align="right">1 000 руб.</td>
						</tr>
						<tr>
							<td>7. Боковой баннер</td>
							<td align="center">250 &times; 400</td>
							<td align="right">1 000 руб.</td>
						</tr>
						<tr>
							<td>8. Боковой баннер</td>
							<td align="center">250 &times; 170</td>
							<td align="right">1 000 руб.</td>
						</tr>
						<tr>
							<td>9. Боковой баннер</td>
							<td align="center">250 &times; 400</td>
							<td align="right">1 000 руб.</td>
						</tr>
						<tr>
							<td>10. Боковой баннер</td>
							<td align="center">250 &times; 170</td>
							<td align="right">1 000 руб.</td>
						</tr>
						<tr>
							<td>11. Нижний баннер</td>
							<td align="center">730 &times; 90</td>
							<td align="right">1 000 руб.</td>
						</tr>
					</table>
					<div class="clear"><br /><br /></div>
					
					<h2>Рекламные баннеры на странице объекта</h2>
					<img src="/img/advert/info.png" width="300" height="350" style="float:left">
					<table class="tab" style="float:left; margin-left:25px;">
						<tr>
							<th>Позиция</th>
							<th>Размер</th>
							<th>Цена в мес.</th>
						</tr>
						<tr>
							<td>12. Верхний баннер</td>
							<td align="center">1000 &times; 90</td>
							<td align="right">1 000 руб.</td>
						</tr>
						<tr>
							<td>13. Верхний баннер</td>
							<td align="center">730 &times; 90</td>
							<td align="right">1 000 руб.</td>
						</tr>
						<tr>
							<td>14. Боковой баннер</td>
							<td align="center">250 &times; 400</td>
							<td align="right">1 000 руб.</td>
						</tr>
						<tr>
							<td>15. Боковой баннер</td>
							<td align="center">250 &times; 170</td>
							<td align="right">1 000 руб.</td>
						</tr>
					</table>
					<div class="clear"><br /><br /></div>
				</div>
				<div id="advert">
					<div id="b4" class="banner">
						<img src="/img/248x398.jpg" width="248" height="398" alt="Реклама на сайте">
					</div>
				</div>
			</div>
		</div>
		<? $this->load->view('footer'); ?>
	</body>
</html>

