
	<? $this -> load -> view ( 'admin/head' ); ?>

  <style type="text/css">
   .column {
    -moz-column-width: 200px;
    -webkit-column-width: 200px;
    -moz-column-count: 3;
    -webkit-column-count: 2;
    -moz-column-gap: 30px;
    -webkit-column-gap: 30px;
    -moz-column-rule: 1px solid #ccc;
    -webkit-column-rule: 1px solid #ccc;
   }
   .number3 {
    width: 30px;
    text-align: right;
    display: inline-block;
    margin-right: 5px;
    color: #999;   }
   .number5 {
    width: 40px;
    text-align: right;
    display: inline-block;
    margin-right: 5px;
    color: #999;
   }

  </style>


	<div style="float:left; line-height:20px;" class="column">
		<? foreach ( $ul as $r ) { ?>
			<span class="number3"><?=$r['id']?></span>
			<a href="/admin/dom/<?=$r['id']?>"><?=$r['name']?></a> &nbsp;
			<? if ( $r['count'] == 0 ) { ?>
				<a href="/admin/add_dom/<?=$r['id']?>" target="_blank">+</a>
			<? } else echo $r['count'] ?>
			<br />
		<? } ?>
	</div>

	<div style="float:left; line-height:20px;">
		<h2>Дома</h2>
		<br />
		<? foreach ( $dom as $r ) { ?>
			<span class="number5"><?=$r['id']?></span><a href="/"><?=$r['dom']?></a><br />
		<? } ?>
	</div>


	<? $this -> load -> view ( 'admin/foot' ); ?>
