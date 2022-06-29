<?php
/**
 * @noinspection PhpUndefinedClassInspection
 * @noinspection PhpUnusedPrivateMethodInspection
 */

final class Kama_Thumb_CLI {

	public static function init(){
		WP_CLI::add_command( 'kthumb', [ __CLASS__, 'commands_router' ] );
	}

	public static function commands_router( $args, $assoc_args ){

		[ $command, $sub_command ] = $args;

		//WP_CLI::success( $sub_command );

		if( ! method_exists( __CLASS__, "{$command}__cmd" ) ){
			$available_commands = [];
			foreach( get_class_methods( __CLASS__ ) as $name ){
				'__cmd' === substr( $name, -5 ) && $available_commands[] = substr( $name, 0, -5 );
			}
			$available_commands = '`'. implode( '`, `', $available_commands ) .'`';
			WP_CLI::error( "Unknown command `$command`. Use one of: $available_commands" );

			return;
		}

		self::{"{$command}__cmd"}( $sub_command, $assoc_args );
	}

	/**
	 * Clear cache.
	 *
	 *     wp kthumb cache rm           # treats as `rm --stubs`
	 *     wp kthumb cache rm --stubs
	 *     wp kthumb cache rm --thumbs
	 *     wp kthumb cache rm --meta
	 *     wp kthumb cache rm --all
	 *
	 * @param string $sub_command rm
	 * @param array $args
	 */
	private static function cache__cmd( $sub_command, $args ){

		// clear cache
		if( 'rm' === $sub_command ){

			$type = 'rm_stub_thumbs';
			isset( $args['all'] )    && $type = 'rm_all_data';
			isset( $args['thumbs'] ) && $type = 'rm_thumbs';
			isset( $args['meta'] )   && $type = 'rm_post_meta';

			Kama_Thumbnail::init()->force_clear( $type );
		}
	}

}