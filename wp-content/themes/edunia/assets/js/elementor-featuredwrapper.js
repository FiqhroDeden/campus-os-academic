( function( $ ) {
	var FeaturedSliderHandler = function( $scope, $ ) {

		$('.featured-slider').slick({
		    infinite: true,
		    slidesToShow: 1,
		    slidesToScroll: 1,
		    arrows: false,
		    dots: true,
		    fade: true,
		    cssEase: 'linear',
		    autoplay: true,
		    autoplaySpeed: 5000,
		});

	};

	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/edunia_featuredwrapper.default', FeaturedSliderHandler );
	} );
} )( jQuery );