<?php
	global $product;
	$main_image_id = $product->get_image_id();
	$main_image_url = wp_get_attachment_image_url($main_image_id, 'product-840-auto');
	$gallery_image_ids = $product->get_gallery_image_ids();
	$gallery_images = [];
	foreach ($gallery_image_ids as $image_id) {
		$thumbnail_url = wp_get_attachment_image_url($image_id, 'product-256-388');
		$full_image_url = wp_get_attachment_image_url($image_id, 'product-840-auto');
		$gallery_images[] = [
			'thumbnail' => $thumbnail_url,
			'full' => $full_image_url
		];
	}
	$product_weight = $product->get_weight();
	$regular_price = $product->get_regular_price();
	$sale_price = $product->get_sale_price();
	$price_html = $product->get_price_html();
	
	// Получаем атрибуты и вариации товара
	if ( $product->is_type( 'variable' ) ) {
		$default_attributes = $product->get_default_attributes(); // Атрибуты по умолчанию
		$available_variations = $product->get_available_variations();
  
		// Ищем вариацию по умолчанию
		$default_variation = null;
		foreach ($available_variations as $variation) {
			$is_default_variation = true;
			foreach ($default_attributes as $key => $value) {
				if (!isset($variation['attributes']['attribute_' . $key]) || $variation['attributes']['attribute_' . $key] !== $value) {
					$is_default_variation = false;
					break;
				}
			}
			if ($is_default_variation) {
				$default_variation = $variation;
				break;
			}
		}
		// Цена вариации по умолчанию
		$default_price_html = $default_variation ? $default_variation['display_price'] : $product->get_price();
	}
    $compound = get_field('compound');
    $storage = get_field('storage') ? get_field('storage') : get_field('storage','option');
    $delivery = get_field('delivery') ? get_field('delivery') : get_field('delivery','option');
    $video = get_field('video');
    $video_toggle = $video ? 'video' : 'classic';
?>

