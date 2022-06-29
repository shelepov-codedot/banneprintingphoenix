<?php

add_action( 'wp_ajax_my_action', 'my_action_callback' );
add_action( 'wp_ajax_nopriv_my_action', 'my_action_callback' );
function my_action(){
    echo 'test';

    wp_die(); // выход нужен для того, чтобы в ответе не было ничего лишнего, только то что возвращает функция
}