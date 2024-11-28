<?php
	$current_category = get_queried_object();
	$category_id = $current_category->term_id;
	$category_name = $current_category->name;
	$thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);
	$image_url = wp_get_attachment_image_url($thumbnail_id, 'product-840-640');
?>
<section class="info">
	<div class="info__wrap mb128">
		<div class="info__block">
			<div class="info__content">
				<div class="info__bg">
					<div class="info__bg--image">
						<img src="/wp-content/uploads/2024/10/cakes.svg" alt="" />
					</div>
					<div class="info__bg--line"></div>
				</div>
				<div class="info__group info__group--full">
					<div class="info__left">
						<div class="info__image info__image--normal">
							<img src="<?php echo $image_url ?>" alt="<?php echo $category_name ?>" loading="lazy" />
						</div>
					</div>
					<div class="info__right">
						<?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb( '<div class="info__breadcrumb">','</div>' );} ?>
						<h1 class="info__title fs64 weight-700 color-beige info__title--840"><?php echo $category_name ?></h1>
						<div class="info__subtitle fs18 mb64 info__subtitle--680"><?php the_field('subtitle', 'product_cat_' . $category_id) ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="catalog">
	<div class="catalog__wrap mb128">
		<div class="container">
			<div class="catalog__block">
				<div class="catalog__group catalog__group">
					<div class="grid">
						<div class="catalog__list">
							<?php
								while (have_posts()): the_post();
									include get_template_directory() . "/php/include/parts/product.php";
								endwhile;
								wp_reset_postdata()
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php include get_template_directory() . "/php/include/blocks/feedback.php"; ?>
<?php include get_template_directory() . "/php/include/blocks/image.php"; ?>
<?php include get_template_directory() . "/php/include/blocks/benefit.php"; ?>
<?php include get_template_directory() . "/php/include/blocks/contact.php"; ?>