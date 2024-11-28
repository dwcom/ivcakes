<section class="breadcrumb">
	<div class="breadcrumb__wrap">
		<div class="container">
			<div class="breadcrumb__block">
				<?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb( '<div class="breadcrumb__breadcrumb">','</div>' );} ?>
				<h1 class="breadcrumb__title fs64 weight-700 color-beige"><?php the_title() ?></h1>
			</div>
		</div>
	</div>
</section>