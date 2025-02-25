<?php
    $product = wc_get_product( get_the_ID() );
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $current_price = $product->get_price();
    $video = get_field('video');
	$video_toggle = 'classic';
    //$video_toggle = $video ? 'video' : 'classic';
?>
<div class="item <?php echo $video_toggle ?>">
	<a class="item__link" href="<?php echo get_permalink() ?>">
        <div class="item__image mb16">
            
            <div class="item__poster">
                <img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'product-540-810' ) ?>" alt="<?php the_title() ?>" />
            </div>
            
<!--             <?php if ($video) { ?>
                <video class="item__video" autoplay muted loop preload="auto">
                    <source src="<?php the_field('video') ?>">
                </video>
            <?php } ?>
             -->
	        <?php if ( $sale_price ) { ?>
                <div class="item__label fs18">Акция</div>
            <?php } ?>
            <div class="item__hover">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M23.2031 10.4615C23.8908 9.00923 25.3717 8 27.0883 8H36.9117C38.6259 8 40.1068 9.00923 40.7969 10.4615C42.4742 10.4763 43.7832 10.5526 44.9522 11.0105C46.3477 11.5576 47.5613 12.4874 48.4543 13.6935C49.3556 14.9071 49.778 16.4677 50.36 18.6117L50.4509 18.9465L51.8998 24.2806C52.8668 24.7678 53.714 25.4639 54.3802 26.3188C55.9078 28.2806 56.1779 30.6166 55.9078 33.2948C55.6426 35.8942 54.8272 39.168 53.8056 43.2665L53.7393 43.5249C53.0934 46.1169 52.5678 48.2215 51.9465 49.8634C51.2932 51.5766 50.4681 52.9797 49.1051 54.0455C47.7445 55.1114 46.185 55.5717 44.3702 55.7932C42.629 56 40.4654 56 37.8007 56H26.1993C23.5347 56 21.3686 56 19.6299 55.7908C17.8125 55.5742 16.2555 55.1114 14.8925 54.0431C13.532 52.9797 12.7068 51.5766 12.0535 49.8634C11.4298 48.2215 10.9067 46.1169 10.2608 43.5249L10.1945 43.2665C9.17282 39.168 8.35502 35.8942 8.09224 33.2972C7.8221 30.6142 8.09224 28.2806 9.61733 26.3188C10.3123 25.4277 11.1449 24.768 12.0977 24.2806L13.5467 18.9465L13.64 18.6117C14.2221 16.4677 14.6445 14.9071 15.5458 13.6911C16.439 12.4859 17.6526 11.5569 19.0478 11.0105C20.2168 10.5526 21.5233 10.4738 23.2031 10.4615ZM23.2056 14.1612C21.5798 14.1785 20.929 14.24 20.3887 14.4517C19.6371 14.7463 18.9835 15.247 18.5026 15.8966C18.0704 16.48 17.815 17.2948 17.1028 19.9212L16.2334 23.1114C18.7826 22.7692 22.0734 22.7692 26.16 22.7692H37.8376C41.9266 22.7692 45.2174 22.7692 47.7642 23.1114L46.8972 19.9188C46.1826 17.2923 45.9296 16.4775 45.4974 15.8942C45.0165 15.2446 44.3629 14.7438 43.6113 14.4492C43.071 14.2375 42.4178 14.176 40.792 14.1588C40.4429 14.8939 39.8932 15.5149 39.2064 15.9497C38.5196 16.3845 37.724 16.6153 36.9117 16.6154H27.0883C26.276 16.6153 25.4804 16.3845 24.7936 15.9497C24.1069 15.5149 23.5571 14.8939 23.208 14.1588M27.0883 11.6923C26.9255 11.6923 26.7693 11.7571 26.6542 11.8726C26.539 11.988 26.4743 12.1445 26.4743 12.3077C26.4743 12.4709 26.539 12.6274 26.6542 12.7428C26.7693 12.8582 26.9255 12.9231 27.0883 12.9231H36.9117C37.0746 12.9231 37.2307 12.8582 37.3459 12.7428C37.461 12.6274 37.5257 12.4709 37.5257 12.3077C37.5257 12.1445 37.461 11.988 37.3459 11.8726C37.2307 11.7571 37.0746 11.6923 36.9117 11.6923H27.0883ZM16.5281 26.7963C14.2933 27.1212 13.2102 27.712 12.5251 28.5932C11.8374 29.472 11.5304 30.6658 11.7588 32.9206C11.9921 35.2246 12.7412 38.2375 13.807 42.5206C14.4897 45.248 14.9613 47.1385 15.5016 48.5514C16.0173 49.9151 16.5257 50.6363 17.1642 51.136C17.8002 51.6332 18.6205 51.9508 20.0695 52.1255C21.5675 52.3052 23.5077 52.3077 26.3172 52.3077H37.6878C40.4948 52.3077 42.4399 52.3052 43.9355 52.1255C45.3844 51.9532 46.2047 51.6332 46.8408 51.136C47.4793 50.6363 47.9852 49.9151 48.5058 48.5514C49.0412 47.1385 49.5152 45.248 50.1955 42.5206C51.2638 38.2375 52.0128 35.2246 52.2436 32.9206C52.4745 30.6658 52.1651 29.4695 51.4799 28.5908C50.7947 27.712 49.7117 27.1212 47.4744 26.7963C45.1904 26.4665 42.0911 26.4615 37.6878 26.4615H26.3172C21.9138 26.4615 18.8145 26.4665 16.5306 26.7963" fill="#567E72"/>
                </svg>
                <span class="fs24 weight-700">Добавить в корзину</span>
            </div>
        </div>
        <div class="item__title mb16 color-green weight-500 fs22"><?php the_title() ?></div>
        <div class="item__price montserrat">
            <?php if ( $sale_price ) { ?>
                <div class="old fs24 weight-500"><?php echo wc_price($regular_price) ?></div>
                <div class="new fs24 weight-700"><?php echo wc_price($sale_price) ?></div>
            <?php } else { ?>
                <div class="normal fs24 weight-700"><?php echo wc_price($current_price) ?></div>
            <?php } ?>
        </div>
	</a>
</div>