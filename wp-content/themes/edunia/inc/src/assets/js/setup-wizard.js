jQuery(document).ready(function($){
	window.onload = function() {
		$('.preload-overlay').fadeOut('slow');
	}

	$('.garudatheme-wizard-wrap .step-wrapper').not('#step-1').hide();

	$('.next-step').click(function(e) {
	    e.preventDefault();
	    var currentTab = $(this).closest('.step-wrapper');
	    var nextTab = currentTab.next('.step-wrapper');
	    currentTab.hide();
	    nextTab.show();
	});

	$('.prev-step').click(function(e) {
	    e.preventDefault();
	    var currentTab = $(this).closest('.step-wrapper');
	    var prevTab = currentTab.prev('.step-wrapper');
	    currentTab.hide();
	    prevTab.show();
	});

	$('.license-verification').submit(function(e){
		e.preventDefault();

		var additionalData = {
			'action': 'garudatheme_license_verification'
		};

		var formData = $(this).serialize() + '&' + $.param(additionalData);

		$.ajax({
			type: 'POST',
			url: about_theme.ajax_url,
			dataType: 'json',
			data: formData,
			beforeSend: function(){
				$('.license-verification > input[type="text"]').attr('disabled', 'disabled');
				$('.license-verification > button[type="submit"]').attr('disabled', 'disabled');

				$('.license-notice').removeClass('error success');
				$('.license-notice').hide();
			},
			success: function(response){
				if(response.status === true){
					var incurentLicense = $('.license-verification > input[type="text"]').val();
					$('.license-verification > input[type="text"]').val(incurentLicense.slice(0, -12)+'xxx');

					$('.license-notice').addClass('success');
					$('.license-notice').after('<div class="step-navigation"><div class="next-step"><span>'+about_theme.str_next+'</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path></svg></div></div>');
				}else{
					$('.license-verification > input[type="text"]').removeAttr('disabled');
					$('.license-verification > button[type="submit"]').removeAttr('disabled');
					
					$('.license-notice').addClass('error');
				}

				$('.license-notice > .message').text(response.message);
			},
			complete: function(){
				$('.next-step').click(function(e) {
				    e.preventDefault();
				    var currentTab = $(this).closest('.step-wrapper');
				    var nextTab = currentTab.next('.step-wrapper');
				    currentTab.hide();
				    nextTab.show();
				});

				$('.license-notice').show();
			},
			error: function(xhr, status, error){
				console.error(xhr.responseText);
			}
		});
	});

	$('.import-action').submit(function(e){
		e.preventDefault();

		var additionalData = {
			'action': 'garudatheme_import_demo'
		};

		var formData = $(this).serialize() + '&' + $.param(additionalData);

		$.ajax({
			type: 'POST',
			url: about_theme.ajax_url,
			dataType: 'json',
			data: formData,
			beforeSend: function(){
				$('.import-action > button[type="submit"]').addClass('loading').attr('disabled', 'disabled');
			},
			success: function(response){
				if(response.status === true){
					console.log('berhasil '+response.message);
				}else{
					console.log('gagal '+response.message);
				}
			},
			complete: function(){
				
			},
			error: function(xhr, status, error){
				console.error(xhr.responseText);
			}
		});
	});
});