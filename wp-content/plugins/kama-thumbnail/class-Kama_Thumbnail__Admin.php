<?php


trait Kama_Thumbnail__Admin {

	static function show_message( $text = '', $type = 'updated' ){

		if( defined('WP_CLI') ){
			'error' === $type
				? WP_CLI::error( $text )
				: WP_CLI::success( $text );
		}
		else {
			add_action( 'admin_notices', function() use ( $text, $type ){
				echo '<div id="message" class="'. $type .' notice is-dismissible"><p>'. $text .'</p></div>';
			} );
		}
	}

	function admin_options(){

		// для мультисайта создается отдельная страница в настройках сети
		if( is_multisite() ){
			$hook = add_submenu_page( 'settings.php', 'Kama Thumbnail', 'Kama Thumbnail', 'manage_network_options', self::$opt_page_key, array( $this, '_network_options_page') );
		}

		// Добавляем блок опций на базовую страницу "Чтение"
		add_settings_section( 'kama_thumbnail', __('Kama Thumbnail Settings','kama-thumbnail'), '', self::$opt_page_key );

		// Добавляем поля опций. Указываем название, описание,
		// функцию выводящую html код поля опции.
		add_settings_field( 'kt_options_field',
			'
			<p><a class="button" target="_blank" href="'. add_query_arg('kt_clear', 'rm_stub_thumbs') .'">'. __('Remove NoPhoto Thumbs (cache)','kama-thumbnail') .'</a></p>
			
			<p><a class="button" target="_blank" href="'. add_query_arg('kt_clear', 'rm_thumbs') .'">'. __('Remove All Thumbs (cache)','kama-thumbnail') .'</a></p>
			
			<p><a class="button" target="_blank" href="'. add_query_arg('kt_clear', 'rm_post_meta') .'" onclick="return confirm(\''. __('Are You Sure?','kama-thumbnail') .'\')">'. __('Remove Releted Posts Meta','kama-thumbnail') .'</a></p>
			
			<p><a class="button" target="_blank" href="'. add_query_arg('kt_clear', 'rm_all_data') .'" onclick="return confirm(\''. __('Are You Sure?','kama-thumbnail') .'\')">'. __('Remove All Data (thumbs, meta)','kama-thumbnail') .'</a></p>
			',
			[ $this, '_options_field' ],
			self::$opt_page_key,
			'kama_thumbnail' // section
		);

		// Регистрируем опции, чтобы они сохранялись при отправке
		// $_POST параметров и чтобы callback функции опций выводили их значение.
		register_setting( self::$opt_page_key, self::$opt_name, [ $this, 'sanitize_options' ] );
	}

	function _network_options_page(){
		echo '<form method="POST" action="edit.php?action=kt_opt_up" style="max-width:900px;">';

		wp_nonce_field( self::$opt_page_key ); // settings_fields() не подходит для мультисайта...

		do_settings_sections( self::$opt_page_key );

		submit_button();

		echo '</form>';
	}

	function _options_field(){

		$opt_name = self::$opt_name;

		$opts = is_multisite() ? get_site_option( $opt_name ) : get_option( $opt_name );
		$opt  = (object) array_merge( self::def_options(), (array) $opts );

		$def_opt = (object) self::def_options();

		$elems = [
			'cache_dir' =>
				'<input type="text" name="'. $opt_name .'[cache_dir]" value="'. $opt->cache_dir .'" style="width:80%;" placeholder="'. self::$opt->cache_dir .'">'.
				'<p class="description">'. __('Full path to the cache folder with 755 rights or above.','kama-thumbnail') .'</p>',

			'cache_dir_url' =>
				'<input type="text" name="'. $opt_name .'[cache_dir_url]" value="'. $opt->cache_dir_url .'" style="width:80%;" placeholder="'. self::$opt->cache_dir_url .'">
				<p class="description">'. __('URL of cache folder.','kama-thumbnail') .'</p>',

			'no_photo_url' =>
				'<input type="text" name="'. $opt_name .'[no_photo_url]" value="'. $opt->no_photo_url .'" style="width:80%;" placeholder="'. self::$opt->no_photo_url .'">
				<p class="description">'. __('URL of stub image.','kama-thumbnail') .'</p>',

			'meta_key' =>
				'<input type="text" name="'. $opt_name .'[meta_key]" value="'. $opt->meta_key .'" class="regular-text">
				<p class="description">'. __('Custom field key, where the thumb URL will be. Default:','kama-thumbnail') .' <code>'. $def_opt->meta_key .'</code></p>',

			'allow_hosts' =>
				'<textarea name="'. $opt_name .'[allow_hosts]" style="width:350px;height:45px;">'. esc_textarea($opt->allow_hosts) .'</textarea>
				<p class="description">'. __('Hosts from which thumbs can be created. One per line: <i>sub.mysite.com</i>. Specify <code>any</code>, to use any hosts.','kama-thumbnail') .'</p>',

			'quality' =>
				'<input type="text" name="'. $opt_name .'[quality]" value="'. $opt->quality .'" style="width:50px;">
				<p class="description" style="display:inline-block;">'. __('Quality of creating thumbs from 0 to 100. Default:','kama-thumbnail') .' <code>'. $def_opt->quality .'</code></p>',

			'no_stub' => '
				<label>
					<input type="hidden" name="'. $opt_name .'[no_stub]" value="0">
					<input type="checkbox" name="'. $opt_name .'[no_stub]" value="1" '. checked(1, @ $opt->no_stub, 0) .'>
					'. __('Don\'t show nophoto image.','kama-thumbnail') .'
				</label>',

			'auto_clear' => '
				<label>
					<input type="hidden" name="'. $opt_name .'[auto_clear]" value="0">
					<input type="checkbox" name="'. $opt_name .'[auto_clear]" value="1" '. checked(1, @ $opt->auto_clear, 0) .'>
					'. sprintf(
					__('Clear all cache automaticaly every %s days.','kama-thumbnail'),
					'<input type="number" name="'. $opt_name .'[auto_clear_days]" value="'. @ $opt->auto_clear_days .'" style="width:50px;">'
				) .'
				</label>',

