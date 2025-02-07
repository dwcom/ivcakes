<?php
	$list = get_field('faq_list','option');
?>
<section class="faq">
	<div class="faq__wrap mb128">
		<div class="container">
			<div class="faq__block">
				
				<div class="special__title align-center color-green fs48 weight-700 mb64">Ответы на популярные вопросы</div>
				
				<div class="faq__list">
					<?php foreach ($list as $item): ?>
						<div class="faq__item">
							<div class="faq__question fs24 weight-600 color-green"><?php echo $item['q']; ?><span class="faq__toggle">+</span></div>
							<div class="faq__answer fs18 editor"><?php echo $item['a']; ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			
			</div>
		</div>
	</div>
	
	<script>
		jQuery(document).ready(function ($) {
			$('.faq__question').on('click', function () {
				let $item = $(this).closest('.faq__item');
				$item.toggleClass('active').find('.faq__answer').slideToggle();
				$item.siblings('.faq__item').removeClass('active').find('.faq__answer').slideUp();
			});
		});
	</script>
</section>