<?php

/**
 * Class Kama_Thumbnail
 *
 * @property-read object $opt Plugin Options.
 */
class Kama_Thumbnail {

	use Kama_Thumbnail__Admin;
	use Kama_Thumbnail__Clear_Cache;

	/**
	 * Current domain without www. and subdomains: www.foo.site.com → site.com
	 *
	 * @var string
	 */
	public static $main_host = '';

	public static $force_allow_hosts = [ 'youtube.com', 'youtu.be' ];

	/**
	 * Plugin options.
	 *
	 * {@see Kama_Thumbnail::def_options()}
	 *
	 * @var object
	 */
	public static $opt;

	public static $opt_name = 'kama_thumbnail';

	private static $opt_page_key = 'media'; // 'kama_thumb' for multisite

	private $skip_setting_page = false;

	private static $instance;

	/**
	 * @return Kama_Thumbnail
	 */
	public static function init(){
		self::$instance || self::$instance = new self;

		return self::$instance;
	}

	public function __construct(){

		is_multisite() && self::$opt_page_key = 'kama_thumb';

		// multisite support
		self::$main_host = Kama_Make_Thumb::parse_main_dom( get_option( 'home' ) );

		$this->set_opt();

		$this->wp_init();
	}

	/** @noinspection MagicMethodsValidityInspection */
	public function __get( $name ){

		if( 'opt' === $name )
			return self::$opt;

		return null;
	}

	private function set_opt(){

		$opt = & self::$opt;

		if( $this->skip_setting_page = has_filter( 'kama_thumb__default_options' ) ){
			$options = array();
		}
		elseif( is_multisite() ){
			$options = get_site_option( self::$opt_name, [] );
		}
		else{
			$options = get_option( self::$opt_name, [] );
		}

		$opt = (object) array_merge( self::def_options(), $options );

		$opt->no_photo_url  = $opt->no_photo_url  ?: KT_URL .'no_photo.jpg';
		$opt->cache_dir     = $opt->cache_dir     ?: untrailingslashit( str_replace( '\\', '/', WP_CONTENT_DIR . '/cache/thumb' ) );
		$opt->cache_dir_url = $opt->cache_dir_url ?: untrailingslashit( content_url() .'/cache/thumb' );

		// allowed hosts
		$ah = wp_parse_list( $opt->allow_hosts );

		foreach( $ah as & $host ){
			$host = str_replace( 'www.', '', $host );
		}
		unset( $host );

		$opt->allow_hosts = array_merge( $ah, [ self::$main_host ], self::$force_allow_hosts );
	}

	private function wp_init(){

		if( is_admin() ){

			add_action( 'admin_menu', [ $this, 'cache_clear_handler' ] );

			add_filter( 'save_post', [ $this, 'clear_post_meta' ] );

			// страница опций будет работать, если не переопределены дефолтные опции через хук `kama_thumb__default_options`
			if( ! defined( 'DOING_AJAX' ) && ! $this->skip_setting_page ){

				add_action( ( is_multisite() ? 'network_admin_menu' : 'admin_menu' ), [ $this, 'admin_options' ] );

				// ссылка на настойки со страницы плагинов
				add_filter( 'plugin_action_links', [ $this, 'setting_page_link' ], 10, 2 );

				// обновления опций
				if( is_multisite() )
					add_action( 'network_admin_edit_'.'kt_opt_up', [ $this, 'network_options_update' ] );
			}
		}

		if( self::$opt->use_in_content ){
			add_filter( 'the_content',     [ $this, 'replece_in_content' ] );
			add_filter( 'the_content_rss', [ $this, 'replece_in_content' ] );
		}

		// re-set (for multisite)
		is_multisite() && add_action( 'switch_blog', function(){
			Kama_Thumbnail::$main_host = Kama_Make_Thumb::parse_main_dom( get_option('home') );
		} );

		do_action( 'kama_thumb_inited', self::$opt );
	}

