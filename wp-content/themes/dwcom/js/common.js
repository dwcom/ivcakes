$(document).ready(() => {
	
	console.log('=== Скрипты успешно загружены ===')
	
	$(window).each(function () {
		$(this).on('scroll', function () {
			if ($(this).scrollTop() >= 50) {
				$('#header').addClass('scroll')
			} else {
				$('#header').removeClass('scroll')
			}
		})
	})
	
	$('#FrontPageSliderList').slick({
		infinite: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: false,
		dots: true,
		autoplay: true,
		autoplaySpeed: 3000
	});
	
	$('#FrontPageCatalogSlider').slick({
		infinite: true,
		slidesToShow: 3,
		slidesToScroll: 1,
		arrows: false,
		dots: true,
		autoplay: true,
		autoplaySpeed: 3000,
		responsive: [
			{
				breakpoint: 769,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 471,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	});
	
	$('#headerSearchBtn').on('click', function (event) {
		event.stopPropagation(); // Остановка всплытия события
		$('#headerSearchPopup').fadeIn();
		$('body').addClass('no-scroll');
	});
	
	$(document).on('click', function (event) {
		if (!$(event.target).closest('.aws-container').length && !$(event.target).is('#headerSearchBtn')) {
			$('#headerSearchPopup').fadeOut();
			$('body').removeClass('no-scroll');
		}
	});
	
	$('#mobileBar, #mobileClose').on('click', function () {
		$('#mobileMenu, #mobileBar').toggleClass('active');
	});
	
})
