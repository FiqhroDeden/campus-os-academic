( function( $ ) {
	var AchievementsHandler = function( $scope, $ ) {

		$('.latachieve-slider').slick({
		  infinite: true,
		  slidesToShow: 4,
		  slidesToScroll: 1,
		  arrows: false,
		  dots: false,
		  prevArrow: '<button class="prev"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10.828 12l4.95 4.95-1.414 1.414L8 12l6.364-6.364 1.414 1.414z"/></svg></button>',
		    nextArrow: '<button class="next"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg></button>',
		  responsive: [
		    {
		      breakpoint: 810,
		      settings: {
		        slidesToShow: 3,
		        slidesToScroll: 1,
		        arrows: true,
		      }
		    },
		    {
		      breakpoint: 610,
		      settings: {
		        slidesToShow: 2,
		        slidesToScroll: 1,
		        arrows: true,
		      }
		    },
		    {
		        breakpoint: 430,
		        settings: {
		          slidesToShow: 1,
		          slidesToScroll: 1,
		          arrows: true,
		        }
		    }
		]
		});

	};

	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/edunia_achievement.default', AchievementsHandler );
	} );
} )( jQuery );