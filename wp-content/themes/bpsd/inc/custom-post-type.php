<?php

register_post_type('subscriber', array(
    'labels'             => array(
        'name'               => 'subscriber', // Основное название типа записи
        'singular_name'      => 'subscriber', // отдельное название записи типа Book
    ),
    'public'             => false,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => true,
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array('title')
) );

register_post_type('Get a Quote', array(
    'labels'             => array(
        'name'               => 'Get a quote', // Основное название типа записи
        'singular_name'      => 'Get a quote', // отдельное название записи типа Book
    ),
    'public'             => false,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => true,
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array('title')
) );