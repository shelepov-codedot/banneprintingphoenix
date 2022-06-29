<?php

/**
 * Use following code instead of same-named functions where you want to show thumbnail:
 *
 *     echo apply_filters( 'kama_thumb_src', '', $args, $src );
 *     echo apply_filters( 'kama_thumb_img', '', $args, $src );
 *     echo apply_filters( 'kama_thumb_a_img', '', $args, $src );
 */
add_filter( 'kama_thumb_src', 'kama_thumb_hook_cb', 0, 3 );
add_filter( 'kama_thumb_img', 'kama_thumb_hook_cb', 0, 3 );
add_filter( 'kama_thumb_a_img', 'kama_thumb_hook_cb', 0, 3 );

function kama_thumb_hook_cb( $empty, $args = [], $src = 'notset' ){

	$cur_hook = current_filter(); // hook

	// suport for versions below 3.4.0 in which hooks was renamed
	foreach( $GLOBALS['wp_filter'][ $cur_hook ]->callbacks as $priority => $callbacks ){

		foreach( $callbacks as $cb ){

			// skip current hook
			if( __FUNCTION__ === $cb['function'] )
				continue;

			// re-create hooks:
			// `kama_thumb_src` → `kama_thumb__src`
			// `kama_thumb_img` → `kama_thumb__img`
			// `kama_thumb_a_img` → `kama_thumb__a_img`
			remove_filter( $cur_hook, $cb['function'], $priority );
			$new_hook = str_replace( 'kama_thumb_', 'kama_thumb__', $cur_hook );
			add_filter( $new_hook, $cb['function'], $priority, $cb['accepted_args'] );

			if( WP_DEBUG ){
				trigger_error(
					sprintf(
						'Kama Thumbnail hook `%s` was renamed to `%s` in version %s. Fix code of your theme or plugin, please.',
						$cur_hook, $new_hook, '3.4.0'
					),
					E_USER_NOTICE
				);
			}
		}
	}

	return $cur_hook( $args, $src );
}

/**
 * Make thumbnail and gets it URL.
 *
 * @param array  $args
 * @param string $src
 *
 * @return string
 */
function kama_thumb_src( $args = [], $src = 'notset' ){

	return ( new Kama_Make_Thumb( $args, $src ) )->src();
}

/**
 * Make thumbnail and gets it IMG tag.
 *
 * @param array  $args
 * @param string $src
 *
 * @return string
 */
function kama_thumb_img( $args = [], $src = 'notset' ){

	return ( new Kama_Make_Thumb( $args, $src ) )->img();
}

/**
 * Make thumbnail and gets it IMG tag wrapped with A tag.
 *
 * @param array  $args
 * @param string $src
 *
 * @return mixed|string|void
 */
function kama_thumb_a_img( $args = [], $src = 'notset' ){

	return ( new Kama_Make_Thumb( $args, $src ) )->a_img();
}

/**
 * Обращение к последнему экземпляру за свойствами класса: высота, ширина или др...
 *
 * @param string $optname
 *
 * @return mixed|Kama_Make_Thumb|null The value of specified property or
 *                                    `Kama_Make_Thumb` object if no property is specified.
 */
function kama_thumb( $optname = '' ){

	$instance = Kama_Make_Thumb::$last_instance;

	if( ! $optname )
		return $instance;

	if( property_exists( $instance, $optname ) )
		return $instance->$optname;

	return null;
}