<!--	Product page = Main -->
<section class="product info">
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
						<div class="info__image product__gallery productGallery">
                            <a class="info__main <?php echo $video_toggle ?>" href="<?php echo $main_image_url ?>">
                                <img src="<?php echo $main_image_url ?>" alt="<?php the_title() ?>" />
                                
	                            <?php if ($video) { ?>
                                    <video class="info__video" autoplay muted loop preload="auto">
                                        <source src="<?php echo $video ?>">
                                    </video>
	                            <?php } ?>
                                
                            </a>
                            <div class="gallery">
                                <?php $i = 1; foreach ( $gallery_images as $image ) { ?>
                                    <a href="<?php echo $image['full'] ?>">
                                        <img src="<?php echo $image['thumbnail'] ?>" alt="<?php the_title() ?> - Изображение <?php echo $i ?>">
                                    </a>
                                <?php $i++; } ?>
                            </div>
						</div>
					</div>
					<div class="info__right">
						<?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb( '<div class="info__breadcrumb">','</div>' );} ?>
						<h1 class="info__title fs64 weight-700 color-beige info__title--840"><?php the_title() ?></h1>
						<div class="info__subtitle fs18 mb64 info__subtitle--680 product__subtitle"><?php the_content() ?></div>
                        
                        <?php if ($product_weight ) { ?>
                            <div class="product__weight fs24 weight-600">Вес: <span class="montserrat"><?php echo $product_weight ?></span> кг</div>
                        <?php } ?>
                        
						<?php if ( $product->is_type( 'variable' ) ) : ?>
                            <div class="info__variables variable-product-options"
                                 data-variations='<?php echo json_encode( $product->get_available_variations(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?>'
                                 data-currency-symbol="<?php echo get_woocommerce_currency_symbol(); ?>">

                            <?php
									$attributes = $product->get_variation_attributes();
									$default_attributes = $product->get_default_attributes();
									
									foreach ( $attributes as $attribute_name => $options ) : ?>
                                        <div class="info__variable">
                                            <label class="fs18 weight-600" for="<?php echo esc_attr( $attribute_name ); ?>">
												<?php echo wc_attribute_label( $attribute_name ); ?>
                                            </label>
                                            <select id="<?php echo esc_attr( $attribute_name ); ?>"
                                                    class="variable-attribute fs20"
                                                    data-attribute_name="<?php echo esc_attr( $attribute_name ); ?>">
                                                <option value="">Выберите</option>
												<?php foreach ( $options as $option ) :
													$term = get_term_by( 'slug', $option, $attribute_name );
													$is_default = isset($default_attributes[$attribute_name]) && $default_attributes[$attribute_name] === $option; ?>
                                                    <option value="<?php echo esc_attr( $option ); ?>" <?php selected( $is_default ); ?>>
														<?php echo esc_html( $term ? $term->name : $option ); ?>
                                                    </option>
												<?php endforeach; ?>
                                            </select>
                                        </div>
									<?php endforeach; ?>
                            </div>
						<?php endif; ?>
                        
                        <div class="product__action">
                            <div class="product__price montserrat color-green">
	                            <?php if ( $product->is_type( 'variable' ) ) { ?>
                                    <div class="regular fs36 weight-700" id="product-price"><?php echo wc_price($default_price_html); ?></div>
                                <?php } else { ?>
		                            <?php if ($sale_price) : ?>
                                        <div class="new fs36 weight-700"><?php echo wc_price($sale_price); ?></div>
                                        <div class="old fs18 weight-500"><?php echo wc_price($regular_price); ?></div>
		                            <?php else : ?>
                                        <div class="regular  fs36 weight-700"><?php echo wc_price($regular_price); ?></div>
		                            <?php endif; ?>
                                <?php } ?>
                            </div>
                            <div class="product__purchase">
                                <input class="montserrat" type="number" id="productQuantity" value="1" min="1" />
	                            <?php if ( $product->is_type( 'variable' ) ) { ?>
                                    <button
                                        type="button"
                                        class="btn btn-green-outline"
                                        id="addVariableToCartButton"
                                        data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
                                        data-product-name="<?php the_title() ?>">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none">
                                          <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.70117 3.92308C8.95904 3.37846 9.51437 3 10.1581 3H13.8419C14.4847 3 15.04 3.37846 15.2988 3.92308C15.9278 3.92862 16.4187 3.95723 16.8571 4.12892C17.3804 4.3341 17.8355 4.68278 18.1703 5.13508C18.5083 5.59015 18.6667 6.17538 18.885 6.97938L18.9191 7.10492L19.4624 9.10523C19.8251 9.28793 20.1427 9.54897 20.3926 9.86954C20.9654 10.6052 21.0667 11.4812 20.9654 12.4855C20.866 13.4603 20.5602 14.688 20.1771 16.2249L20.1522 16.3218C19.91 17.2938 19.7129 18.0831 19.4799 18.6988C19.235 19.3412 18.9255 19.8674 18.4144 20.2671C17.9042 20.6668 17.3194 20.8394 16.6388 20.9225C15.9859 21 15.1745 21 14.1753 21H9.82473C8.8255 21 8.01323 21 7.3612 20.9215C6.6797 20.8403 6.09582 20.6668 5.58469 20.2662C5.07449 19.8674 4.76505 19.3412 4.52008 18.6988C4.28616 18.0831 4.08999 17.2938 3.84779 16.3218L3.82292 16.2249C3.43981 14.688 3.13313 13.4603 3.03459 12.4865C2.93329 11.4803 3.03459 10.6052 3.6065 9.86954C3.86713 9.53539 4.17933 9.288 4.53665 9.10523L5.08001 7.10492L5.11501 6.97938C5.33327 6.17538 5.49168 5.59015 5.82966 5.13415C6.16464 4.6822 6.61974 4.33384 7.14293 4.12892C7.5813 3.95723 8.07125 3.92769 8.70117 3.92308ZM8.70209 5.31046C8.09243 5.31692 7.84838 5.34 7.64577 5.41938C7.36393 5.52986 7.11881 5.71764 6.93848 5.96123C6.7764 6.18 6.68062 6.48554 6.41354 7.47046L6.08753 8.66677C7.04347 8.53846 8.27754 8.53846 9.80999 8.53846H14.1891C15.7225 8.53846 16.9565 8.53846 17.9116 8.66677L17.5865 7.46954C17.3185 6.48462 17.2236 6.17908 17.0615 5.96031C16.8812 5.71672 16.6361 5.52894 16.3542 5.41846C16.1516 5.33908 15.9067 5.316 15.297 5.30954C15.1661 5.58522 14.9599 5.81809 14.7024 5.98113C14.4449 6.14418 14.1465 6.23074 13.8419 6.23077H10.1581C9.8535 6.23074 9.55514 6.14418 9.29761 5.98113C9.04007 5.81809 8.83391 5.58522 8.70302 5.30954M10.1581 4.38462C10.097 4.38462 10.0385 4.40893 9.99531 4.45221C9.95213 4.49548 9.92787 4.55418 9.92787 4.61538C9.92787 4.67659 9.95213 4.73529 9.99531 4.77856C10.0385 4.82184 10.097 4.84615 10.1581 4.84615H13.8419C13.903 4.84615 13.9615 4.82184 14.0047 4.77856C14.0479 4.73529 14.0721 4.67659 14.0721 4.61538C14.0721 4.55418 14.0479 4.49548 14.0047 4.45221C13.9615 4.40893 13.903 4.38462 13.8419 4.38462H10.1581ZM6.19804 10.0486C5.35998 10.1705 4.95384 10.392 4.6969 10.7225C4.43903 11.052 4.32392 11.4997 4.40956 12.3452C4.49705 13.2092 4.77794 14.3391 5.17763 15.9452C5.43366 16.968 5.61048 17.6769 5.81309 18.2068C6.00648 18.7182 6.19712 18.9886 6.43657 19.176C6.67509 19.3625 6.98269 19.4815 7.52605 19.5471C8.08782 19.6145 8.81537 19.6154 9.86893 19.6154H14.1329C15.1856 19.6154 15.9149 19.6145 16.4758 19.5471C17.0192 19.4825 17.3268 19.3625 17.5653 19.176C17.8047 18.9886 17.9944 18.7182 18.1897 18.2068C18.3905 17.6769 18.5682 16.968 18.8233 15.9452C19.2239 14.3391 19.5048 13.2092 19.5914 12.3452C19.6779 11.4997 19.5619 11.0511 19.305 10.7215C19.048 10.392 18.6419 10.1705 17.8029 10.0486C16.9464 9.92492 15.7842 9.92308 14.1329 9.92308H9.86893C8.21768 9.92308 7.05544 9.92492 6.19896 10.0486"
                                                fill="#567E72"/>
                                        </svg>
                                        Добавить в корзину
                                    </span>
                                </button>
                                <?php } else { ?>
                                    <button
                                        type="button"
                                        class="btn btn-green-outline"
                                        id="addToCartButton"
                                        data-product-name="<?php the_title() ?>"
                                        data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none">
                                          <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.70117 3.92308C8.95904 3.37846 9.51437 3 10.1581 3H13.8419C14.4847 3 15.04 3.37846 15.2988 3.92308C15.9278 3.92862 16.4187 3.95723 16.8571 4.12892C17.3804 4.3341 17.8355 4.68278 18.1703 5.13508C18.5083 5.59015 18.6667 6.17538 18.885 6.97938L18.9191 7.10492L19.4624 9.10523C19.8251 9.28793 20.1427 9.54897 20.3926 9.86954C20.9654 10.6052 21.0667 11.4812 20.9654 12.4855C20.866 13.4603 20.5602 14.688 20.1771 16.2249L20.1522 16.3218C19.91 17.2938 19.7129 18.0831 19.4799 18.6988C19.235 19.3412 18.9255 19.8674 18.4144 20.2671C17.9042 20.6668 17.3194 20.8394 16.6388 20.9225C15.9859 21 15.1745 21 14.1753 21H9.82473C8.8255 21 8.01323 21 7.3612 20.9215C6.6797 20.8403 6.09582 20.6668 5.58469 20.2662C5.07449 19.8674 4.76505 19.3412 4.52008 18.6988C4.28616 18.0831 4.08999 17.2938 3.84779 16.3218L3.82292 16.2249C3.43981 14.688 3.13313 13.4603 3.03459 12.4865C2.93329 11.4803 3.03459 10.6052 3.6065 9.86954C3.86713 9.53539 4.17933 9.288 4.53665 9.10523L5.08001 7.10492L5.11501 6.97938C5.33327 6.17538 5.49168 5.59015 5.82966 5.13415C6.16464 4.6822 6.61974 4.33384 7.14293 4.12892C7.5813 3.95723 8.07125 3.92769 8.70117 3.92308ZM8.70209 5.31046C8.09243 5.31692 7.84838 5.34 7.64577 5.41938C7.36393 5.52986 7.11881 5.71764 6.93848 5.96123C6.7764 6.18 6.68062 6.48554 6.41354 7.47046L6.08753 8.66677C7.04347 8.53846 8.27754 8.53846 9.80999 8.53846H14.1891C15.7225 8.53846 16.9565 8.53846 17.9116 8.66677L17.5865 7.46954C17.3185 6.48462 17.2236 6.17908 17.0615 5.96031C16.8812 5.71672 16.6361 5.52894 16.3542 5.41846C16.1516 5.33908 15.9067 5.316 15.297 5.30954C15.1661 5.58522 14.9599 5.81809 14.7024 5.98113C14.4449 6.14418 14.1465 6.23074 13.8419 6.23077H10.1581C9.8535 6.23074 9.55514 6.14418 9.29761 5.98113C9.04007 5.81809 8.83391 5.58522 8.70302 5.30954M10.1581 4.38462C10.097 4.38462 10.0385 4.40893 9.99531 4.45221C9.95213 4.49548 9.92787 4.55418 9.92787 4.61538C9.92787 4.67659 9.95213 4.73529 9.99531 4.77856C10.0385 4.82184 10.097 4.84615 10.1581 4.84615H13.8419C13.903 4.84615 13.9615 4.82184 14.0047 4.77856C14.0479 4.73529 14.0721 4.67659 14.0721 4.61538C14.0721 4.55418 14.0479 4.49548 14.0047 4.45221C13.9615 4.40893 13.903 4.38462 13.8419 4.38462H10.1581ZM6.19804 10.0486C5.35998 10.1705 4.95384 10.392 4.6969 10.7225C4.43903 11.052 4.32392 11.4997 4.40956 12.3452C4.49705 13.2092 4.77794 14.3391 5.17763 15.9452C5.43366 16.968 5.61048 17.6769 5.81309 18.2068C6.00648 18.7182 6.19712 18.9886 6.43657 19.176C6.67509 19.3625 6.98269 19.4815 7.52605 19.5471C8.08782 19.6145 8.81537 19.6154 9.86893 19.6154H14.1329C15.1856 19.6154 15.9149 19.6145 16.4758 19.5471C17.0192 19.4825 17.3268 19.3625 17.5653 19.176C17.8047 18.9886 17.9944 18.7182 18.1897 18.2068C18.3905 17.6769 18.5682 16.968 18.8233 15.9452C19.2239 14.3391 19.5048 13.2092 19.5914 12.3452C19.6779 11.4997 19.5619 11.0511 19.305 10.7215C19.048 10.392 18.6419 10.1705 17.8029 10.0486C16.9464 9.92492 15.7842 9.92308 14.1329 9.92308H9.86893C8.21768 9.92308 7.05544 9.92492 6.19896 10.0486"
                                                fill="#567E72"/>
                                        </svg>
                                        Добавить в корзину
                                    </span>
                                    </button>
                                <?php } ?>
                                
                            </div>
                            <div class="product__or">
                                <span class="fs20 color-green">или</span>
                            </div>
                            <div class="product__social">
                                <span class="fs14 color-green weight-500">Заказать через соц.сеть</span>
                                <div class="list">
                                    <a href="<?php the_field('wa','option') ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="37" height="36" viewBox="0 0 37 36" fill="none">
                                            <g clip-path="url(#clip0_82_67289)">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.9375 3.375C10.1708 3.375 3.875 9.67078 3.875 17.4375C3.875 20.0953 4.61328 22.5844 5.89719 24.705L4.64281 28.9687C4.57079 29.2135 4.56605 29.4732 4.62909 29.7205C4.69214 29.9678 4.82063 30.1935 5.00107 30.3739C5.18151 30.5544 5.40722 30.6829 5.65449 30.7459C5.90176 30.8089 6.16145 30.8042 6.40625 30.7322L10.67 29.4778C12.862 30.8038 15.3757 31.5033 17.9375 31.5C25.7042 31.5 32 25.2042 32 17.4375C32 9.67078 25.7042 3.375 17.9375 3.375ZM14.7566 20.6198C17.6014 23.4633 20.3169 23.8387 21.2759 23.8739C22.7342 23.9273 24.1545 22.8136 24.7072 21.5212C24.7764 21.3603 24.8014 21.1839 24.7796 21.0101C24.7579 20.8364 24.6901 20.6715 24.5834 20.5326C23.8128 19.5483 22.7708 18.8409 21.7527 18.1378C21.5402 17.9905 21.2789 17.9313 21.0237 17.9728C20.7685 18.0142 20.5393 18.153 20.3844 18.36L19.5406 19.6467C19.496 19.7156 19.4269 19.765 19.3473 19.7848C19.2676 19.8047 19.1834 19.7935 19.1117 19.7536C18.5394 19.4259 17.7055 18.8691 17.1064 18.27C16.5073 17.6709 15.9842 16.875 15.6903 16.3392C15.6548 16.2709 15.6447 16.1922 15.662 16.1172C15.6792 16.0422 15.7227 15.9758 15.7845 15.93L17.0839 14.9653C17.2699 14.8044 17.3899 14.5806 17.4211 14.3366C17.4522 14.0927 17.3922 13.8459 17.2527 13.6434C16.6227 12.7209 15.8886 11.5481 14.8241 10.7705C14.6864 10.6715 14.5255 10.6099 14.357 10.5914C14.1885 10.5729 14.018 10.5983 13.8622 10.665C12.5684 11.2191 11.4491 12.6394 11.5025 14.1005C11.5377 15.0595 11.9131 17.775 14.7566 20.6198Z" fill="#567E72"/>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_82_67289">
                                                    <rect width="36" height="36" fill="white" transform="translate(0.5)"/>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </a>
                                    <a href="<?php the_field('tg','option') ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="37" height="36" viewBox="0 0 37 36" fill="none">
                                            <path d="M17.9375 3.375C10.175 3.375 3.875 9.675 3.875 17.4375C3.875 25.2 10.175 31.5 17.9375 31.5C25.7 31.5 32 25.2 32 17.4375C32 9.675 25.7 3.375 17.9375 3.375ZM24.4625 12.9375C24.2516 15.1594 23.3375 20.5594 22.8734 23.0484C22.6766 24.1031 22.2828 24.4547 21.9172 24.4969C21.1016 24.5672 20.4828 23.9625 19.6953 23.4422C18.4578 22.6266 17.7547 22.1203 16.5594 21.3328C15.1672 20.4188 16.0672 19.9125 16.8687 19.0969C17.0797 18.8859 20.6797 15.6094 20.75 15.3141C20.7598 15.2693 20.7585 15.2229 20.7462 15.1788C20.734 15.1347 20.7111 15.0942 20.6797 15.0609C20.5953 14.9906 20.4828 15.0188 20.3844 15.0328C20.2578 15.0609 18.2891 16.3687 14.45 18.9563C13.8875 19.3359 13.3812 19.5328 12.9312 19.5187C12.425 19.5047 11.4688 19.2375 10.7516 18.9984C9.86562 18.7172 9.17656 18.5625 9.23281 18.0703C9.26094 17.8172 9.6125 17.5641 10.2734 17.2969C14.3797 15.5109 17.1078 14.3297 18.4719 13.7672C22.3813 12.1359 23.1828 11.8547 23.7172 11.8547C23.8297 11.8547 24.0969 11.8828 24.2656 12.0234C24.4062 12.1359 24.4484 12.2906 24.4625 12.4031C24.4484 12.4875 24.4766 12.7406 24.4625 12.9375Z" fill="#567E72"/>
                                        </svg>
                                    </a>
                                    <a href="<?php the_field('in','option') ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="37" height="36" viewBox="0 0 37 36" fill="none">
                                            <path d="M19.3831 3.375C20.9651 3.37922 21.768 3.38766 22.4613 3.40734L22.7341 3.41719C23.0491 3.42843 23.3598 3.4425 23.7353 3.45937C25.2314 3.52968 26.2523 3.76592 27.1481 4.11324C28.0761 4.47041 28.858 4.95414 29.6398 5.73457C30.3551 6.43728 30.9084 7.28765 31.2611 8.22632C31.6085 9.12205 31.8447 10.1429 31.915 11.6405C31.9319 12.0146 31.9459 12.3253 31.9572 12.6417L31.9656 12.9145C31.9867 13.6064 31.9952 14.4093 31.998 15.9912L31.9994 17.0402V18.8823C32.0028 19.908 31.992 20.9337 31.967 21.9591L31.9586 22.2319C31.9474 22.5483 31.9333 22.859 31.9164 23.2331C31.8461 24.7306 31.6071 25.7501 31.2611 26.6473C30.9084 27.5859 30.3551 28.4363 29.6398 29.139C28.9371 29.8543 28.0867 30.4076 27.1481 30.7603C26.2523 31.1077 25.2314 31.3439 23.7353 31.4142L22.7341 31.4564L22.4613 31.4648C21.768 31.4845 20.9651 31.4944 19.3831 31.4972L18.3341 31.4986H16.4934C15.4673 31.5022 14.4412 31.4914 13.4153 31.4662L13.1425 31.4578C12.8087 31.4452 12.475 31.4306 12.1413 31.4142C10.6451 31.3439 9.62426 31.1077 8.72712 30.7603C7.78896 30.4075 6.93908 29.8542 6.23677 29.139C5.52095 28.4364 4.96715 27.5861 4.61404 26.6473C4.26672 25.7515 4.03048 24.7306 3.96017 23.2331L3.91799 22.2319L3.91095 21.9591C3.88503 20.9337 3.87331 19.908 3.8758 18.8823V15.9912C3.87191 14.9656 3.88222 13.9399 3.90674 12.9145L3.91658 12.6417C3.92783 12.3253 3.94189 12.0146 3.95876 11.6405C4.02907 10.1429 4.26531 9.12346 4.61264 8.22632C4.96656 7.28727 5.52134 6.43687 6.23818 5.73457C6.94008 5.0196 7.78946 4.4663 8.72712 4.11324C9.62426 3.76592 10.6437 3.52968 12.1413 3.45937C12.5154 3.4425 12.8275 3.42843 13.1425 3.41719L13.4153 3.40875C14.4407 3.38376 15.4664 3.37298 16.492 3.37641L19.3831 3.375ZM17.9376 10.4059C16.0729 10.4059 14.2845 11.1466 12.966 12.4652C11.6474 13.7837 10.9067 15.5721 10.9067 17.4368C10.9067 19.3015 11.6474 21.0898 12.966 22.4084C14.2845 23.7269 16.0729 24.4677 17.9376 24.4677C19.8023 24.4677 21.5906 23.7269 22.9092 22.4084C24.2277 21.0898 24.9685 19.3015 24.9685 17.4368C24.9685 15.5721 24.2277 13.7837 22.9092 12.4652C21.5906 11.1466 19.8023 10.4059 17.9376 10.4059ZM17.9376 13.2183C18.4916 13.2182 19.0402 13.3272 19.552 13.5391C20.0639 13.751 20.529 14.0617 20.9208 14.4533C21.3125 14.845 21.6234 15.31 21.8354 15.8218C22.0475 16.3336 22.1567 16.8821 22.1568 17.4361C22.1569 17.9901 22.0479 18.5387 21.836 19.0505C21.6241 19.5624 21.3134 20.0275 20.9217 20.4193C20.5301 20.811 20.0651 21.1219 19.5533 21.3339C19.0415 21.546 18.493 21.6552 17.939 21.6553C16.8202 21.6553 15.7472 21.2109 14.956 20.4197C14.1649 19.6286 13.7205 18.5556 13.7205 17.4368C13.7205 16.318 14.1649 15.245 14.956 14.4538C15.7472 13.6627 16.8202 13.2183 17.939 13.2183M25.3214 8.29663C24.8553 8.29663 24.4082 8.48181 24.0785 8.81145C23.7489 9.14109 23.5637 9.58817 23.5637 10.0544C23.5637 10.5205 23.7489 10.9676 24.0785 11.2972C24.4082 11.6269 24.8553 11.8121 25.3214 11.8121C25.7876 11.8121 26.2347 11.6269 26.5643 11.2972C26.894 10.9676 27.0792 10.5205 27.0792 10.0544C27.0792 9.58817 26.894 9.14109 26.5643 8.81145C26.2347 8.48181 25.7876 8.29663 25.3214 8.29663Z" fill="#567E72"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="product__tabs">
                            <div class="tabs fs24 color-green weight-500">
                                <?php if ( $compound ) { ?><div class="item active productTabItem" data-tab="compound">Состав</div><?php } ?>
                                <div class="item productTabItem <?php if ( !$compound ) { ?>active<?php } ?>" data-tab="storage">Хранение</div>
                                <div class="item productTabItem" data-tab="delivery">Доставка и оплата</div>
                            </div>
                            
                            <div class="blocks">
	                            <?php if ( $compound ) { ?>
                                    <div class="block active productTabBlock" id="compound">
                                        <div class="block__list">
                                            <ul class="fs18">
                                                <?php foreach ($compound as $item) { ?>
                                                    <li><?php echo $item['item'] ?></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
	                            <?php } ?>
                                <div class="block productTabBlock <?php if ( !$compound ) { ?>active<?php } ?>" id="storage">
                                    <div class="editor fs18">
                                        <?php echo $storage ?>
                                    </div>
                                </div>
                                <div class="block productTabBlock" id="delivery">
                                    <div class="editor fs18">
		                                <?php echo $delivery ?>
                                    </div>
                                </div>
                            </div>
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
			'posts_per_page' => 6,
			'orderby' => 'rand',
			'post__not_in' => [get_the_ID()]
		]);
	?>
    <div class="special__wrap mb128">
        <div class="container">
            <div class="special__block">
                <div class="special__title align-center color-green fs48 weight-700 mb64">Возможно вас заинтересует</div>
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

