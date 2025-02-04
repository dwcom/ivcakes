<?php get_header() ?>

<?php include get_template_directory() . "/php/include/blocks/breadcrumb.php"; ?>

<section class="page cart">
	<div class="page__wrap">
		<div class="container">
			<div class="page__block fs18 editor">
				
				<?php the_content() ?>
			
			</div>
		</div>
	</div>
</section>

<?php include get_template_directory() . "/php/include/blocks/feedback.php"; ?>
<?php include get_template_directory() . "/php/include/blocks/image.php"; ?>
<?php include get_template_directory() . "/php/include/blocks/benefit.php"; ?>
<?php include get_template_directory() . "/php/include/blocks/contact.php"; ?>

<?php get_footer() ?>

