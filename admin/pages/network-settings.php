<?php

/**
 * The page Network Settings.
 *
 * @since 1.0.0
 */

// Exit if accessed directly
if( !defined('ABSPATH') ) {
	exit;
}

class WMAC_NetworkSettingsPage extends Wbcr_FactoryPages000_ImpressiveThemplate {

	/**
	 * The id of the page in the admin menu.
	 *
	 * Mainly used to navigate between pages.
	 * @see Wbcr_FactoryPages000_AdminPage
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $id = "network_settings"; // Уникальный идентификатор страницы
	public $page_menu_dashicon = 'dashicons-testimonial'; // Иконка для закладки страницы, дашикон

	/**
	 * @param Wbcr_Factory000_Plugin $plugin
	 */
	public function __construct(Wbcr_Factory000_Plugin $plugin)
	{
		// Заголовок страницы
		$this->menu_title = __('Minify And Combine', 'minify-and-combine');

		// Если плагин загружен, как самостоятельный, то мы меняем настройки страницы и делаем ее внешней,
		// а не внутренней страницей родительского плагина. Внешнии страницы добавляются в Wordpress меню "Общие"

		if( ! defined('LOADING_MINIFY_AND_COMBINE_AS_ADDON') ) {
			// true - внутреняя, false- внешняя страница
			$this->internal = false;
			// Для сети
			$this->network = true;
			// меню, к которому нужно прикрепить ссылку на страницу
			$this->menu_target = 'settings.php';
			// Если true, добавляет ссылку "Настройки", рядом с действиями активации, деактивации плагина, на странице плагинов.
			$this->add_link_to_plugin_actions = false;
		}

		parent::__construct($plugin);

		add_action( 'admin_bar_menu', array( $this, 'addAdminBarMenu' ), 999 );
	}

