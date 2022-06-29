<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BPSD
 */

$category_url = $_SERVER['REQUEST_URI'];
$category_url = explode('/', $category_url);

if ($category_url[1] == 'product-category') {
    $current_page = max( 1, get_query_var('page') );
    $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $canonical_url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . $category_url[1] . '/' . $category_url[2] .'/';
    $url = explode('?', $url);
    $url = $url[0];

    $prev = $current_page - 1;
    $next = $current_page + 1;

    if (in_array('banner-printing', $category_url) && count($category_url) <= 4) {
        $canonical = get_option('home');
    } elseif (in_array('fabric-banners', $category_url) || in_array('vinyl-banners', $category_url)) {
        $canonical = $url;
    } else {
        $canonical = $canonical_url;
    }

    if ($prev == 1) {
        $prev_url = $url;
    } elseif ($prev != 0 && $prev != 1) {
        $prev_url = $url.'?page='.$prev;
    }

    if ($next <= $args['header_arg']) {
        $next_url = $url.'?page='.$next;
    }
}

$header=get_field('header', 'option');
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@600&display=swap" rel="stylesheet">

    <?php if (isset($canonical)): ?><link rel="canonical" href="<?php echo $canonical?>"><?php endif; ?>
    <?php if (isset($prev_url)): ?><link rel="prev" href="<?php echo $prev_url?>"><?php endif; ?>
    <?php if (isset($next_url)): ?><link rel="next" href="<?php echo $next_url?>"><?php endif; ?>

    <?php wp_head(); ?>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CRKJYWQDTQ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-CRKJYWQDTQ');
    </script>


    <?php if ((is_front_page()) and (!is_paged())): ?>
        <meta name="google-site-verification" content="BUoYxynYqf2l3VVu39P85lqHun4X3jLNqUV3HyE-NO4" />
    <?php endif; ?>

    <style>
        #chatra:not(.chatra--expanded) {
            width: 55px !important;
            height: 55px !important;
        }

        #chatra.chatra--side-left {
            right: 11px !important;
        }

        #chatra.chatra--pos-middle:not(.chatra--expanded) {
            position: fixed!important;
            bottom: 50px!important;
            width: 40px!important;
            height: 40px!important;
            z-index: 100!important;
            cursor: pointer!important;
        }

        #chatra__iframe-wrapper{
            box-shadow: none!important;
        }

        #chatra.chatra--mobile-widget.chatra--expanded.chatra--side-left:not(.chatra--transparent) {
            left: 0!important;
        }
    </style>
    <script>
        window.ChatraSetup = {
            colors: {
                buttonText: '#fff', /* цвет текста кнопки чата */
                buttonBg: '#392DA3'    /* цвет фона кнопки чата */
            },
            buttonStyle: 'round',
            buttonPosition: window.innerWidth < 768 ?
                'br' : // положение кнопки чата на маленьких экранах
                'br'  // положение кнопки чата на больших экранах
        };
    </script>
</head>

<body <?php body_class(); ?>>
    <header class="header">

        <section class="header-marketing">
            <div class="container">
                <div class="header-marketing__wrap">
                    <!--<div class="header-marketing__info">
                        <?php /*echo $header['announcement']['info']; */?>
                    </div>
                    <div class="header-marketing__cross-wrap">
                        <a href="<?php /*echo  $header['announcement']['link']; */?>"
                            class="header-marketing__offer"><?php /*echo $header['announcement']['title'];*/?></a>
                        <div class="header-marketing__cross"></div>
                    </div>-->
                    <div class="user-header__contacts">
                        <a href="mailto:<?php echo $header['email']; ?>"
                           class="user-header__link user-header__link-mail">
                            <svg class="icon user-header__link-icon">
                                <use xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#email-ico"></use>
                            </svg>
                            <span><?= $header['email']; ?></span>
                        </a>
                        <a href="tel:<?php echo preg_replace("/[^0-9]/", "",$header['phone']); ?>"
                           class="user-header__link">
                            <svg class="icon user-header__link-icon">
                                <use xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#phone-ico"></use>
                            </svg>
                            <span><?= $header['phone']; ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="user-header">
            <div class="container">
                <div class="user-header__wrap">
                    <div class="main-menu__toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="user-header__semi-wrap">
                        <?php if (is_front_page()): ?>
                            <a href="<?php get_home_url() ?>">
                                <img class="user-header__logo"
                                     src="<?php echo get_template_directory_uri() ?>/assets/img/main-logo.png">
                            </a>
                            <?php
