<?php
	get_header('shop');
	if (is_product()) {
		include get_template_directory() . "/php/woo/product.php";
	} else if (is_shop()) {
		include get_template_directory() . "/php/woo/shop.php";
	} else if (is_archive()) {
		include get_template_directory() . "/php/woo/category.php";
	};
	get_footer('shop');
