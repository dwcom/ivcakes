<?php
	
	add_action( 'after_setup_theme', 'dwcom_setup' );
	
	function dwcom_setup() {
		load_theme_textdomain( 'dwcom', get_template_directory() . '/languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form' ) );
		register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'dwcom' ) ) );
		register_nav_menus( array( 'sidebar-menu' => esc_html__( 'Sidebar Menu', 'dwcom' ) ) );
	}

//delete custom css
	function wpassist_remove_block_library_css() {
		wp_dequeue_style( 'wp-block-library' );
	}
	
	add_action( 'wp_enqueue_scripts', 'wpassist_remove_block_library_css' );

// Отключаем Emoji
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_wp_emojis_in_tinymce' );
	function disable_wp_emojis_in_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}

// Кастомные настройки меню
	if ( function_exists( 'acf_add_options_page' ) ) {
		
		acf_add_options_page( array(
			'page_title' => 'Опции сайта',
			'menu_title' => 'Опции сайта',
			'menu_slug'  => 'contacts',
			'capability' => 'edit_posts',
			'redirect'   => false
		) );
		
	}

// Включаем виджеты в footer
	register_sidebar( array(
		'name'          => 'Footer #1',
		'id'            => 'footer1',
		'before_widget' => '<div class="footer__menu">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="footer__title">',
		'after_title'   => '</div>'
	) );
	
	register_sidebar( array(
		'name'          => 'Footer #2',
		'id'            => 'footer2',
		'before_widget' => '<div class="footer__menu">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="footer__title">',
		'after_title'   => '</div>'
	) );

// Отключаем сжатие размера у изображений
	add_filter( 'big_image_size_threshold', '__return_false' );

// Отключаем точки у тега "далее"
	add_filter( 'the_content_more_link', '__return_empty_string' );

// Показываем пустые категории
	function save_inline_tags( $initArray ) {
		$opts                                 = '*[*]';
		$initArray['valid_elements']          = $opts;
		$initArray['extended_valid_elements'] = $opts;
		
		return $initArray;
	}
	
	add_filter( 'tiny_mce_before_init', 'save_inline_tags' );

// Свои стили для админки
	function admin_style() {
		wp_enqueue_style( 'admin-styles', get_template_directory_uri() . '/css/admin.css' );
	}
	
	add_action( 'admin_enqueue_scripts', 'admin_style' );

// транслитерация русских букв
	function transliterate_url( $text ) {
		$text         = mb_strtolower( $text, 'UTF-8' );
		$symbol_table = array(
			'а' => 'a',
			'б' => 'b',
			'в' => 'v',
			'г' => 'g',
			'д' => 'd',
			'е' => 'e',
			'ё' => 'yo',
			'ж' => 'zh',
			'з' => 'z',
			'и' => 'i',
			'й' => 'j',
			'к' => 'k',
			'л' => 'l',
			'м' => 'm',
			'н' => 'n',
			'о' => 'o',
			'п' => 'p',
			'р' => 'r',
			'с' => 's',
			'т' => 't',
			'у' => 'u',
			'ф' => 'f',
			'х' => 'h',
			'ц' => 'c',
			'ч' => 'ch',
			'ш' => 'sh',
			'щ' => 'shh',
			'ъ' => "",
			'ы' => 'y',
			'ь' => "",
			'э' => 'e',
			'ю' => 'yu',
			'я' => 'ya'
		);
		$text         = strtr( $text, $symbol_table );
		
		return $text;
	}
	
	add_filter( 'sanitize_title', 'transliterate_url', 1 );
	add_filter( 'sanitize_file_name', 'transliterate_url', 1 );
	
// remove archive prefix
	add_filter( 'get_the_archive_title', function ( $title ) {
		return preg_replace( '~^[^:]+: ~', '', $title );
	} );

// register images size
	add_image_size( 'slider-1920-1080', 1920, 1080, true );
	add_image_size( 'about-680-840', 680, 840, true );
	add_image_size( 'about-680', 680, 'auto', false );
	add_image_size( 'product-540-810', 540, 810, true );
	add_image_size( 'product-256-388', 256, 388, true );
	add_image_size( 'product-840-840', 840, 840, true );
	add_image_size( 'product-840-640', 840, 640, true );
	add_image_size( 'product-840-auto', 840, 'auto', false );
	
	
// Woocommerce
	add_filter('woocommerce_enqueue_styles', '__return_empty_array');
	add_action('after_setup_theme', 'woocommerce_support');
	function woocommerce_support() {
		add_theme_support('woocommerce');
	}
	
	
	// Добавляем пользовательское действие для добавления товара в корзину через AJAX
	function custom_ajax_add_to_cart() {
		// Проверка, что получены нужные параметры
		if ( isset($_POST['product_id']) && isset($_POST['quantity']) ) {
			$product_id = intval($_POST['product_id']);
			$quantity = intval($_POST['quantity']);
			
			// Добавление товара в корзину
			$added = WC()->cart->add_to_cart($product_id, $quantity);
			
			if ( $added ) {
				// Возвращаем обновленное количество товаров в корзине
				wp_send_json_success(array(
					'cart_count' => WC()->cart->get_cart_contents_count()
				));
			} else {
				wp_send_json_error('Не удалось добавить товар в корзину.');
			}
		} else {
			wp_send_json_error('Недостаточно данных для добавления товара в корзину.');
		}
	}
	add_action('wp_ajax_add_to_cart', 'custom_ajax_add_to_cart');
	add_action('wp_ajax_nopriv_add_to_cart', 'custom_ajax_add_to_cart');
	
	function enqueue_woocommerce_ajax() {
		wp_localize_script( 'jquery', 'woocommerce_params', array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		));
	}
	add_action( 'wp_enqueue_scripts', 'enqueue_woocommerce_ajax' );
	
	