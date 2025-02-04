<?php
	
	add_action('after_setup_theme', 'dwcom_setup');
	
	function dwcom_setup()
	{
		load_theme_textdomain('dwcom', get_template_directory() . '/languages');
		add_theme_support('title-tag');
		add_theme_support('automatic-feed-links');
		add_theme_support('post-thumbnails');
		add_theme_support('html5', array('search-form'));
		register_nav_menus(array('main-menu' => esc_html__('Main Menu', 'dwcom')));
		register_nav_menus(array('sidebar-menu' => esc_html__('Sidebar Menu', 'dwcom')));
	}

//delete custom css
	function wpassist_remove_block_library_css()
	{
		wp_dequeue_style('wp-block-library');
	}
	
	add_action('wp_enqueue_scripts', 'wpassist_remove_block_library_css');

// Отключаем Emoji
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('tiny_mce_plugins', 'disable_wp_emojis_in_tinymce');
	function disable_wp_emojis_in_tinymce($plugins)
	{
		if (is_array($plugins)) {
			return array_diff($plugins, array('wpemoji'));
		} else {
			return array();
		}
	}

// Кастомные настройки меню
	if (function_exists('acf_add_options_page')) {
		
		acf_add_options_page(array(
			'page_title' => 'Опции сайта',
			'menu_title' => 'Опции сайта',
			'menu_slug' => 'contacts',
			'capability' => 'edit_posts',
			'redirect' => false
		));
		
	}

// Включаем виджеты в footer
	register_sidebar(array(
		'name' => 'Footer #1',
		'id' => 'footer1',
		'before_widget' => '<div class="footer__menu">',
		'after_widget' => '</div>',
		'before_title' => '<div class="footer__title">',
		'after_title' => '</div>'
	));
	
	register_sidebar(array(
		'name' => 'Footer #2',
		'id' => 'footer2',
		'before_widget' => '<div class="footer__menu">',
		'after_widget' => '</div>',
		'before_title' => '<div class="footer__title">',
		'after_title' => '</div>'
	));

// Отключаем сжатие размера у изображений
	add_filter('big_image_size_threshold', '__return_false');

// Отключаем точки у тега "далее"
	add_filter('the_content_more_link', '__return_empty_string');

// Показываем пустые категории
	function save_inline_tags($initArray)
	{
		$opts = '*[*]';
		$initArray['valid_elements'] = $opts;
		$initArray['extended_valid_elements'] = $opts;
		
		return $initArray;
	}
	
	add_filter('tiny_mce_before_init', 'save_inline_tags');

// Свои стили для админки
	function admin_style()
	{
		wp_enqueue_style('admin-styles', get_template_directory_uri() . '/css/admin.css');
	}
	
	add_action('admin_enqueue_scripts', 'admin_style');

