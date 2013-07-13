jQuery(document).ready(function(){

	/* слайдер цен */
	jQuery("#sliderPrice").slider({
		min: 1000,
		max: 10000,
		values: [jQuery("input#minPrice").val(),jQuery("input#maxPrice").val()],
		range: true,
		stop: function(event, ui) {
			jQuery("input#minPrice").val(jQuery("#sliderPrice").slider("values",0));
			jQuery("input#maxPrice").val(jQuery("#sliderPrice").slider("values",1));
		},
		slide: function(event, ui) {
			jQuery("input#minPrice").val(jQuery("#sliderPrice").slider("values",0));
			jQuery("input#maxPrice").val(jQuery("#sliderPrice").slider("values",1));
		}
	});

	/* слайдер площадей */
	jQuery("#sliderSpace").slider({
		min: 20,
		max: 100,
		values: [jQuery("input#minSpace").val(),jQuery("input#maxSpace").val()],
		range: true,
		stop: function(event, ui) {
			jQuery("input#minSpace").val(jQuery("#sliderSpace").slider("values",0));
			jQuery("input#maxSpace").val(jQuery("#sliderSpace").slider("values",1));
		},
		slide: function(event, ui) {
			jQuery("input#minSpace").val(jQuery("#sliderSpace").slider("values",0));
			jQuery("input#maxSpace").val(jQuery("#sliderSpace").slider("values",1));
		}
	});

	/* слайдер этажей */
	jQuery("#sliderFloor").slider({
		min: 1,
		max: 12,
		values: [jQuery("input#minFloor").val(),jQuery("input#maxFloor").val()],
		range: true,
		stop: function(event, ui) {
			jQuery("input#minFloor").val(jQuery("#sliderFloor").slider("values",0));
			jQuery("input#maxFloor").val(jQuery("#sliderFloor").slider("values",1));
		},
		slide: function(event, ui) {
			jQuery("input#minFloor").val(jQuery("#sliderFloor").slider("values",0));
			jQuery("input#maxFloor").val(jQuery("#sliderFloor").slider("values",1));
		}
	});

	/* чекбоксы */
	$("span.checkbox").click(function() {
		var chk = $(this).find('input:checkbox').attr('checked');
		$(this).find('input:checkbox').attr('checked', !chk);
		
		if (!chk) $(this).addClass('checked');
		else $(this).removeClass('checked');
		
	});



	jQuery("input#minCost").change(function()
	{
		var value1 = jQuery("input#minCost").val();
		var value2 = jQuery("input#maxCost").val();

		if ( parseInt(value1) > parseInt(value2) )
		{
			value1 = value2;
			jQuery("input#minCost").val(value1);
		}
		jQuery("#slider").slider("values",0,value1);
	});


	jQuery("input#maxCost").change(function()
	{
		var value1=jQuery("input#minCost").val();
		var value2=jQuery("input#maxCost").val();
		
		if ( value2 > 1000 )
		{ 
			value2 = 1000; 
			jQuery("input#maxCost").val(1000)
		}
		
		if ( parseInt(value1) > parseInt(value2) )
		{
			value2 = value1;
			jQuery("input#maxCost").val(value2);
		}
		jQuery("#slider").slider("values",1,value2);
	});


	// фильтрация ввода в поля
	jQuery('input').keypress(function(event)
	{
		var key, keyChar;
		if(!event) var event = window.event;
		
		if (event.keyCode) key = event.keyCode;
		else if(event.which) key = event.which;
		
		if(key==null || key==0 || key==8 || key==13 || key==9 || key==46 || key==37 || key==39 ) return true;
		keyChar=String.fromCharCode(key);
		
		if(!/\d/.test(keyChar))	return false;
	});

	// аккордион
	jQuery('.accordion .head').click(function() {
		$(this).next().toggle('slow');
		return false;
	}).next().hide();

});


