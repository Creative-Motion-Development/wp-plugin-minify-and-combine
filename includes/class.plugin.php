<?php
	/**
	 * Основной класс плагина
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 19.02.2018, Webcraftic
	 * @version 1.0
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	if( !class_exists('WMAC_Plugin') ) {
		if( !class_exists('WMAC_PluginFactory') ) {
			// Этот плагин может быть аддоном плагина Clearfy, если он загружен, как аддон, то мы не подключаем фреймворк,
			// а наследуем функции фреймворка от плагина Clearfy. Если плагин скомпилирован, как отдельный плагин, то он использует собственный фреймворк для работы.
			// Константа LOADING_MINIFY_AND_COMBINE_AS_ADDON утсанавливается в классе libs/factory/core/includes/Wbcr_Factory000_Plugin

			if( defined('LOADING_MINIFY_AND_COMBINE_AS_ADDON') ) {
				class WMAC_PluginFactory {
					
				}
			} else {
				class WMAC_PluginFactory extends Wbcr_Factory000_Plugin {
					
				}
			}
		}
		
		class WMAC_Plugin extends WMAC_PluginFactory {
			
			/**
			 * @var Wbcr_Factory000_Plugin
			 */
			private static $app;
			
			/**
			 * @var bool
			 */
			private $as_addon;
			
			/**
			 * @param string $plugin_path
			 * @param array $data
			 * @throws Exception
			 */
			public function __construct($plugin_path, $data)
			{
				$this->as_addon = isset($data['as_addon']);
				
				if( $this->as_addon ) {
					$plugin_parent = isset($data['plugin_parent'])
						? $data['plugin_parent']
						: null;
					
					if( !($plugin_parent instanceof Wbcr_Factory000_Plugin) ) {
						throw new Exception('An invalid instance of the class was passed.');
					}

					// Если плагин загружен, как аддон, то мы передаем в app ссылку на класс родителя
					self::$app = $plugin_parent;
				} else {
					// Если плагин самостоятельный, то записываем в app сслыку на текущий класс
					self::$app = $this;

					parent::__construct($plugin_path, $data);
				}

				$this->setTextDomain();
				$this->setModules();
				$this->globalScripts();
				
				if( is_admin() ) {
					$this->adminScripts();
				}

				add_action('plugins_loaded', array($this, 'pluginsLoaded'));
			}
			
			/**
			 * Статический метод для быстрого доступа к информации о плагине, а также часто использумых методах.
			 *
			 * Пример:
			 * WMAC_Plugin::app()->getOption()
			 * WMAC_Plugin::app()->updateOption()
			 * WMAC_Plugin::app()->deleteOption()
			 * WMAC_Plugin::app()->getPluginName()
			 *
			 * @return Wbcr_Factory000_Plugin
			 */
			public static function app()
			{
				return self::$app;
			}

			/**
			 * Устанавливаем текстовый домен
			 */
			protected function setTextDomain()
			{
				// Localization plugin
				load_plugin_textdomain('minify-and-combibe', false, dirname(WMAC_PLUGIN_BASE) . '/languages/');
			}

			/**
			 * Подключаем модули фреймворка
			 */
			protected function setModules()
			{
				if( !$this->as_addon ) {
					self::app()->load(array(
						// Модуль отвечает за подключение скриптов и стилей для интерфейса
						array('libs/factory/bootstrap', 'factory_bootstrap_000', 'admin'),
						// Модуль отвечает за создание форм и полей
						array('libs/factory/forms', 'factory_forms_000', 'admin'),
						// Модуль отвечает за создание шаблонов страниц плагина
						array('libs/factory/pages', 'factory_pages_000', 'admin'),
						// Модуль в котором хранится общий функционал плагина Clearfy и его аддонов
						array('libs/factory/clearfy', 'factory_clearfy_000', 'all')
					));
				}
			}

			/**
			 * Регистрируем страницы плагина
			 */
			private function registerPages()
			{

				$admin_path = WMAC_PLUGIN_DIR . '/admin/pages';

				// Пример основной страницы настроек
				self::app()->registerPage('WMAC_MinifyAndCombineSettingsPage', $admin_path . '/settings.php');

				// Пример внутренней страницы настроек
				//self::app()->registerPage('WMAC_StatisticPage', $admin_path . '/statistic.php');
			}

			/**
			 * Подключаем функции бекенда
			 */
			private function adminScripts()
			{
				require_once(WMAC_PLUGIN_DIR . '/admin/boot.php');
				$this->registerPages();
			}

			/**
			 * Подключаем глобальные функции
			 */
			private function globalScripts()
			{
				//require(WMAC_PLUGIN_DIR . '/includes/classes/class.configurate-comments.php');
				//new WMAC_ConfigComments(self::$app);
				require_once(WMAC_PLUGIN_DIR . '/includes/boot.php');
			}

			/**
			 * Выполнение действий после загрузки плагина
			 * Подключаем все классы оптимизации и запускаем процесс
			 */
			public function pluginsLoaded()
			{
				require_once(WMAC_PLUGIN_DIR . '/includes/classes/class.mac-base.php');
				require_once(WMAC_PLUGIN_DIR . '/includes/classes/class.mac-cache.php');
				require_once(WMAC_PLUGIN_DIR . '/includes/classes/class.mac-cache-checker.php');
				require_once(WMAC_PLUGIN_DIR . '/includes/classes/class.mac-scripts.php');
				require_once(WMAC_PLUGIN_DIR . '/includes/classes/class.mac-css-min.php');
				require_once(WMAC_PLUGIN_DIR . '/includes/classes/class.mac-styles.php');
				require_once(WMAC_PLUGIN_DIR . '/includes/classes/class.mac-main.php');
				require_once(WMAC_PLUGIN_DIR . '/includes/classes/class.mac-helper.php');

				require_once(WMAC_PLUGIN_DIR . '/includes/classes/ext/php/jsmin.php');
				require_once(WMAC_PLUGIN_DIR . '/includes/classes/ext/php/yui-php-cssmin-bundled/Colors.php');
				require_once(WMAC_PLUGIN_DIR . '/includes/classes/ext/php/yui-php-cssmin-bundled/Minifier.php');
				require_once(WMAC_PLUGIN_DIR . '/includes/classes/ext/php/yui-php-cssmin-bundled/Utils.php');

				$plugin = new WMAC_PluginMain();
				$plugin->start();
			}
		}
	}