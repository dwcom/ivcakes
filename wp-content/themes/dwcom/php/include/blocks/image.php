<?php
	$footer_image = get_field('footer_image', 'option');
?>

<!-- Footer = Empty Custom Image -->
<section class="image">
	<div class="image__item">
		<img src="<?php echo $footer_image ?>" alt="<?php the_title() ?>" />
	</div>
</section>