<?php
	get_header();
	
	$slider = get_field('slider');
	$about_image = get_field('front_about_image');
	$about_title = get_field('front_about_title');
	$about_subtitle = get_field('front_about_subtitle');
	$about_about_btn_text = get_field('front_about_btn_text');
	$about_about_btn_link = get_field('front_about_btn_link');
	$special = get_field('front_special');
	$online_bg = get_field('front_online_bg');
	$online_btn_text = get_field('front_online_btn_text');
	$online_btn_link = get_field('front_online_btn_link');
	$recent_list = get_field('front_items');
	$month_title = get_field('front_month_title');
	$month_subtitle = get_field('front_month_subtitle');
	$month_id = get_field('front_month_id');
	?>
	
	<!--	Front page = Banner -->
	<section class="banner">
		<div class="banner__wrap">
			<div class="banner__block">
				<div class="banner__content">
					<div class="banner__slider slider">
						<div class="list" id="FrontPageSliderList">
							<?php foreach ( $slider as $item ) : ?>
								<div class="item">
									<div class="item__content" style="background: url(<?php echo $item['bg']['sizes']['slider-1920-1080'] ?>);">
										<div class="item__group align-center">
											<div class="item__title fs48 color-beige mb16"><?php echo $item['title'] ?></div>
											<a class="item__btn btn btn-beige-outline" href="<?php echo $item['btn_link'] ?>"><?php echo $item['btn_text'] ?></a>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<div class="dots"></div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<!--	Front page = Info -->
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
                    <div class="info__group">
                        <div class="info__left">
                            <div class="info__image">
                                <img src="<?php echo $about_image['sizes']['about-680-840'] ?>" alt="<?php echo $about_title ?>" loading="lazy" />
                            </div>
                        </div>
                        <div class="info__right">
                            <h1 class="info__title fs64 weight-700 color-beige"><?php echo $about_title ?></h1>
                            <div class="info__subtitle fs18 mb64"><?php echo $about_subtitle ?></div>
                            <div class="info__btn">
                                <a href="<?php echo $about_about_btn_link ?>" class="info__btn--item btn btn-green-outline"><?php echo $about_about_btn_text ?></a>
                                <div class="info__btn--line"></div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</section>

    <!--	Front page = Special -->
    <section class="special">
	    <?php
		    $query = new WP_Query;
		    $post = $query->query([
			    'post_type' => 'product',
			    'posts_per_page' => -1,
			    'post__in' => $special,
			    'orderby' => 'post__in',
			    'order' => 'DESC',
		    ]);
	    ?>
        <div class="special__wrap mb128">
            <div class="container">
                <div class="special__block">
                    <div class="special__title align-center color-green fs48 weight-700 mb64">Специальные предложения</div>
                    <div class="special__group catalog__group">
                        <div class="slider">
                            <div class="catalog__list" id="FrontPageCatalogSlider">
	                            <?php
		                            while ($query->have_posts()): $query->the_post();
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

    <!--	Front page = Online -->
    <section class="online">
        <div class="online__wrap mb128" style="background: url(<?php echo $online_bg ?>)">
            <div class="online__block">
                <div class="online__border">
                    <a href="<?php echo $online_btn_link ?>" class="online__btn btn btn-beige"><?php echo $online_btn_text ?></a>
                </div>
            </div>
        </div>
    </section>

    <!--	Front page = Recent Items List -->
    <section class="special">
		<?php
			$query = new WP_Query;
			$post = $query->query([
				'post_type' => 'product',
				'posts_per_page' => -1,
				'post__in' => $recent_list,
				'orderby' => 'post__in',
				'order' => 'DESC',
			]);
		?>
        <div class="special__wrap mb128">
            <div class="container">
                <div class="special__block">
                    <div class="special__group catalog__group">
                        <div class="grid">
                            <div class="catalog__list">
								<?php
									while ($query->have_posts()): $query->the_post();
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

    <!--	Front page = Month -->
    <section class="month">
        <div class="month__wrap">
            <div class="month__bg">
                <div class="container">
                    <div class="month__block">
                        <div class="month__content">
                            <div class="month__left">
                                <div class="month__title fs64 weight-500 color-green mb32"><?php echo $month_title ?></div>
                                <div class="month__subtitle mb32 fs18"><?php echo $month_subtitle ?></div>
                                <a class="month__btn btn btn-green-outline" href="<?php echo get_permalink($month_id) ?>">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.70117 3.92308C8.95904 3.37846 9.51437 3 10.1581 3H13.8419C14.4847 3 15.04 3.37846 15.2988 3.92308C15.9278 3.92862 16.4187 3.95723 16.8571 4.12892C17.3804 4.3341 17.8355 4.68278 18.1703 5.13508C18.5083 5.59015 18.6667 6.17538 18.885 6.97938L18.9191 7.10492L19.4624 9.10523C19.8251 9.28793 20.1427 9.54897 20.3926 9.86954C20.9654 10.6052 21.0667 11.4812 20.9654 12.4855C20.866 13.4603 20.5602 14.688 20.1771 16.2249L20.1522 16.3218C19.91 17.2938 19.7129 18.0831 19.4799 18.6988C19.235 19.3412 18.9255 19.8674 18.4144 20.2671C17.9042 20.6668 17.3194 20.8394 16.6388 20.9225C15.9859 21 15.1745 21 14.1753 21H9.82473C8.8255 21 8.01323 21 7.3612 20.9215C6.6797 20.8403 6.09582 20.6668 5.58469 20.2662C5.07449 19.8674 4.76505 19.3412 4.52008 18.6988C4.28616 18.0831 4.08999 17.2938 3.84779 16.3218L3.82292 16.2249C3.43981 14.688 3.13313 13.4603 3.03459 12.4865C2.93329 11.4803 3.03459 10.6052 3.6065 9.86954C3.86713 9.53539 4.17933 9.288 4.53665 9.10523L5.08001 7.10492L5.11501 6.97938C5.33327 6.17538 5.49168 5.59015 5.82966 5.13415C6.16464 4.6822 6.61974 4.33384 7.14293 4.12892C7.5813 3.95723 8.07125 3.92769 8.70117 3.92308ZM8.70209 5.31046C8.09243 5.31692 7.84838 5.34 7.64577 5.41938C7.36393 5.52986 7.11881 5.71764 6.93848 5.96123C6.7764 6.18 6.68062 6.48554 6.41354 7.47046L6.08753 8.66677C7.04347 8.53846 8.27754 8.53846 9.80999 8.53846H14.1891C15.7225 8.53846 16.9565 8.53846 17.9116 8.66677L17.5865 7.46954C17.3185 6.48462 17.2236 6.17908 17.0615 5.96031C16.8812 5.71672 16.6361 5.52894 16.3542 5.41846C16.1516 5.33908 15.9067 5.316 15.297 5.30954C15.1661 5.58522 14.9599 5.81809 14.7024 5.98113C14.4449 6.14418 14.1465 6.23074 13.8419 6.23077H10.1581C9.8535 6.23074 9.55514 6.14418 9.29761 5.98113C9.04007 5.81809 8.83391 5.58522 8.70302 5.30954M10.1581 4.38462C10.097 4.38462 10.0385 4.40893 9.99531 4.45221C9.95213 4.49548 9.92787 4.55418 9.92787 4.61538C9.92787 4.67659 9.95213 4.73529 9.99531 4.77856C10.0385 4.82184 10.097 4.84615 10.1581 4.84615H13.8419C13.903 4.84615 13.9615 4.82184 14.0047 4.77856C14.0479 4.73529 14.0721 4.67659 14.0721 4.61538C14.0721 4.55418 14.0479 4.49548 14.0047 4.45221C13.9615 4.40893 13.903 4.38462 13.8419 4.38462H10.1581ZM6.19804 10.0486C5.35998 10.1705 4.95384 10.392 4.6969 10.7225C4.43903 11.052 4.32392 11.4997 4.40956 12.3452C4.49705 13.2092 4.77794 14.3391 5.17763 15.9452C5.43366 16.968 5.61048 17.6769 5.81309 18.2068C6.00648 18.7182 6.19712 18.9886 6.43657 19.176C6.67509 19.3625 6.98269 19.4815 7.52605 19.5471C8.08782 19.6145 8.81537 19.6154 9.86893 19.6154H14.1329C15.1856 19.6154 15.9149 19.6145 16.4758 19.5471C17.0192 19.4825 17.3268 19.3625 17.5653 19.176C17.8047 18.9886 17.9944 18.7182 18.1897 18.2068C18.3905 17.6769 18.5682 16.968 18.8233 15.9452C19.2239 14.3391 19.5048 13.2092 19.5914 12.3452C19.6779 11.4997 19.5619 11.0511 19.305 10.7215C19.048 10.392 18.6419 10.1705 17.8029 10.0486C16.9464 9.92492 15.7842 9.92308 14.1329 9.92308H9.86893C8.21768 9.92308 7.05544 9.92492 6.19896 10.0486" fill="#567E72"/>
                                    </svg>
                                    Добавить в корзину
                                </span>
                                </a>
                            </div>
                            <div class="month__right">
                                <div class="month__image">
                                    <img src="<?php echo get_the_post_thumbnail_url( $month_id, 'product-840-840' ) ?>" alt="<?php the_title($month_id) ?>" />
                                </div>
                                <div class="month__label">
                                    <div class="title fs32 weight-500"><?php echo get_the_title($month_id) ?></div>
                                    <div class="price montserrat">
                                        <div class="old weight-500 fs24"><?php echo wc_price(wc_get_product( $month_id )->get_sale_price()) ?></div>
                                        <div class="new weight-600 fs32"><?php echo wc_price(wc_get_product( $month_id )->get_regular_price()) ?></div>
                                    </div>
                                </div>
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
    
<?php get_footer(); ?>