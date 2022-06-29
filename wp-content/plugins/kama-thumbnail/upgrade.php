<?php

namespace Kama_Thumbnail;

/**
 * Plugin Upgrade
 * Need initiated Democracy_Poll class.
 * Нужно вызывать на странице настроек плагина, чтобы не грузить лишний раз сервер.
 */
function upgrade(){
	$ver_key = 'kama_thumb_version';

	$cur_ver = get_file_data( KT_MAIN_FILE, [ 'Version' =>'Version' ] )['Version'];
	$old_ver = get_option( $ver_key );

	if( $old_ver === $cur_ver ){
		return;
	}

	update_option( $ver_key, $cur_ver );

	$ktumb = \Kama_Thumbnail::init();

	cache_dir_rename();
}

// v 3.3.9
function cache_dir_rename(){

	// upgrade
	$opt_name = \Kama_Thumbnail::$opt_name;
	$opts = is_multisite() ? get_site_option( $opt_name ) : get_option( $opt_name );

	if( ! isset( $opts['cache_dir_url'] ) ){

		$opts['cache_dir'] = @ $opts['cache_folder'] ?: '';
		$opts['cache_dir_url'] = @ $opts['cache_folder_url'] ?: '';

		unset( $opts['cache_folder_url'], $opts['cache_folder'] );

		is_multisite()
			? update_site_option( $opt_name, $opts )
			: update_option( $opt_name, $opts );
	}

}