//                            echo kama_thumb_img([
//                                'width' => 155,
//                                'height'=> 85,
//                                'class' => 'user-header__logo',
//                                'src'   => get_template_directory_uri().'/assets/img/main-logo.png',
//                            ]);
                            ?>
                        <?php else: ?>
                            <a href="<?php get_home_url() ?>">
                                <img class="user-header__logo"
                                     src="<?php echo get_template_directory_uri() ?>/assets/img/main-logo.png">
                                <?php
                                //                            echo kama_thumb_img([
                                //                                'width' => 155,
                                //                                'height'=> 85,
                                //                                'class' => 'user-header__logo',
                                //                                'src'   => get_template_directory_uri().'/assets/img/main-logo.png',
                                //                            ]);
                                ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="user-header__dropdown-search">
                        <div class="user-header__dropdown-container">
                            <?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
                            <span>X</span>
                        </div>
                    </div>

                    <section class="main-menu">
                        <div class="main-menu__header is-mobile">
                            <div class="user-header__search-wrap">
                                <div class="main-menu__close"></div>
                                <?php if (is_front_page()): ?>
                                    <img class="main-menu__logo"
                                         src="<?php echo get_template_directory_uri() ?>/assets/img/main-logo.jpg">
                                <?php else: ?>
                                    <a class="main-menu__logo-wrap" href="/">
                                        <img class="main-menu__logo"
                                             src="<?php echo get_template_directory_uri() ?>/assets/img/main-logo.jpg">
                                    </a>
                                <?php endif; ?>
                            </div>
                            <?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
                        </div>
                        <div class="main-menu__wrap">
                            <div class="main-nav">
                                <?php
                                $args = array(
                                    'taxonomy' => 'product_cat',
                                    'orderby' => 'name',
                                    'hierarchical' => 'false',
                                    'parent' => '0',
                                    'hide_empty' => false,
                                    'exclude' => '15'
                                );
                                $top_categories = get_categories($args);
                                if ($top_categories):
                                    render_cat_list($top_categories); ?>

                                <?php endif; ?>
                            </div>
                            <div class="main-menu__footer is-mobile">
                                <a href="/contacts">CONTACTS</a>
                                <a href="/help">HELP</a>
                            </div>
                        </div>
                    </section>

                    <div class="user-nav">
                        <a class="user-nav__button user-nav__button-search">
                            <div class="user-nav__icon">
                                <svg fill="#000000" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="100px" height="100px"><path d="M 21 3 C 11.601563 3 4 10.601563 4 20 C 4 29.398438 11.601563 37 21 37 C 24.355469 37 27.460938 36.015625 30.09375 34.34375 L 42.375 46.625 L 46.625 42.375 L 34.5 30.28125 C 36.679688 27.421875 38 23.878906 38 20 C 38 10.601563 30.398438 3 21 3 Z M 21 7 C 28.199219 7 34 12.800781 34 20 C 34 27.199219 28.199219 33 21 33 C 13.800781 33 8 27.199219 8 20 C 8 12.800781 13.800781 7 21 7 Z"/></svg>
                            </div>
                        </a>
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="user-nav__button user-nav__button-cart">
                            <div class="user-nav__icon is-active">
                                <span class="is-mobile user-nav__cart-count"><?= WC()->cart->get_cart_contents_count() ?></span>
                                <svg class="icon ">
                                    <use
                                        xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#shopping-cart">
                                    </use>
                                </svg>
                            </div>
                        </a>
                        <a href="#" class="user-nav__button user-nav__button-login">
                            <div class="user-nav__icon">
                                <svg class="icon">
                                    <use
                                        xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#person">
                                    </use>
                                </svg>
                            </div>
                        </a>
                    </div>
                    <div class="user-popup">
                        <div class="user-popup__wrap">
                            <a class="user-popup__logo-wrap" href="/">
                                <img class="user-popup__logo"
                                    src="<?php echo get_template_directory_uri() ?>/assets/img/main-logo.jpg">
                            </a>
                            <div class="user-popup__cross"></div>
                        </div>

                        <a class="user-popup__link user-popup__link-first">UPLOAD FILE
                            <svg class="user-popup__icon">
                                <use
                                    xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#upload">
                                </use>
                            </svg>
                        </a>
                        <a class="user-popup__link user-popup__link-second">ORDER TRACKING
                            <svg class="user-popup__icon">
                                <use
                                    xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#truck">
                                </use>
                            </svg>
                        </a>
                        <div class="user-popup__button user-popup__button-login">LOGIN</div>
                        <div class="user-popup__button user-popup__button-register">REGISTER</div>

                    </div>
                    <div class="cart-popup">
                        <ul class="cart-popup__list">
                            <?php

                            foreach (WC()->cart->get_cart() as $product_key => $product):
                                $_product = apply_filters( 'woocommerce_cart_item_product', $product['data'], $product, $product_key );