	// Метод позволяет менять заголовок меню, в зависимости от сборки плагина.
	public function getMenuTitle()
	{
		return defined('LOADING_MINIFY_AND_COMBINE_AS_ADDON')
			? __('Minify And Combine', 'minify-and-combine')
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
	 * @return array
	 */
	public function getActionNotices($notices)
	{

		$notices[] = array(
			'conditions' => array(
				'wbcr_mac_clear_cache_success' => 1
			),
			'type' => 'success',
			'message' => __('Кеш успешно очищен.', 'minify-and-combine')
		);

		$notices[] = array(
			'conditions' => array(
				'wbcr_mac_test_success' => 1
			),
			'type' => 'success',
			'message' => __('Пример успешного выполненного уведомления.', 'minify-and-combine')
		);

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
	 * Вызывается всегда при загрузке страницы, перед опциями формы с типом страницы options
	 */
	protected function warningNotice()
	{
		$this->printErrorNotice(__("The backup folder wp-content/uploads/backup/ cannot be created or is not writable by the server, original images cannot be saved!", 'wbcr_factory_pages_000'));
	}

	/**
	 * Метод должен передать массив опций для создания формы с полями.
	 * Созданием страницы и формы занимается фреймворк
	 *
	 * @since 1.0.0
	 * @return mixed[]
	 */
	public function getOptions()
	{
		$options = array();

		$options[] = array(
			'type' => 'html',
			'html' => '<div class="wbcr-factory-page-group-header"><strong>' . __('JavaScript Options', 'minify-and-combine') . '</strong><p>' . __('Описание общих настроек', 'minify-and-combine') . '</p></div>'
		);

		// Переключатель
		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'js_optimize',
			'title' => __('Optimize JavaScript Code?', 'clearfy'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
			//'hint' => __('Optimize JavaScript Code.', 'minify-and-combine'),
			'default' => false
		);

		// Переключатель
		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'js_aggregate',
			'title' => __('Aggregate JS-files?', 'clearfy'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
			'hint' => __('Aggregate all linked JS-files to have them loaded non-render blocking? If this option is off, the individual JS-files will remain in place but will be minified.', 'minify-and-combine'),
			'default' => false
		);

		// Переключатель
		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'js_include_inline',
			'title' => __('Also aggregate inline JS?', 'clearfy'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
			'hint' => __('Let Мinify And Combine also extract JS from the HTML. Warning: this can make Мinify And Combine\'s cache size grow quickly, so only enable this if you know what you\'re doing.', 'minify-and-combine'),
			'default' => false
		);

		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'js_forcehead',
			'title' => __('Force JavaScript in &lt;head&gt;?', 'clearfy'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
			'hint' => __('Load JavaScript early, this can potentially fix some JS-errors, but makes the JS render blocking.', 'minify-and-combine'),
			'default' => false
		);

		$options[] = array(
			'type' => 'textarea',
			'name' => 'js_exclude',
			'title' => __('Exclude scripts from Мinify And Combine:', 'clearfy'),
			//'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
			'hint' => __('A comma-separated list of scripts you want to exclude from being optimized, for example \'whatever.js, another.js\' (without the quotes) to exclude those scripts from being aggregated and minimized by Мinify And Combine.', 'minify-and-combine'),
			'default' => 'seal.js, js/jquery/jquery.js'
		);

		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'js_trycatch',
			'title' => __('Add try-catch wrapping?', 'clearfy'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
			'hint' => __('If your scripts break because of a JS-error, you might want to try this.', 'minify-and-combine'),
			'default' => false
		);

		$options[] = array(
			'type' => 'html',
			'html' => '<div class="wbcr-factory-page-group-header"><strong>' . __('CSS Options', 'minify-and-combine') . '</strong><p>' . __('Описание раздела оптимизация', 'minify-and-combine') . '</p></div>'
		);

		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'css_optimize',
			'title' => __('Optimize CSS Code?', 'clearfy'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
			'hint' => __('If your scripts break because of a JS-error, you might want to try this.', 'minify-and-combine'),
			'default' => false
		);

		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'css_aggregate',
			'title' => __('Aggregate CSS-files?', 'clearfy'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
			'hint' => __('Aggregate all linked CSS-files? If this option is off, the individual CSS-files will remain in place but will be minified.', 'minify-and-combine'),
			'default' => false
		);

		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'css_include_inline',
			'title' => __('Also aggregate inline CSS?', 'clearfy'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
			'hint' => __('Check this option for Мinify And Combine to also aggregate CSS in the HTML.', 'minify-and-combine'),
			'default' => false
		);

		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'css_datauris',
			'title' => __('Generate data: URIs for images?', 'clearfy'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
			'hint' => __('Enable this to include small background-images in the CSS itself instead of as separate downloads.', 'minify-and-combine'),
			'default' => false
		);

		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'css_defer',
			'title' => __('Inline and Defer CSS?', 'clearfy'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
			'hint' => __('Inline "above the fold CSS" while loading the main auto optimized CSS only after page load. Check the FAQ for more info.
This can be fully automated for different types of pages with the Мinify And Combine CriticalCSS Power-Up.', 'minify-and-combine'),
			'default' => false
		);

		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'css_inline',
			'title' => __('Inline all CSS?', 'clearfy'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
			'hint' => __('Inlining all CSS can improve performance for sites with a low pageviews/ visitor-rate, but may slow down performance otherwise.', 'minify-and-combine'),
			'default' => false
		);

		$options[] = array(
			'type' => 'textarea',
			'name' => 'css_exclude',
			'title' => __('Exclude CSS from Мinify And Combine:', 'clearfy'),
			//'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
			'hint' => __('A comma-separated list of CSS you want to exclude from being optimized.', 'minify-and-combine'),
			'default' => 'wp-content/cache/, wp-content/uploads/, admin-bar.min.css, dashicons.min.css'
		);

		$options[] = array(
			'type' => 'html',
			'html' => '<div class="wbcr-factory-page-group-header"><strong>' . __('Cache Info', 'minify-and-combine') . '</strong><p>' . __('Описание раздела оптимизация', 'minify-and-combine') . '</p></div>'
		);

		// Произвольный html код
		$options[] = array(
			'type' => 'html',
			'html' => array($this, 'cacheInfo')
		);

		$formOptions = array();

		$formOptions[] = array(
			'type' => 'form-group',
			'items' => $options,
			//'cssClass' => 'postbox'
		);

		return apply_filters('wbcr_mac_settings_form_options', $formOptions);
	}

	public function cacheInfo()
	{
		$cache = WMAC_PluginCache::getUsedCacheMultisite();
		?>
		<div class="form-group">
			<label for="wbcr_mac_css_optimize" class="col-sm-6 control-label">
				Cache folders
			</label>

			<div class="control-group col-sm-6">
				<?php echo WP_CONTENT_DIR . WMAC_CACHE_CHILD_DIR . '[...]/' ?>
			</div>
		</div>
		<div class="form-group">
			<label for="wbcr_mac_css_optimize" class="col-sm-6 control-label">
				Can we write?
			</label>

			<div class="control-group col-sm-6">
				Yes
			</div>
		</div>
		<div class="form-group">
			<label for="wbcr_mac_css_optimize" class="col-sm-6 control-label">
				Cached styles and scripts (all sites)
			</label>

			<div class="control-group col-sm-6">
				<?php echo $cache['files'] ?> files, totalling <?php echo $cache['size'] ?> (calculated at <?php echo gmdate('H:i') ?> UTC)
			</div>
		</div>
		<div class="form-group">
			<label for="wbcr_mac_css_optimize" class="col-sm-6 control-label">
			</label>

			<div class="control-group col-sm-6">
				<a class="btn btn-default" href="<?= wp_nonce_url($this->getActionUrl('clear-cache'), 'clear_cache_' . $this->getResultId()); ?>">Очистить
					кеш</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Действие rollback
	 * Если мы перейдем по ссылке, которую мы создали для кнопки "Восстановить" для метода rollbackButton,
	 * То выполнится это действие
	 */
	public function clearCacheAction()
	{
		check_admin_referer('clear_cache_' . $this->getResultId());

		WMAC_PluginCache::clearAllMultisite();

		// редирект с выводом уведомления
		$this->redirectToAction('index', array('wbcr_mac_clear_cache_success' => 1));
	}

	/**
	 * Добавляем кнопку сброса кеша в админ бар
	 *
	 * @param $wp_admin_bar
	 */
	public function addAdminBarMenu( $wp_admin_bar ) {
        $args = array(
            'id'    => 'clear-cache-btn',
            'title' => __( 'Очистить кеш', 'image-optimizer' ),
            'href'  => wp_nonce_url(
                $this->getActionUrl( 'clear-cache' ), 'clear_cache_' . $this->getResultId()
            )
        );
        $wp_admin_bar->add_node( $args );
	}

}