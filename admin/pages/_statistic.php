<?php
	
	/**
	 * The page Settings.
	 *
	 * @since 1.0.0
	 */
	
	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}
	
	class WMAC_StatisticPage extends Wbcr_FactoryPages000_ImpressiveThemplate {
		
		/**
		 * The id of the page in the admin menu.
		 *
		 * Mainly used to navigate between pages.
		 * @see FactoryPages000_AdminPage
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $id = "image_optimizer"; // Уникальный идентификатор страницы
		public $type = "page"; // Этот произвольный тип страницы

		public $page_menu_dashicon = 'dashicons-testimonial'; // Иконка для закладки страницы, дашикон
		
		/**
		 * @param Wbcr_Factory000_Plugin $plugin
		 */
		public function __construct(Wbcr_Factory000_Plugin $plugin)
		{
			$this->menu_title = __('Image optimizer', 'minify-and-combine');
			
			// Если плагин загружен, как самостоятельный, то мы меняем настройки страницы и делаем ее внешней,
			// а не внутренней страницей родительского плагина. Внешнии страницы добавляются в Wordpress меню "Общие"

			if( !defined('LOADING_MINIFY_AND_COMBINE_AS_ADDON') ) {
				// true - внутреняя, false- внешняя страница
				$this->internal = false;
				// меню к которому, нужно прикрепить ссылку на страницу
				$this->menu_target = 'options-general.php';
				// Если true, добавляет ссылку "Настройки", рядом с действиями активации, деактивации плагина, на странице плагинов.
				$this->add_link_to_plugin_actions = true;
			}

			parent::__construct($plugin);
		}

		// Метод позволяет менять заголовок меню, в зависимости от сборки плагина.
		public function getMenuTitle()
		{
			return defined('LOADING_MINIFY_AND_COMBINE_AS_ADDON')
				? __('Image optimizer', 'minify-and-combine')
				: __('General', 'minify-and-combine');
		}

		/**
		 * Подключаем скрипты и стили для страницы
		 *
		 * @see Wbcr_FactoryPages000_AdminPage
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function assets($scripts, $styles)
		{
			parent::assets($scripts, $styles);

			// Способ подключения стилей
			$this->styles->add(WMAC_PLUGIN_URL . '/admin/assets/css/general.css');

			// Способ подключения скриптов
			$this->scripts->add(WMAC_PLUGIN_URL . '/admin/assets/js/general.js');
		}
		
		/**
		 * Регистрируем уведомления для страницы
		 *
		 * @see libs\factory\pages\themplates\FactoryPages000_ImpressiveThemplate
		 * @param $notices
		 * @param Wbcr_Factory000_Plugin $plugin
		 * @return array
		 */
		public function getActionNotices($notices)
		{
			// К строке запроса, добавляется переменная wbcr_mac_test_success равная 1, если условия верны выводится уведомление
			// http://testwp.test/wp-admin/admin.php?page=io_settings-wbcr_image_optimizer&wbcr_mac_test_success=1
			$notices[] = array(
				'conditions' => array(
					'wbcr_mac_test_success' => 1
				),
				'type' => 'success',
				'message' => __('Пример успешного выполненного уведомления.', 'minify-and-combine')
			);

			// К строке запроса, добавляется переменная wbcr_mac_test_error равная 1 и wbcr_mac_code равная interal_error, если условия верны выводится уведомление
			// http://testwp.test/wp-admin/admin.php?page=io_settings-wbcr_image_optimizer&wbcr_mac_test_error=1&wbcr_mac_code=interal_error
			$notices[] = array(
				'conditions' => array(
					'wbcr_mac_test_error' => 1,
					'wbcr_mac_code' => 'interal_error'
				),
				'type' => 'danger',
				'message' => __('Пример уведомления об ошибке.', 'minify-and-combine')
			);
			
			return $notices;
		}
		
		/**
		 * Prints the content of the page
		 *
		 * @see libs\factory\pages\themplates\FactoryPages000_ImpressiveThemplate
		 */
		public function showPageContent()
		{
			$this->printWarningNotice('fsdfsd');
			?>
			<div class="wbcr-factory-page-group-header" style="margin-top:0;">
				<strong><?php _e('Заголовок произвольной страницы', 'minify-and-combine') ?></strong>
				
				<p>
					<?php _e('Описание произвольной страницы.', 'minify-and-combine') ?>
				</p>
			</div>
			
			<div class="wbcr-factory-page-group-body" style="padding:20px">
				<p>
					<img style="width:100%;" src="http://dl4.joxi.net/drive/2018/05/16/0027/1778/1804018/18/8355d1b6f9.png" alt=""/>
				</p>
			</div>
		<?php
		}
		
		/**
		 * Действие для страницы
		 * Если мы перейдем по ссылке
		 * http://testwp.test/wp-admin/options-general.php?page=image_optimizer-wbcr_image_optimizer&action=simple
		 * То будет вызван этот медот для дальнейшей обрабоки действия
		 */
		public function simpleAction()
		{
			// Получение get переменных
			$var1 = $this->plugin->request->get('var1');
			// Получение post переменных
			$var2 = $this->plugin->request->post('var2');
			
			// Получение опций из таблицы wp_options
			$option = $this->plugin->getOption('test', 'default');
			
			// Обновление опций из таблицы wp_options
			$this->plugin->updateOption('test', '1');
			
			// Удаление опций из таблицы wp_options
			$this->plugin->deleteOption('test');
		}
	}