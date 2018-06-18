<?php
	/**
	 * Plugin Name: Мinify And Combine
	 * Plugin URI:
	 * Description: Optimizes your website, concatenating the CSS and JavaScript code, and compressing it.
	 * Author: Webcraftic <wordpress.webraftic@gmail.com>
	 * Version: 1.0.0
	 * Text Domain: minify-and-combine
	 * Domain Path: /languages/
	 * Author URI:
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	/**
	 * Уведомление о том, что этот плагин используется уже в составе плагина Clearfy, как его компонент.
	 * Мы блокируем работу этого плагина, чтобы не вызывать конфликт.
	 */
	if( defined('WMAC_PLUGIN_ACTIVE') || (defined('WMAC_PLUGIN_ACTIVE') && !defined('LOADING_MINIFY_AND_COMBINE_AS_ADDON')) ) {
		function wbcr_mac_admin_notice_error()
		{
			?>
			<div class="notice notice-error">
				<p><?php _e('We found that you have the "Clearfy - wordpress optimization plugin" plugin installed, this plugin already has disable comments functions, so you can deactivate plugin "Image optimizer"!'); ?></p>
			</div>
		<?php
		}

		add_action('admin_notices', 'wbcr_mac_admin_notice_error');

		return;
	} else {

		// Устанавливаем контстанту, что плагин уже используется
		define('WMAC_PLUGIN_ACTIVE', true);

		// Директория плагина
		define('WMAC_PLUGIN_DIR', dirname(__FILE__));

		// Относительный путь к плагину
		define('WMAC_PLUGIN_BASE', plugin_basename(__FILE__));

		// Ссылка к директории плагина
		define('WMAC_PLUGIN_URL', plugins_url(null, __FILE__));

		#comp remove
		// Эта часть кода для компилятора, не требует редактирования
		// the following constants are used to debug features of diffrent builds
		// on developer machines before compiling the plugin

		// Сборка плагина
		// build: free, premium, ultimate
		if( !defined('BUILD_TYPE') ) {
			define('BUILD_TYPE', 'free');
		}
		// Языки уже не используются, нужно для работы компилятора
		// language: en_US, ru_RU
		if( !defined('LANG_TYPE') ) {
			define('LANG_TYPE', 'en_EN');
		}

		// Тип лицензии
		// license: free, paid
		if( !defined('LICENSE_TYPE') ) {
			define('LICENSE_TYPE', 'free');
		}

		// wordpress language
		if( !defined('WPLANG') ) {
			define('WPLANG', LANG_TYPE);
		}
		// the compiler library provides a set of functions like onp_build and onp_license
		// to check how the plugin work for diffrent builds on developer machines

		if( !defined('LOADING_MINIFY_AND_COMBINE_AS_ADDON') ) {
			require('libs/onepress/compiler/boot.php');
			// creating a plugin via the factory
		}
		// #fix compiller bug new Factory000_Plugin
		#endcomp

		// Этот плагин может быть аддоном плагина Clearfy, если он загружен, как аддон, то мы не подключаем фреймворк,
		// а наследуем функции фреймворка от плагина Clearfy. Если плагин скомпилирован, как отдельный плагин, то он использует собственный фреймворк для работы.
		// Константа LOADING_MINIFY_AND_COMBINE_AS_ADDON утсанавливается в классе libs/factory/core/includes/Wbcr_Factory000_Plugin

		if( !defined('LOADING_MINIFY_AND_COMBINE_AS_ADDON') ) {
			// Фреймворк - отвечает за интерфейс, содержит общие функции для серии плагинов и готовые шаблоны для быстрого развертывания плагина.
			require_once(WMAC_PLUGIN_DIR . '/libs/factory/core/boot.php');
		}

		// Основной класс плагина
		require_once(WMAC_PLUGIN_DIR . '/includes/class.plugin.php');

		// Класс WMAC_Plugin создается только, если этот плагин работает, как самостоятельный плагин.
		// Если плагин работает, как аддон, то класс создается родительским плагином.

		if( !defined('LOADING_MINIFY_AND_COMBINE_AS_ADDON') ) {
			new WMAC_Plugin(__FILE__, array(
				'prefix' => 'wbcr_mac_', // префикс для базы данных и полей формы
				'plugin_name' => 'wbcr_minify_and_combine', // имя плагина, как уникальный идентификатор
				'plugin_title' => __('Webcraftic minify and combine', 'minify-and-combine'), // заголовок плагина
				'plugin_version' => '1.0.0', // текущая версия плагина
				'required_php_version' => '5.2', // минимальная версия php для работы плагина
				'required_wp_version' => '4.2', // минимальная версия wp для работы плагина
				'plugin_build' => BUILD_TYPE, // сборка плагина
				//'updates' => WMAC_PLUGIN_DIR . '/updates/' в этой папке хранятся миграции для разных версий плагина
			));
		}
	}