	private static function def_options(){

		/**
		 * Allows to change default options.
		 *
		 * If this hook in use, the plugin options page is disables automatically.
		 *
		 * @param array $options {
		 *     Array of options.
		 *
		 *     @type string $meta_key          Называние мета поля записи.
		 *     @type string $cache_dir      Полный путь до папки миниатюр.
		 *     @type string $cache_dir_url  URL до папки миниатюр.
		 *     @type string $no_photo_url      УРЛ на заглушку.
		 *     @type string $use_in_content    Искать ли класс mini у картинок в тексте, чтобы изменить их размер.
		 *     @type bool   $no_stub           Не выводить картинку-заглушку.
		 *     @type bool   $auto_clear        Очищать ли кэш каждые Х дней.
		 *     @type int    $auto_clear_days   Каждые сколько дней очищать кэш.
		 *     @type bool   $rise_small        Увеличить создаваемую миниатюру (ширину/высоту), если её размер меньше указанного размера.
		 *     @type int    $quality           Качество создаваемых миниатюр.
		 *     @type string $allow_hosts       Доступные хосты через запятую. Укажите 'any', чтобы разрешить любые хосты.
		 *     @type int    $stop_creation_sec Макс кол-во секунд, с момента работы PHP, в которые миниатюры будут создаваться.
		 *     @type bool   $webp              Использвоать ли webp формат для создания миниатюр?
		 *     @type int    $debug             Режим дебаг (для разработчиков).
		 * }
		 */
		return apply_filters( 'kama_thumb__default_options', [
			'meta_key'          => 'photo_URL',
			'cache_dir'         => '',
			'cache_dir_url'     => '',
			'no_photo_url'      => '',
			'use_in_content'    => 'mini',
			'no_stub'           => false,
			'auto_clear'        => false,
			'auto_clear_days'   => 7,
			'rise_small'        => true,
			'quality'           => 90,
			'allow_hosts'       => '',
			'stop_creation_sec' => 20,
			'webp'              => false,
			'debug'             => 0,
		] );
	}

	/**
	 * Поиск создание и замена миниатюр в контенте записи, по классу тега IMG.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function replece_in_content( $content ){

		$match_class = ( self::$opt->use_in_content === '1' ) ? 'mini' : self::$opt->use_in_content;

		if( false !== strpos( $content, '<img ') && strpos( $content, $match_class ) ){

			$img_ex = '<img([^>]*class=["\'][^\'"]*(?<=[\s\'"])'. $match_class .'(?=[\s\'"])[^\'"]*[\'"][^>]*)>';

			// разделение ускоряет поиск почти в 10 раз
			$content = preg_replace_callback( "~(<a[^>]+>\s*)$img_ex|$img_ex~", [ $this, '_replece_in_content' ], $content );
		}

		return $content;
	}

	private function _replece_in_content( $match ){

		$a_prefix = $match[1];
		$is_a_img = '<a' === substr( $a_prefix, 0, 2 );
		$attr = $is_a_img ? $match[2] : $match[3];

		$attr = trim( $attr, '/ ' );

		// get src="xxx"
		preg_match( '/src=[\'"]([^\'"]+)[\'"]/', $attr, $match_src );
		$src = $match_src[1];
		$attr = str_replace( $match_src[0], '', $attr );

		// make args from attrs
		$args = preg_split( '/ *(?<!=)["\'] */', $attr );
		$args = array_filter( $args );

		$_args = [];
		foreach( $args as $val ){
			[ $k, $v ] = preg_split( '/=[\'"]/', $val );
			$_args[ $k ] = $v;
		}
		$args = $_args;

		// parse srcset if set
		if( isset( $args['srcset'] ) ){

			$srcsets = array_map( 'trim', explode( ',', $args['srcset'] ) );
			$_cursize = 0;

			foreach( $srcsets as $_src ){
				preg_match( '/ (\d+[a-z]+)$/', $_src, $mm );
				$size = $mm[1];
				$_src = str_replace( $mm[0], '', $_src );

				// retina
				if( $size === '2x' ){
					$src = $_src;
					break;
				}

				$size = (int) $size;
				if( $size > $_cursize ){
					$src = $_src;
				}

				$_cursize = $size;
			}

			unset( $args['srcset'] );
		}

		$Make_Thumb = new Kama_Make_Thumb( $args, $src );

		return $is_a_img
			? $a_prefix . $Make_Thumb->img()
			: $Make_Thumb->a_img();
	}

}
