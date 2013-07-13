
	<? $this -> load -> view ( 'admin/head' ); ?>

	<h1>Ошибки объявлений</h1>

	<h3>Цена</h3>
	Отсутствует: <a href="/errors/objects/price_none"><?=$obj['price_none']?></a><br />
	Завышена:    <a href="/errors/objects/price_high"><?=$obj['price_high']?></a><br />
	Занижена:    <a href="/errors/objects/price_low"><?=$obj['price_low']?></a><br />
	<br />

	<h3>Площадь</h3>
	Отсутствует:   <a href="/errors/objects/space_none"><?=$obj['space_none']?></a><br />
	Ошибка суммы:  <a href="/errors/objects/space_sum"><?=$obj['space_sum']?></a><br />
	Отклонение:    <a href="/errors/objects/space_dev"><?=$obj['space_dev']?></a><br />
	<br />

	<h3>Этаж</h3>
	Отсутствует:      <a href="/errors/objects/floor_none"><?=$obj['floor_none']?></a><br />
	Больше этажности: <a href="/errors/objects/floor_storey"><?=$obj['floor_storey']?></a><br />
	<br />

	<h3>Телефон</h3>
	Отсутствует:    <a href="/errors/objects/phone_none"><?=$obj['phone_none']?></a><br />
	Не по формату:  <a href="/errors/objects/phone_format"><?=$obj['phone_format']?></a><br />
	<br />
	<br />
	<br />


	<h1>Данные домов</h1>
	Этажность: <a href=""><?=$building['storey']?></a><br />
	Год постройки: <a href=""><?=$building['gp']?></a><br />
	Материал: <a href=""><?=$building['material']?></a><br />
	Тип: <a href=""><?=$building['type']?></a><br />

	<? $this -> load -> view ( 'admin/foot' ); ?>

