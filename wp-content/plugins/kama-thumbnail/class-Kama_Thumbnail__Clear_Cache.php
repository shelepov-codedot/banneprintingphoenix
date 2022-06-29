<?php

trait Kama_Thumbnail__Clear_Cache {

	/**
	 * Очищает произвольное поле со ссылкой при обновлении поста,
	 * чтобы создать его снова. Только если метаполе у записи существует.
	 *
	 * @param int $post_id
	 */
	public function clear_post_meta( $post_id ){
		global $wpdb;

		$row = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $post_id, self::$opt->meta_key
		) );

		if( $row ){
			update_post_meta( $post_id, self::$opt->meta_key, '' );
		}
	}

	public function cache_clear_handler(){

		if( isset( $_GET['kt_clear'] ) && current_user_can( 'manage_options' ) ){

			$this->force_clear( $_GET['kt_clear'] );

			ob_start();
			do_action('admin_notices');
			$msg = ob_get_clean();

			/** @noinspection ForgottenDebugOutputInspection */
			wp_die( $msg );
		}

		$this->smart_clear( 'stub' );

		if( ! empty( self::$opt->auto_clear ) ){
			$this->smart_clear();
		}
	}

	/**
	 * Cache cleanup with expire verification.
	 *
	 * @param string $type
	 */
	public function smart_clear( $type = '' ){

		$_stub = ( $type === 'stub' );
		$cache_dir = self::$opt->cache_dir;
		$expire_file = "$cache_dir/". ( $_stub ? 'expire_stub' : 'expire' );

		if( ! is_dir( $cache_dir ) )
			return;

		$expire = $cleared = 0;
		if( file_exists( $expire_file ) ){
			$expire = (int) file_get_contents( $expire_file );
		}

		if( $expire < time() ){
			$cleared = $this->_clear_thumb_cache( $_stub ? 'only_stub' : '' );
		}

		if( $cleared || ! $expire ){
			@ file_put_contents( $expire_file, time() + ( $_stub ? DAY_IN_SECONDS : self::$opt->auto_clear_days * DAY_IN_SECONDS ) );
		}
	}

	/**
	 *
	 * ?kt_clear=clear_cache - очистит кеш картинок ?kt_clear=delete_meta - удалит произвольные поля
	 *
	 * @param $type
	 */
	public function force_clear( $type ){

		switch( $type ){
			case 'rm_stub_thumbs':
				$this->_clear_thumb_cache('only_stub');
				break;
			case 'rm_thumbs':
				$this->_clear_thumb_cache();
				break;
			case 'rm_post_meta':
				$this->_delete_meta();
				break;
			case 'rm_all_data':
				$this->_clear_thumb_cache();
				$this->_delete_meta();
				break;
		}
	}


	/**
	 * Removes cached images files.
	 *
	 * @param bool $only_stub
	 *
	 * @return bool
	 */
	public function _clear_thumb_cache( $only_stub = false ){

		$cache_dir = self::$opt->cache_dir;

		if( ! $cache_dir ){
			self::show_message( __( 'ERROR: Path to cache not set.', 'kama-thumbnail' ), 'error' );

			return false;
		}

		if( is_dir( $cache_dir ) ){

			// delete stub only
			if( $only_stub ){
				foreach( glob( "$cache_dir/stub_*" ) as $file ){
					unlink( $file );
				}

				if( defined( 'WP_CLI' ) || WP_DEBUG ){
					self::show_message(
						__( 'All nophoto thumbs was deleted from <b>Kama Thumbnail</b> cache.', 'kama-thumbnail' ), 'notice-info'
					);
				}
			}
			// delete all
			else{
				self::_clear_folder( $cache_dir );
				self::show_message( __( '<b>Kama Thumbnail</b> cache has been cleared.', 'kama-thumbnail' ) );
			}
		}

		return true;
	}

	/**
	 * Удаляет все метаполя `photo_URL` у записей.
	 */
	public function _delete_meta(){
		global $wpdb;

		if( ! self::$opt->meta_key ){
			self::show_message( 'meta_key option not set.', 'error' );
			return;
		}

		if( is_multisite() ){
			$deleted = [];
			$sites = get_sites( [
				'fields' => 'ids',
				'number' => 500,
			] );

			foreach( $sites as $blog_id ){
				$deleted[] = $wpdb->delete(
					$wpdb->get_blog_prefix( $blog_id ) .'postmeta', [ 'meta_key' => self::$opt->meta_key ]
				);
			}

			$deleted = (bool) array_filter( $deleted );
		}
		else
			$deleted = $wpdb->delete( $wpdb->postmeta, [ 'meta_key' => self::$opt->meta_key ] );

		if( $deleted )
			self::show_message( sprintf( __('All custom fields <code>%s</code> was deleted.','kama-thumbnail'), self::$opt->meta_key ) );
		else
			self::show_message( sprintf( __('Couldn\'t delete <code>%s</code> custom fields','kama-thumbnail'), self::$opt->meta_key ) );

		wp_cache_flush();
	}

	/**
	 * Удаляет все файлы и папки в указанной директории.
	 *
	 * @param string $folder_path Путь до папки которую нужно очистить.
	 */
	static function _clear_folder( $folder_path, $del_current = false ){

		$folder_path = untrailingslashit( $folder_path );

		foreach( glob("$folder_path/*") as $file ){
			if( is_dir($file) )
				call_user_func( __METHOD__, $file, true );
			else
				unlink( $file );
		}

		if( $del_current )
			rmdir( $folder_path );
	}

}
