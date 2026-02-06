( function( $ ) {
	var TestimonialHandler = function( $scope, $ ) {

		$('.testimonial-slider').slick({
		    infinite: true,
		    slidesToShow: 2,
		    slidesToScroll: 2,
		    arrows: false,
		    dots: true,
		    autoplay: true,
		    autoplaySpeed: 3000,
		    responsive: [
		        {
		          breakpoint: 610,
		          settings: {
		            slidesToShow: 1,
		            slidesToScroll: 1
		          }
		        }
		    ]
		});

	};

	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/edunia_testimonial.default', TestimonialHandler );
	} );
} )( jQuery );