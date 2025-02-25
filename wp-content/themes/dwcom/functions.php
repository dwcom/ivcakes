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
	function get_variation_price()
	{
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
	function copy_attributes_and_variations($source_product_id, $target_product_id)
	{
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
	
	
	add_filter('woocommerce_states', function ($states) {
		return array(
			'RU' => array( // Только Москва и Московская область
				'RU-MOW' => 'Москва',
				'RU-MOS' => 'Московская область',
			),
		);
	});
	
	add_filter('woocommerce_get_country_locale', function ($locale) {
		if (isset($locale['RU'])) {
			$locale['RU']['state']['required'] = true; // Регион обязателен
			$locale['RU']['state']['hidden'] = false;  // Показывать список
		}
		return $locale;
	});
	
	add_filter('default_checkout_billing_postcode', function () {
		return '101000'; // Почтовый индекс Москвы
	});
	
	add_filter('default_checkout_shipping_postcode', function () {
		return '101000'; // Почтовый индекс для доставки
	});
	
	add_filter('woocommerce_checkout_fields', function ($fields) {
		if (isset($fields['billing']['billing_postcode'])) {
			$fields['billing']['billing_postcode']['required'] = false; // Сделать необязательным
			$fields['billing']['billing_postcode']['class'] = array('hidden'); // Добавить скрытый класс
		}
		if (isset($fields['shipping']['shipping_postcode'])) {
			$fields['shipping']['shipping_postcode']['required'] = false;
			$fields['shipping']['shipping_postcode']['class'] = array('hidden');
		}
		return $fields;
	});
	
	
	add_action('template_redirect', 'send_order_to_telegram_on_payment_page');
	
	function send_order_to_telegram_on_payment_page()
	{
		if (is_checkout() && isset($_GET['order']) && isset($_GET['key'])) {
			$order_id = absint($_GET['order']);
			$order = wc_get_order($order_id);
			
			if (!$order) return;
			
			// Проверяем, отправлялось ли уже сообщение
			if (get_post_meta($order_id, '_telegram_sent', true)) {
				return;
			}
			
			// Telegram API параметры
			$telegram_token = '8104546666:AAH076bdIUrXztSHwHBRyen4bx1WymjOAuY';
			$chat_id = '-1002366801077';
			
			// Данные заказа
			$order_number = $order->get_order_number();
			$order_total = $order->get_total();
			$order_currency = $order->get_currency();
			$customer_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
			$customer_phone = $order->get_billing_phone();
			$customer_email = $order->get_billing_email();
			
			// Получаем список товаров
			$items = $order->get_items();
			$product_list = "";
			foreach ($items as $item) {
				$product_list .= "🔹 " . $item->get_name() . " × " . $item->get_quantity() . "\n";
			}
			
			// Формируем сообщение
			$message = "📌 *Заказ ожидает оплаты!* #$order_number\n\n";
			$message .= "👤 *Клиент:* $customer_name\n";
			$message .= "📞 *Телефон:* $customer_phone\n";
			$message .= "✉️ *Email:* $customer_email\n";
			$message .= "\n🛍 *Товары:*\n$product_list";
			$message .= "\n💰 *Сумма:* $order_total $order_currency\n";
			$message .= "\n🔗 [Открыть заказ](https://ivcakes.ru/wp-admin/post.php?post=$order_id&action=edit)";
			
			// Кодируем сообщение
			$message = urlencode($message);
			
			// Отправка через Telegram API
			$url = "https://api.telegram.org/bot$telegram_token/sendMessage?chat_id=$chat_id&parse_mode=Markdown&text=$message";
			
			$response = wp_remote_get($url);
			
			if (!is_wp_error($response)) {
				// Помечаем, что уведомление уже отправлено
				update_post_meta($order_id, '_telegram_sent', 1);
			}
		}
	}
	
	
	add_action('wp', function () {
		if (is_order_received_page()) {
			add_action('woocommerce_thankyou', 'custom_order_received_content', 10);
			
		}
	});
	
	
	function custom_order_received_content($order_id)
	{
		if (!$order_id) return;
		
		$order = wc_get_order($order_id);
		$order_number = $order->get_order_number(); // Номер заказа
		$order_total = $order->get_total(); // Итоговая сумма без тегов
		$currency_symbol = get_woocommerce_currency_symbol();
		$billing_name = $order->get_formatted_billing_full_name();
		$billing_address = implode(', ', array_filter([
			$order->get_billing_address_1(),
			$order->get_billing_address_2(),
			$order->get_billing_city(),
			$order->get_billing_state(),
			$order->get_billing_postcode()
		]));
		$customer_note = $order->get_customer_note();
		$payment_method = $order->get_payment_method_title();
		$shipping_method = implode(', ', wp_list_pluck($order->get_shipping_methods(), 'name'));
		$items = $order->get_items();
		
		echo '<div class="custom-thankyou-container">';
		
		// Иконка подтверждения
		echo '<div class="custom-thankyou-icon">✔</div>';
		echo '<h2>Спасибо, ваш заказ оформлен!</h2>';
		
		// Основная информация
		echo '<p class="tracking-code">Номер вашего заказа: <strong>' . esc_html($order_number) . '</strong></p>';
		echo '<p class="order-total">Общая сумма заказа: <strong>' . wc_price($order_total) . '</strong></p>';
		
		// Детали заказа
		echo '<div class="order-details">';
		echo '<h3>Детали заказа</h3>';
		echo '<p><strong>Имя:</strong> ' . esc_html($billing_name) . '</p>';
		echo '<p><strong>Адрес доставки:</strong> ' . esc_html($billing_address) . '</p>';
		echo '<p><strong>Оплата:</strong> ' . esc_html($payment_method) . '</p>';
		if (!empty($customer_note)) {
			echo '<p><strong>Комментарий:</strong> ' . esc_html($customer_note) . '</p>';
		}
		echo '</div>';
		
		// Таблица товаров
		echo '<h3>Состав заказа</h3>';
		echo '<table class="order-table">';
		echo '<thead><tr><th>Товар</th><th>Кол-во</th><th>Цена</th></tr></thead>';
		echo '<tbody>';
		foreach ($items as $item_id => $item) {
			$product = $item->get_product();
			$product_name = $product ? $product->get_name() : 'Товар удален';
			$quantity = $item->get_quantity();
			$subtotal = wc_price($item->get_total()); // **Теперь цена форматируется правильно!**
			
			echo '<tr>';
			echo '<td>' . esc_html($product_name) . '</td>';
			echo '<td>' . esc_html($quantity) . '</td>';
			echo '<td>' . $subtotal . '</td>'; // **Выводим корректную цену**
			echo '</tr>';
		}
		echo '</tbody></table>';
		
		// Кнопка "Продолжить покупки"
		echo '<a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="custom-thankyou-btn">Продолжить покупки</a>';
		echo '</div>';
	}
	
	function custom_product_title_with_category_and_price($title)
	{
		if (is_product()) {
			global $post, $product;
			
			// Получаем родительскую категорию
			$terms = get_the_terms($post->ID, 'product_cat');
			$parent_cat = '';
			if (!empty($terms)) {
				foreach ($terms as $term) {
					if ($term->parent == 0) { // Берем только родительскую категорию
						$parent_cat = $term->name;
						break;
					}
				}
			}
			
			// Получаем цену товара
			$price = $product->get_price();
			$currency = get_woocommerce_currency_symbol();
			
			// Проверяем наличие данных
			if (!empty($parent_cat) && !empty($price)) {
				$title = sprintf('%s %s на заказ за %s%s в Москве | Ivcakes',
					$parent_cat,
					get_the_title($post->ID),
					$price,
					$currency
				);
			}
		}
		return $title;
	}
	
	add_filter('wpseo_title', 'custom_product_title_with_category_and_price');


// Добавление нового пункта меню в админку
	add_action('admin_menu', function () {
		add_menu_page(
			'Вариации',
			'Вариации',
			'manage_options',
			'custom_variations',
			'render_custom_variations_page',
			'dashicons-admin-generic',
			30
		);
	});

// Функция рендеринга страницы с вкладками и визуальным редактором
	function render_custom_variations_page()
	{
		$taxonomy = 'pa_nachinki-na-vybor'; // Таксономия
		$terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
		
		if (is_wp_error($terms) || empty($terms)) {
			echo '<div class="notice notice-warning"><p>Нет доступных вариаций.</p></div>';
			return;
		}
		
		echo '<div class="wrap">
            <h1>Вариации</h1>
            <h2 class="nav-tab-wrapper">';
		
		foreach ($terms as $index => $term) {
			echo '<a href="#" class="nav-tab' . ($index === 0 ? ' nav-tab-active' : '') . '" data-tab="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</a>';
		}
		
		echo '</h2>
          <div id="tabs-content">';
		
		foreach ($terms as $index => $term) {
			$description = get_option('variation_description_' . $term->term_id, '');
			
			echo '<div class="tab-content' . ($index === 0 ? ' active' : '') . '" id="' . esc_attr($term->slug) . '">
                <form method="post" action="">
                    <p>Содержимое для: <strong>' . esc_html($term->name) . '</strong></p>';
			wp_editor($description, 'variation_description_' . $term->term_id, ['textarea_name' => 'variation_description_' . $term->term_id]);
			echo '<p><input type="submit" name="save_variation_description" value="Сохранить" class="button button-primary"></p>
                </form>
              </div>';
		}
		
		echo '</div></div>';
		
		// Подключение JS для работы вкладок
		echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                const tabs = document.querySelectorAll(".nav-tab");
                const contents = document.querySelectorAll(".tab-content");
                tabs.forEach(tab => {
                    tab.addEventListener("click", function(e) {
                        e.preventDefault();
                        tabs.forEach(t => t.classList.remove("nav-tab-active"));
                        contents.forEach(c => c.classList.remove("active"));
                        this.classList.add("nav-tab-active");
                        document.getElementById(this.getAttribute("data-tab")).classList.add("active");
                    });
                });
            });
          </script>';
		
		// Подключение стилей
		echo '<style>
            .tab-content { display: none; padding: 10px; border: 1px solid #ddd; }
            .tab-content.active { display: block; }
          </style>';
	}

// Обработка сохранения описаний
	if (isset($_POST['save_variation_description'])) {
		foreach ($_POST as $key => $value) {
			if (strpos($key, 'variation_description_') === 0) {
				$term_id = str_replace('variation_description_', '', $key);
				update_option($key, wp_kses_post($value));
			}
		}
	}
	
	
	add_action('rest_api_init', function () {
		register_rest_route('custom/v1', '/variations', [
			'methods' => 'GET',
			'callback' => 'get_variations_data',
			'permission_callback' => '__return_true'
		]);
	});
	
	function get_variations_data()
	{
		$taxonomy = 'pa_nachinki-na-vybor';
		$terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
		
		if (is_wp_error($terms) || empty($terms)) {
			return [];
		}
		
		$variations_data = [];
		
		foreach ($terms as $term) {
			$description = get_option('variation_description_' . $term->term_id, '');
			$variations_data[$term->slug] = wpautop($description); // Добавляем wpautop()
		}
		
		return rest_ensure_response($variations_data);
	}
	
	function custom_sort_newest_products_first($query)
	{
		if (!is_admin() && $query->is_main_query() && (is_shop() || is_product_category() || is_product_tag())) {
			$query->set('orderby', 'date');
			$query->set('order', 'DESC');
		}
	}
	
	add_action('pre_get_posts', 'custom_sort_newest_products_first');
	
	function enqueue_select2_on_product_page()
	{
		if (is_product()) {
			// Подключаем стили и скрипты Select2
			wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2/dist/css/select2.min.css', array(), '4.0.13');
			wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2/dist/js/select2.min.js', array('jquery'), '4.0.13', true);
		}
	}
	
	add_action('wp_enqueue_scripts', 'enqueue_select2_on_product_page');
