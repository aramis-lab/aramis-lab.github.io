jQuery(function($) {

    'use strict';

    /*-----------------------------------------------------------------
     * Variables
     *-----------------------------------------------------------------*/

    var $body_html = jQuery('body, html'),
        $html = jQuery('html'),
        $body = jQuery('body'),

        $navigation = jQuery('#navigation'),
        navigation_height = $navigation.height() - 20,

        $scroll_to_top = jQuery('#scroll-to-top'),

        $preloader = jQuery('#preloader'),
        $loader = $preloader.find('.loader');

    if (navigation_height <= 0) navigation_height = 60;

    /*-----------------------------------------------------------------
     * Is mobile
     *-----------------------------------------------------------------*/

    var ua_test = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i,
        is_mobile = ua_test.test(navigator.userAgent);

    $html.addClass(is_mobile ? 'mobile' : 'no-mobile');
	/*-----------------------------------------------------------------
     * ScrollSpy
     *-----------------------------------------------------------------*/

    $body.scrollspy({
        offset:  51,
        target: '#navigation'
    });

    /*-----------------------------------------------------------------
     * Affixed Navbar
     *-----------------------------------------------------------------*/
	if( jQuery('.affix').length){
		jQuery('.affix').affix({
			offset: {
				top: navigation_height
			}
		});
	}

    /*-----------------------------------------------------------------
     * Dropdown By Click on Mobile
     *-----------------------------------------------------------------*/

    if (is_mobile) {
       jQuery('.dropdown-toggle').each(function() {
           jQuery(this).attr('data-toggle', 'dropdown');
        });
    }

    /*-----------------------------------------------------------------
     * Scroll To Top
     *-----------------------------------------------------------------*/

   jQuery(window).scroll(function () {

        var $scroll_top = jQuery(this).scrollTop();

        if ($scroll_top > navigation_height) {
            $scroll_to_top.addClass('in');
        } else {
            $scroll_to_top.removeClass('in');
        }
    });

    $scroll_to_top.click(function() {
        $.scrollWindow(0);
    });
	$.scrollWindow = function(offset) {
        $body_html.animate({
            scrollTop: offset
        }, 1500);
    };
 	/*-----------------------------------------------------------------
     * Smooth Scrolling
     *-----------------------------------------------------------------*/
	
		jQuery('a[href^="#"]').click(function(event) {
			
			event.preventDefault();
			
			var $this = jQuery(this),
				target = $this.attr('href');
	
			// Don't return false!
			if (target == '#') return;
	
			if ($this.hasClass('smooth-scroll')) {
				var offset = $(target).offset().top - navigation_height;
				$.scrollWindow(offset);
			}
		});
	
		$.scrollWindow = function(offset) {
			$body_html.animate({
				scrollTop: offset
			}, 1500);
		};
	
	/*-----------------------------------------------------------------
     * Carousels
     *-----------------------------------------------------------------*/

		// Home Page Slider
		if(jQuery(".slider").length){
			jQuery(".slider").owlCarousel({
				autoPlay              : true,
				singleItem            : true,
				responsive            : true,
				autoHeight            : true,
		
				mouseDrag             : true,
				touchDrag             : true,
		
				responsiveRefreshRate : 0,
				transitionStyle       : 'fadeUp',
				navigation        : true,
				navigationText    : [
				'<i class="fa fa-angle-left"></i>',
				'<i class="fa fa-angle-right"></i>'
				]
			});
		}
   /*-----------------------------------------------------------------
     * Magnific
     *-----------------------------------------------------------------*/

    jQuery('.image-popup').magnificPopup({
        closeBtnInside : true,
        type           : 'image',
        mainClass      : 'mfp-with-zoom'
    });

   

    /*-----------------------------------------------------------------
     * Finish loading
     *-----------------------------------------------------------------*/

    jQuery(window).load(function() {
		
        /* Remove preloader */
        $loader.delay(1500).fadeOut();
        $preloader.delay(500).fadeOut('slow');

        $body.addClass('loaded');

    });

});