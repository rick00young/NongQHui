/* *************************************** */ 
/* Cart Button Drop Down */
/* *************************************** */  

$(document).ready(function() {
	$('.btn-cart-md .cart-link').click(function(e){
		e.preventDefault();
		var $dd_menu = $('.btn-cart-md .cart-dropdown')
		if ($dd_menu.hasClass('open')) {
			$dd_menu.fadeOut();
			$dd_menu.removeClass('open');
		} else {
			$dd_menu.fadeIn();
			$dd_menu.addClass('open');
		}
	});
});

/* *************************************** */ 
/* Tool Tip JS */
/* *************************************** */  

$('.my-tooltip').tooltip();

/* *************************************** */ 
/* Scroll to Top */
/* *************************************** */  
		
$(document).ready(function() {
	$(".totop").hide();
	
	$(window).scroll(function(){
	if ($(this).scrollTop() > 300) {
		$('.totop').fadeIn();
	} else {
		$('.totop').fadeOut();
	}
	});
	$(".totop a").click(function(e) {
		e.preventDefault();
		$("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	});
		
});
/* *************************************** */