<script>
    jQuery(document).ready(function ($) {
        // Обработка кликов на вкладках товара
        $('.productTabItem').on('click', function () {
            // Убираем класс активности у всех вкладок и их содержимого
            $('.productTabBlock, .productTabItem').removeClass('active');
            
            let block = $(this).data('tab'); // Получаем идентификатор блока для отображения
            $('#' + block).addClass('active'); // Активируем соответствующий блок
            $(this).addClass('active'); // Активируем текущую вкладку
        });
		
		// Галерея по клику
	    $('.productGallery a').simpleLightbox();
     
	    // Обработка клика по кнопке добавления в корзину
        $('#addToCartButton').on('click', function () {
            let productId = $(this).data('product-id'); // Идентификатор товара
            let quantity = $('#productQuantity').val(); // Количество товара
            let productName = $(this).data('product-name'); // Название товара
            
            // Функция для отображения уведомления о добавлении товара
            function productAddNotion(productName) {
                let notification = $('<div>', {
                    class: 'item green', // Класс оформления уведомления
                    text: `"${productName}" добавлен в корзину`
                });
                
                $('#notionBlock').append(notification); // Добавляем уведомление в блок
                notification.fadeIn(300).delay(1500).fadeOut(300, function() {
                    $(this).remove(); // Удаляем уведомление после показа
                });
            }
            
            // AJAX-запрос для добавления товара в корзину
            $.ajax({
                url: woocommerce_params.ajax_url, // URL для обработки запроса
                type: 'POST',
                data: {
                    action: 'add_to_cart', // Действие WooCommerce
                    product_id: productId, // ID товара
                    quantity: quantity // Количество товара
                },
                success: function (response) {
                    if (response.success) {
                        $('#headerCardCount').text(response.data.cart_count); // Обновляем количество товаров в корзине
                        productAddNotion(productName); // Показ уведомления
                    } else {
                        console.log('Ошибка добавления товара в корзину:', response.data);
                    }
                },
                error: function () {
                    console.log('Произошла ошибка в AJAX-запросе.');
                }
            });
        });
		
		// Обработка вариативного товара по клику
	    $('#addVariableToCartButton').on('click', function () {
		    let productId = $(this).data('product-id');
		    let quantity = $('#productQuantity').val();
		    let productName = $(this).data('product-name'); // Название товара
		    let attributes = {};
		    
		    $('.variable-attribute').each(function () {
			    let attributeName = $(this).data('attribute_name');
			    let attributeValue = $(this).val();
			    if (attributeValue) {
				    attributes[attributeName] = attributeValue;
			    }
		    });
		    
		    if (Object.keys(attributes).length < $('.variable-attribute').length) {
			    alert('Пожалуйста, выберите все опции.');
			    return;
		    }
      
		    // Функция для отображения уведомления о добавлении товара
		    function productAddNotion(productName) {
			    let notification = $('<div>', {
				    class: 'item green', // Класс оформления уведомления
				    text: `"${productName}" добавлен в корзину`
			    });
			    
			    $('#notionBlock').append(notification); // Добавляем уведомление в блок
			    notification.fadeIn(300).delay(1500).fadeOut(300, function() {
				    $(this).remove(); // Удаляем уведомление после показа
			    });
		    }
		    
		    $.ajax({
			    url: woocommerce_params.ajax_url,
			    type: 'POST',
			    data: {
				    action: 'add_variable_to_cart',
				    product_id: productId,
				    quantity: quantity,
				    attributes: attributes
			    },
			    success: function (response) {
				    if (response.success) {
					    $('#headerCardCount').text(response.data.cart_count);
					    productAddNotion(productName);
				    } else {
					    alert('Ошибка: ' + response.data);
				    }
			    },
			    error: function (xhr, status, error) {
				    console.error('Ошибка AJAX:', status, error);
				    alert('Произошла ошибка. Попробуйте снова.');
			    }
		    });
	    });
	    
		// Обновление цен при смене вариаций
	    function updatePrice() {
		    const $productOptions = $('.variable-product-options');
		    const variations = $productOptions.data('variations');
		    const currencySymbol = $productOptions.data('currency-symbol'); // Символ валюты
		    
		    if (!Array.isArray(variations)) {
			    console.error('Variations не массив или undefined');
			    return;
		    }
		    
		    const selectedAttributes = {};
		    let allSelected = true;
		    
		    // Собираем выбранные значения
		    $('.variable-attribute').each(function () {
			    const attributeName = $(this).data('attribute_name');
			    const selectedValue = $(this).val();
			    
			    if (!selectedValue) {
				    allSelected = false;
			    } else {
				    selectedAttributes[`attribute_${attributeName}`] = selectedValue;
			    }
		    });
		    
		    if (allSelected) {
			    const matchedVariation = variations.find(function (variation) {
				    return Object.entries(selectedAttributes).every(function ([key, value]) {
					    return variation.attributes[key] === value;
				    });
			    });
			    
			    const $priceElement = $('#product-price');
			    if (matchedVariation) {
				    // Форматируем цену
				    const formattedPrice = parseFloat(matchedVariation.display_price).toFixed(2) + ' ' + currencySymbol;
				    $priceElement.text(formattedPrice);
			    } else {
				    $priceElement.text('Выбранная вариация не найдена');
			    }
		    }
	    }
	    
	    $('.variable-attribute').on('change', updatePrice);
	   
	    
    });
</script>

<style>
    .benefit__wrap {
        margin-bottom: 0;
    }
    .feedback__wrap {
        margin-bottom: 64px
    }
</style>

<?php include get_template_directory() . "/php/include/blocks/benefit.php"; ?>
<?php include get_template_directory() . "/php/include/blocks/feedback.php"; ?>
<?php include get_template_directory() . "/php/include/blocks/contact.php"; ?>