// транслитерация русских букв
	function transliterate_url($text)
	{
		$text = mb_strtolower($text, 'UTF-8');
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
		$text = strtr($text, $symbol_table);
		
		return $text;
	}
	
	add_filter('sanitize_title', 'transliterate_url', 1);
	add_filter('sanitize_file_name', 'transliterate_url', 1);

	// remove archive prefix
	add_filter('get_the_archive_title', function ($title) {
		return preg_replace('~^[^:]+: ~', '', $title);
	});

	// register images size
	add_image_size('slider-1920-1080', 1920, 1080, true);
	add_image_size('about-680-840', 680, 840, true);
	add_image_size('about-680', 680, 'auto', false);
	add_image_size('product-540-810', 540, 810, true);
	add_image_size('product-256-388', 256, 388, true);
	add_image_size('product-840-840', 840, 840, true);
	add_image_size('product-840-640', 840, 640, true);
	add_image_size('product-840-auto', 840, 'auto', false);

	// Woocommerce
	add_filter('woocommerce_enqueue_styles', '__return_empty_array');
	add_action('after_setup_theme', 'woocommerce_support');
	function woocommerce_support()
	{
		add_theme_support('woocommerce');
	}
	
	// Проверяем атрибуты
	function wc_get_product_id_by_attributes($product_id, $attributes)
	{
		$product = wc_get_product($product_id);
		if ($product->is_type('variable')) {
			foreach ($product->get_available_variations() as $variation) {
				// Очистка и нормализация атрибутов
				$cleaned_attributes = array_map('trim', array_map('strtolower', $attributes));
				$cleaned_variation_attributes = array_map('trim', array_map('strtolower', $variation['attributes']));
				
				// Сравнение атрибутов
				if ($cleaned_attributes == $cleaned_variation_attributes) {
					return $variation['variation_id'];
				}
			}
		}
		return false;
	}
	
	// Обновление цены вариации
	function get_variation_price() {
		if (isset($_POST['product_id'], $_POST['attributes'])) {
			$product_id = intval($_POST['product_id']);
			$attributes = $_POST['attributes'];
			
			// Получаем продукт
			$product = wc_get_product($product_id);
			if (!$product || !$product->is_type('variable')) {
				wp_send_json_error(['message' => 'Продукт не найден или не является вариативным.']);
			}
			
			// Ищем вариацию
			$variation_id = wc_get_product_id_by_attributes($product_id, $attributes);
			if (!$variation_id) {
				wp_send_json_error(['message' => 'Вариация не найдена.']);
			}
			
			// Получаем цену вариации
			$variation = wc_get_product($variation_id);
			$price_html = wc_price($variation->get_price());
			
			wp_send_json_success(['price_html' => $price_html]);
		}
		
		wp_send_json_error(['message' => 'Некорректные данные.']);
	}
	add_action('wp_ajax_get_variation_price', 'get_variation_price');
	add_action('wp_ajax_nopriv_get_variation_price', 'get_variation_price');
	
	
	// Добавляем пользовательское действие для добавления товара в корзину через AJAX
	function custom_ajax_add_to_cart()
	{
		// Проверка, что получены нужные параметры
		if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
			$product_id = intval($_POST['product_id']);
			$quantity = intval($_POST['quantity']);
			
			// Добавление товара в корзину
			$added = WC()->cart->add_to_cart($product_id, $quantity);
			
			if ($added) {
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
	
	// AJAX-обработчик для добавления вариативного товара
	function custom_ajax_add_variable_to_cart()
	{
		if (isset($_POST['product_id'], $_POST['quantity'], $_POST['attributes'])) {
			$product_id = intval($_POST['product_id']);
			$quantity = intval($_POST['quantity']);
			$attributes = $_POST['attributes'];
			
			// Форматируем атрибуты
			$formatted_attributes = array();
			foreach ($attributes as $key => $value) {
				$formatted_attributes['attribute_' . $key] = $value;
			}
			
			// Получаем продукт
			$product = wc_get_product($product_id);
			if (!$product || !$product->is_type('variable')) {
				wp_send_json_error([
					'message' => 'Продукт не найден или не является вариативным.',
				]);
			}
			
			// Получаем доступные вариации
			$available_variations = $product->get_available_variations();
			
			// Логируем переданные атрибуты
			error_log('Переданные атрибуты: ' . print_r($formatted_attributes, true));
			error_log('Доступные вариации: ' . print_r($available_variations, true));
			
			// Поиск ID вариации
			$variation_id = wc_get_product_id_by_attributes($product_id, $formatted_attributes);
			
			if (!$variation_id) {
				wp_send_json_error([
					'message' => 'Выбранные атрибуты недействительны.',
					'passed_attributes' => $formatted_attributes,
					'available_variations' => $available_variations,
				]);
			}
			
			// Добавляем товар в корзину
			$added = WC()->cart->add_to_cart($variation_id, $quantity, 0, $formatted_attributes);
			
			if ($added) {
				wp_send_json_success([
					'cart_count' => WC()->cart->get_cart_contents_count(),
				]);
			} else {
				wp_send_json_error([
					'message' => 'Не удалось добавить товар в корзину.',
				]);
			}
		} else {
			wp_send_json_error([
				'message' => 'Недостаточно данных для добавления товара в корзину.',
			]);
		}
	}
	add_action('wp_ajax_add_variable_to_cart', 'custom_ajax_add_variable_to_cart');
	add_action('wp_ajax_nopriv_add_variable_to_cart', 'custom_ajax_add_variable_to_cart');
	
	// Обработка моих скриптов
	function enqueue_woocommerce_ajax()
	{
		wp_localize_script('jquery', 'woocommerce_params', array(
			'ajax_url' => admin_url('admin-ajax.php')
		));
	}
	add_action('wp_enqueue_scripts', 'enqueue_woocommerce_ajax');
	
	
	/**
	 * AJAX обработчик для копирования атрибутов и вариаций.
	 */
	add_action('wp_ajax_copy', function () {
		// Получение данных из POST-запроса
		$source_id = intval($_POST['source']);
		$target_id = intval($_POST['target']);
		
		// Проверяем наличие ID
		if (!$source_id || !$target_id) {
			wp_send_json_error('Укажите оба ID: source и target.');
		}
		
		// Вызов функции копирования
		$result = copy_attributes_and_variations($source_id, $target_id);
		
		// Возвращаем результат
		wp_send_json_success('Копирование выполнено. Результат: ' . $result);
	});
	
	/**
	 * Копирует атрибуты и вариации из одного товара в другой.
	 *
	 * @param int $source_product_id ID исходного товара.
	 * @param int $target_product_id ID целевого товара.
	 * @return string Результат выполнения.
	 */
	function copy_attributes_and_variations($source_product_id, $target_product_id) {
		// Получаем исходный и целевой товары
		$source_product = wc_get_product($source_product_id);
		$target_product = wc_get_product($target_product_id);
		
		if (!$source_product || !$target_product) {
			return 'Продукт не найден.';
		}
		
		// Копируем атрибуты
		$source_attributes = $source_product->get_attributes();
		$target_product->set_attributes($source_attributes);
		$target_product->save();
		
		// Удаляем существующие вариации целевого товара
		$target_variations = $target_product->get_children();
		foreach ($target_variations as $variation_id) {
			wp_delete_post($variation_id, true);
		}
		
		// Копируем вариации из исходного товара
		$source_variations = $source_product->get_children();
		foreach ($source_variations as $source_variation_id) {
			$source_variation = wc_get_product($source_variation_id);
			
			if ($source_variation) {
				$new_variation = new WC_Product_Variation();
				$new_variation->set_parent_id($target_product_id);
				$new_variation->set_attributes($source_variation->get_attributes());
				$new_variation->set_regular_price($source_variation->get_regular_price());
				$new_variation->set_sale_price($source_variation->get_sale_price());
				$new_variation->set_stock_status($source_variation->get_stock_status());
				$new_variation->set_sku($source_variation->get_sku());
				$new_variation->save();
			}
		}
		
		return 'Атрибуты и вариации успешно перенесены.';
	}
	
	