//                                $percent = 0;
//
//                                if (!empty($product['discounts']['table'])) {
//                                    foreach ($product['discounts']['table'] as $discount) {
//                                        if ($product['quantity'] >= $discount['from'] && $product['quantity'] < $discount['to'])
//                                            $percent = $discount['discount'];
//                                    }
//                                }
//
//                                if ($percent > 0) {
//                                    $count_dop_price = $product['discounts']['price'] * $product['quantity'];
//                                    $price = ($cart_item['data']->get_price()-$count_dop_price) * ((100-$percent)/100) + $count_dop_price;
//                                } else {
//                                    $price = WC()->cart->get_product_subtotal( $_product, $product['quantity'] );
//                                }

//                                echo '<pre>';
//                                print_r($product);
//                                echo '</pre>';
//                                echo '<br>';
//                                echo '<pre>';
//                                print_r($_product);
//                                echo '</pre>';
                            ?>
                            <li>
                                <div class="cart-popup__item">
                                    <img class="cart-popup__item-img"
                                        src="<?= wp_get_attachment_image_url( $_product->get_image_id(), 'full', '$icon' ) ?>">
                                    <?php
//                                    echo kama_thumb_img([
//                                        'width' => 60,
//                                        'height'=> 40,
//                                        'class' => 'cart-popup__item-img',
//                                        'src'   => wp_get_attachment_image_url( $_product->get_image_id(), 'full', '$icon' ),
//                                    ]);
                                    ?>
                                    <div class="cart-popup__item-wrap">
                                        <div class="cart-popup__item-box">
                                            <div class="cart-popup__item-text cart-popup__item-title"><?= $_product->get_name() ?></div>
                                            <div class="cart-popup__item-text cart-popup__item-count"><?= $product['quantity'] ?></div>
                                            <div class="cart-popup__item-text cart-popup__item-price"><?= wc_price($product['line_subtotal']) ?></div>
                                        </div>

                                        <?php
                                        $attributes = $_product->get_attributes();

                                        foreach ($attributes as $attribute=>$value) {
                                            if (!empty($_product->get_default_attributes()[$attribute]) || !is_object($value)) {
                                        ?>
                                            <div class="cart__item-box">
                                                <div class="cart__item-parameters"><?= implode(' ', array_map('ucfirst', explode('-', substr($attribute, 3)))) ?>:
                                                    <?php if (isset($product['custom_size']) && $attribute == 'pa_set-size'): ?>
                                                        <?= $product['custom_size'] ?>
                                                    <?php else: ?>
                                                        <?= !is_object($value) ? $value : $_product->get_default_attributes()[$attribute]; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php
                                            } elseif (is_object($value)) {
                                                $attribute_name = $value->get_taxonomy(); // The taxonomy slug name
                                                $attribute_terms = $value->get_terms(); // The terms
                                                $attribute_slugs = $value->get_slugs(); // The term slugs
                                                //
                                                echo '<div class="cart__item-box">';
                                                echo '<div class="cart__item-parameters">'.implode(' ', array_map('ucfirst', explode('-', substr($value->get_name(), 3)))).': '.$attribute_slugs[0].'</div>';
                                                echo '</div>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="cart-popup__item-button">
                                    <?php $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $product ) : '', $product, $product_key ); ?>
                                    <a class="cart-popup__item-edit" href="<?= $product_permalink ?>">
                                        <span>Edit PRODUCT</span>
                                    </a>
                                    <a class="cart-popup__item-delete" href="<?= wc_get_cart_remove_url($product_key)?>">
                                        <span>DELETE</span>
                                    </a>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="total-popup">
                            <div class="total-popup__text">TOTAL <span class="total-popup__sum">$<?= WC()->cart->get_subtotal() ?></span></div>
                            <!--<div class="total-popup__box">
                                <input class="total-popup__discount" placeholder="Discount Code">
                                <div class="total-popup__apply">APPLY</div>
                            </div>-->
                            <div class="total-popup__button">Proceed to checkout</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script type='application/ld+json'>
            {
                "@context": "http://www.schema.org",
                "@type": "Organization",
                "name": "Banner Printing Phoenix",
                "url": "https://bannerprintingphoenix.com",
                "logo": "https://bannerprintingphoenix.com/wp-content/themes/bpsd/assets/img/main-logo.jpg",
                "description": "At Banner Printing Phoenix our main goal is to make your advertising campaign stand out and effectively spread the word about your business or event.",
                "address": {
                    "addressLocality": "Phoenix",
                    "addressCountry": "USA"
                },
                "openingHours": "Mo, Tu, We, Th, Fr, Sa, Su -",
                "contactPoint": {
                    "@type": "ContactPoint",
                    "telephone": "+1480-885-6844"
                }
            }
        </script>

    </header>
