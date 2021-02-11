/**
 * 2016-2020 Codezeel
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 *  @Module Name: CZ CouponPop Module
 *  @author    codezeel <support@codezeel.com>
 *  @copyright 2010-2019 codezeel
 *  @license   http://www.codezeel.com - prestashop template provider
 */

$(document).ready(function()
{
	/*$(document).on('click', '.open .cz-coupon-small, .open .share-coupon-small-wrapper', function()
	{
		showDialog();
	});
	
	$(document).on('click', '.close .cz-coupon-small, .close .share-coupon-small-wrapper', function()
	{
		closeDialog(cookies_time);
	});*/

	//showDialog();
	//closeDialog(cookies_time);
});


$(function(){
	$("html").bind("mouseleave", function () { 
		showDialog();
		$("html").unbind("mouseleave");
	});
});

function showDialog()
{  
	var data={'task':'showPopup'};
	$.ajax({
		type: "POST",
		cache: false,
		url: cz_coupon_url + '/front-end-ajax.php',
		dataType : "json",
		data: data,
		complete: function(){},
		success: function (response) {
			//console.log(response);
		}
	});   
	setTimeout(function(){
		$("#overlay").show();
		$(".newsletter-main").show();
		//$('.cz-show-newsletter-popup').removeClass('open').addClass('close');
	}, 500);
}

function closeDialog(cookies_time)
{  
	var data={'task':'cancelRegisNewslette1', 'cookies_time':cookies_time};        
	
	if ($('#notshow').is(':checked')){
        data.notshow = '1';
    }else{
        data.notshow = '0';
    }

	$.ajax({
		type: "POST",
		cache: false,
		url: cz_coupon_url + '/front-end-ajax.php',
		dataType : "json",
		data: data,
		complete: function(){},
		success: function (response) {
			//console.log(response);
		}
	});  

	setTimeout(function(){
		$("#overlay").hide();
		$(".newsletter-main").hide();
	}, 500);
}
function check_email(email){
	emailRegExp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]){2,4})$/;	
	if(emailRegExp.test(email)){
		return true;
	}else{
		return false;
	}
}
function regisNewsletter(){
    var data={'task':'regisNewsletter', 'action':0};
    var email = $("#newsletter_input_email").val();
    if(check_email(email) == true){
        data.email = email;
        $("#regisNewsletterMessage").html("");
    }else{
        $("#regisNewsletterMessage").html('<p class="alert alert-danger">'+enterEmail+'</p>');
        return false;
    }
    
    if ($('#notshow').is(':checked')){
        data.notshow = '1';
    }else{
        data.notshow = '0';
    }
    $.ajax({
		type: "POST",
		cache: false,
		url: cz_coupon_url + '/front-end-ajax.php',
		dataType : "json",
		data: data,
        complete: function(){},
		success: function (response)
		{

			if(response.indexOf("@")>0)
			{
				var new_response = response.substring(response.indexOf("@")+1, response.length);
				$("#regisNewsletterMessage").html('<p class="alert alert-success">'+new_response+'</p>');
			}
			else {
				$("#regisNewsletterMessage").html('<p class="alert alert-warning">'+response+'</p>');
			}
			$("#coupon-text-before").hide();
			$("#coupon-text-after").show();
			
		},
		 error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("Status: " + textStatus); 
            alert("Error: " + errorThrown);
        }  
	});
}