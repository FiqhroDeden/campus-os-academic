( function( $ ) {
	var PartnershipHandler = function( $scope, $ ) {

		$('.partner-list').slick({
		    infinite: true,
		    slidesToShow: 6,
		    slidesToScroll: 1,
		    arrows: true,
		    dots: false,
		    autoplay: true,
		    autoplaySpeed: 2000,
		    prevArrow: '<button class="prev"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M8 12l6-6v12z"/></svg></button>',
		    nextArrow: '<button class="next"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16 12l-6 6V6z"/></svg></button>',
		    responsive: [
		        {
		          breakpoint: 610,
		          settings: {
		            slidesToShow: 4,
		            slidesToScroll: 1,
		            arrows: false
		          }
		        },
		        {
		            breakpoint: 420,
		            settings: {
		              slidesToShow: 3,
		              slidesToScroll: 1,
		              arrows: false
		            }
		        }
		    ]
		});

	};

	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/edunia_partnership.default', PartnershipHandler );
	} );
} )( jQuery );