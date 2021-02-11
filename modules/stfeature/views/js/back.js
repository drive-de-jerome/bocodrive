/**
 * 2010-2019 Codezeel
 *
 * NOTICE OF LICENSE
 *
 * Tm feature for prestashop 1.7: compare, wishlist at product list 
 *
 * DISCLAIMER
 *
 *  @Module Name: CZ Feature
 *  @author    codezeel <support@codezeel.com>
 *  @copyright 2010-2019 codezeel
 *  @license   http://www.codezeel.com - prestashop template provider
 */
$(document).ready(function() {
	$('select#id_product_review_criterion_type').change(function() {
		// PS 1.6
		$('#categoryBox').closest('div.form-group').hide();
		$('#ids_product').closest('div.form-group').hide();
		// PS 1.5
		$('#categories-treeview').closest('div.margin-form').hide();
		$('#categories-treeview').closest('div.margin-form').prev().hide();
		$('#ids_product').closest('div.margin-form').hide();
		$('#ids_product').closest('div.margin-form').prev().hide();

		if (this.value == 2)
		{
			$('#categoryBox').closest('div.form-group').show();
			// PS 1.5
			$('#categories-treeview').closest('div.margin-form').show();
			$('#categories-treeview').closest('div.margin-form').prev().show();
		}
		else if (this.value == 3)
		{
			$('#ids_product').closest('div.form-group').show();
			// PS 1.5
			$('#ids_product').closest('div.margin-form').show();
			$('#ids_product').closest('div.margin-form').prev().show();
		}
	});

	$('select#id_product_review_criterion_type').trigger("change");
	
	//DONGND:: tab change in group config
	var id_panel = $("#leofeature-setting .leofeature-tablist li.active a").attr("href");
	$(id_panel).addClass('active').show();
	$('.leofeature-tablist li').click(function(){
		if(!$(this).hasClass('active'))
		{
			var default_tab = $(this).find('a').attr("href");			
			$('#STFEATURE_DEFAULT_TAB').val(default_tab);
		}
	})
	
	// console.log('test');
	$.ajax({
		type: 'POST',
		headers: {"cache-control": "no-cache"},
		url: leofeature_module_dir + 'psajax.php',
		async: true,
		cache: false,
		data: {
			"action": "get-new-review",		
		},
		success: function (result)
		{
			if(result != '')
			{						
				var obj = $.parseJSON(result);
				if (obj.number_review > 0)
				{
					$('#subtab-AdminStfeatureManagement').addClass('has-review');
					// $('#subtab-AdminStfeatureReviews').append('<span id="total_notif_number_wrapper" class="notifs_badge"><span id="total_notif_value">'+obj.number_review+'</span></span>');
					$('#subtab-AdminstfeatureReviews').append('<div class="notification-container"><span class="notification-counter">'+obj.number_review+'</span></div>');
				}
				
			}
			
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			// alert("TECHNICAL ERROR: \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
		}
	});
});