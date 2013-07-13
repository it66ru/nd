
	<? $this->load->view('head', $head); ?>

	<? $this->load->view('flat/top_object'); ?>

	<div class="clear"></div>

	<ul class="bookmark">
		<? foreach ( $sections as $url => $name ) { ?>
			<? if ( $this->uri->segment(2) == $url ) { ?>
				<li class="current"><?=$name?></li>
			<? } else { ?>
				<li><a href="/flat/<?=$url?>/<?=$this->Mobject->id?>"><?=$name?></a></li>
			<? } ?>
		<? } ?>
	</ul>

	<script type="text/javascript">
		$(document).ready(function() {
			$("a[rel=example_group]").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'titlePosition' 	: 'over',
			});
		});
	</script>

	<div style="float:left; width:308px;">
		<? if ( $foto = $this->Mobject->foto ) { ?>
			<? $main_foto = array_shift($foto); ?>
			<div style="border: 1px solid #ddd; text-align:center; margin:1px;">
				<a rel="example_group" href="/foto/<?=$this->Mobject->id?>/large/<?=$main_foto?>"><img src="/foto/<?=$this->Mobject->id?>/middle/<?=$main_foto?>" alt="Фотография 1"></a>
			</div>
			<? foreach ( $foto as $f ) { ?>
			<div style="border: 1px solid #ddd; text-align:center; float:left; width:150px; margin:1px;">
				<a rel="example_group" href="/foto/<?=$this->Mobject->id?>/large/<?=$f?>"><img src="/foto/<?=$this->Mobject->id?>/small/<?=$f?>"></a>
			</div>
			<? } ?>
		<? } else { ?>
		<img src="/img/no_foto.png" width="300" height="200" alt="Фотографии отсутствуют" style="border: 1px solid #ddd">
		<? } ?>
		<div class="clear"></div>
		<p class="grey" style="font-size:11px; margin-top:5px;">Дата создания: <?=$this->Mobject->data['cdate']?></p>
		<p class="grey" style="font-size:11px; margin-top:5px;">Дата изменения: <?=$this->Mobject->data['edate']?></p>
	</div>

	<div style="float:left; margin-left:20px; width:400px;">
<? /*
		<div class="LikeButton">
			<script src="http://connect.facebook.net/ru_RU/all.js#xfbml=1"></script>
			<fb:like href="" show_faces="false" width="450" font=""></fb:like>
		</div>
*/ ?>

		<? if ( $this->Mobject->data['description'] ) { ?>
			<p style="margin-bottom: 20px;"><?=$this->Mobject->data['description']?></p>
		<? } ?>


		<p class="red" style="font-size:16px; margin-bottom:10px">Характеристики квартиры</p>
		<p style="line-height: 20px;">
			&nbsp;–&nbsp; Количество комнат: <?=$this->Mobject->data['rooms']?><br />
			<? foreach ( $i as $k => $label ) if ($this->Mobject->data['info'][$k]) { ?>
				&nbsp;–&nbsp; <?=$label?>: <?=$this->Mobject->data['info'][$k]?><br />
			<? } ?>
		</p>
		</div>

	<div class="clear"></div>

	<? $this->load->view('foot'); ?>