			'rise_small' => '
				<label>
					<input type="hidden" name="'. $opt_name .'[rise_small]" value="0">
					<input type="checkbox" name="'. $opt_name .'[rise_small]" value="1" '. checked(1, @ $opt->rise_small, 0) .'>
					'. __('Increase the thumbnail you create (width/height) if it is smaller than the specified size.','kama-thumbnail') .'
				</label>',

			'use_in_content' => '
				<input type="text" name="'. $opt_name .'[use_in_content]" value="'.( isset($opt->use_in_content) ? esc_attr($opt->use_in_content) : 'mini' ).'">
				<p class="description">'. sprintf( __('Find specified here class of IMG tag in content and make thumb from found image by it sizes. Leave this field empty to disable this function. Default: %s','kama-thumbnail'), '<code>mini</code>' ) .'</p>',

			'stop_creation_sec' => '
				<input type="number" step="0.5" name="'. $opt_name .'[stop_creation_sec]" value="'.( isset($opt->stop_creation_sec) ? esc_attr($opt->stop_creation_sec) : 20 ).'" style="width:4rem;"> '. __('seconds','kama-thumbnail') .'
				<p class="description">'. sprintf( __('The maximum number of seconds since PHP started, after which thumbnails creation will be stopped. Must be less then %s (current PHP `max_execution_time`).','kama-thumbnail'), ini_get('max_execution_time') ) .'</p>',

		];

		$elems = apply_filters( 'kama_thumb__options_field_elems', $elems, $opt_name, $opt, $def_opt );

		$elems['debug'] = '
			<label>
				<input type="hidden" name="'. $opt_name .'[debug]" value="0">
				<input type="checkbox" name="'. $opt_name .'[debug]" value="1" '. checked(1, @ $opt->debug, 0) .'>
				'. __('Debug mode. Recreates thumbs all time (disables the cache).','kama-thumbnail') .'
			</label>';

		echo '
		<style>
			.ktumb-line{ padding:.5em 0; }
		</style>
		<div class="ktumb-line">'. implode( '</div><div class="ktumb-line">', $elems ) .'</div>';

	}

	# update options from network settings.php
	function network_options_update(){
		// nonce check
		check_admin_referer( self::$opt_page_key );

		$new_opts = wp_unslash( $_POST['kama_thumbnail'] );
		//$new_opts = self::sanitize_options( $new_opts ); // сработает автоматом из register_setting() ...

		update_site_option( self::$opt_name, $new_opts );

		wp_redirect( add_query_arg( 'updated', 'true', network_admin_url( 'settings.php?page='. self::$opt_page_key  ) ) );
		exit();
	}

	# sanitize options
	function sanitize_options( $opts ){

		$defopt = self::def_options();

		foreach( $opts as $key => & $val ){

			if( $key === 'allow_hosts' ){
				$ah = wp_parse_list( $val );

				foreach( $ah as & $host ){
					$host = sanitize_text_field( $host );
					$host = Kama_Make_Thumb::parse_main_dom( $host );
				}
				unset( $host );

				$ah = array_unique( $ah );

				$val = implode( "\n", $ah );
			}
			elseif( $key === 'meta_key' && ! $val ){

				$val = $defopt['meta_key'];
			}
			elseif( $key === 'stop_creation_sec' ){

				$maxtime = (int) ( ini_get( 'max_execution_time' ) * 0.95 ); // -5%
				$val = (float) $val;
				$val = ( $val > $maxtime || ! $val ) ? $maxtime : $val;
			}
			else
				$val = sanitize_text_field( $val );
		}

		return $opts;
	}

	function setting_page_link( $actions, $plugin_file ){
		if( false === strpos( $plugin_file, basename(KT_PATH) ) ) return $actions;

		$settings_link = '<a href="'. admin_url('options-media.php') .'">'. __('Settings','kama-thumbnail') .'</a>';
		array_unshift( $actions, $settings_link );

		return $actions;
	}
}

