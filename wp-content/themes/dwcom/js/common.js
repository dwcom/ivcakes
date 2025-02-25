$(document).ready( function() {
	
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
	console.log('=== Скрипты успешно выполнены ===')
	
})

document.addEventListener("DOMContentLoaded", function () {
    let interval = setInterval(function () {
        let elements = document.querySelectorAll(".wc-block-components-shipping-rates-control__package__description--free");
        let allElementsLoaded = true;

        if (elements.length) {
            elements.forEach(el => el.textContent = 'Рассчитывается индивидуально');
        } else {
            allElementsLoaded = false;
        }
        
        // Проверяем наличие всех необходимых элементов
        const dateInput = document.getElementById("shipping-thwcfe-block-delivey_date");
        const timeFromInput = document.getElementById("shipping-thwcfe-block-delivery_time_from");
        const timeToInput = document.getElementById("shipping-thwcfe-block-delivery_time_to");
        
        if (dateInput) {
            dateInput.type = "date";
            const today = new Date();
            today.setDate(today.getDate() + 3);
            const minDate = today.toISOString().split("T")[0];
            dateInput.min = minDate;
            //dateInput.value = minDate; // Устанавливаем дату по умолчанию
        } else {
            allElementsLoaded = false;
        }
        
        if (timeFromInput) {
            timeFromInput.type = "time";
            //timeFromInput.value = "09:00"; // Устанавливаем время "с" по умолчанию
        } else {
            allElementsLoaded = false;
        }
        
        if (timeToInput) {
            timeToInput.type = "time";
            //timeToInput.value = "18:00"; // Устанавливаем время "до" по умолчанию
        } else {
            allElementsLoaded = false;
        }
        
        // Останавливаем проверку, если все элементы загружены и изменены
        if (allElementsLoaded) {
            clearInterval(interval);
        }
    }, 500);
});

