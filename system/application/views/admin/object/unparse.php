
	<? $this -> load -> view ( 'admin/head' ); ?>

	<h1>Не распознанные - <?=$count?></h1>

		<script src="/js/jquery.ui.core.js"></script>
		<script src="/js/jquery.ui.widget.js"></script>
		<script src="/js/jquery.ui.position.js"></script>
		<script src="/js/jquery.ui.autocomplete.js"></script>
		<link rel="stylesheet" href="/css/ui/jquery-ui.css">

		<? foreach ( $objects as $r ) { ?>
			<div style="padding:10px 0">
				<div>
					<a href="/moderator/object/e/<?=$r['id']?>"><?=$r['id']?></a> &nbsp;
					<?=$r['address']?> &nbsp;
					<b><?=number_format($r['price'], 0, ',', ' ')?> р.</b>
				</div>
				<div>
					<form method="post">
						ул <input name="ul" type="text" class="w250" id="street_<?=$r['id']?>"> &nbsp;
						д  <input name="d" type="text" class="w50" id="building_<?=$r['id']?>"> &nbsp;
						<input name="house_id" type="text" class="w50" id="house_id_<?=$r['id']?>">
						<input name="object_id" type="text" class="w50" value="<?=$r['id']?>">
						<input name="ok" type="submit" value=">" class="button">
					</form>
					<script>
						$(function() {
							$('#street_<?=$r['id']?>').autocomplete({
								source: '/ajax/get_street', 
								minLength: 2,
								select: function(event, ui) {
									$('#building_<?=$r['id']?>').autocomplete('option', 'source', '/ajax/get_building/'+ui.item.id);
									$('#building_<?=$r['id']?>').val('');
									$('#house_id_<?=$r['id']?>').val(0);
									
								},
							});
							$('#building_<?=$r['id']?>').autocomplete({
								minLength: 1,
								select: function(event, ui) {
									$('#house_id_<?=$r['id']?>').val(ui.item.id);
								},
							});
						});
					</script>
				</div>
				<div>
					<?=$r['description']?>
				</div>
				<div>
					<? foreach ( $reasons as $url => $name ) { ?>
						<a href="/admin/unparse/<?=$r['id']?>/<?=$url?>"><?=$name?></a> &nbsp; &nbsp;
					<? } ?>
				</div>
			</div>
		<? } ?>


	<? $this -> load -> view ( 'admin/foot' ); ?>
