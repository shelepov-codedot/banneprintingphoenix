<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BPSD
 */
$header=get_field('header', 'option');
$footer=get_field('footer', 'option')
?>

<footer class="footer">
    <div class="container">
        <div class="footer__wrap">
            <div>
                <h3 class="footer__title"><?php echo $footer['form']['title']; ?></h3>
                <div class="footer__description"><?php echo $footer['form']['info']; ?></div>
                <form class="footer-subscription" id="subscription">
                    <input type="email" name="email" placeholder="Email" class="footer-subscription__input" required>
                    <input type="submit" class="footer-subscription__button" value="SUBSCRIBE">
                </form>
            </div>
            <div class="footer__social">
                <ul class="footer-social__list">
                    <li class="footer-social__item">
                    <a class="footer-social__link" href="<?php echo $footer['social']['facebook']; ?>">
                        <svg class="icon">
                            <use xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#facebook"></use>
                        </svg>
                    </a>
                    </li>
                    <li class="footer-social__item">
                    <a class="footer-social__link" href="<?php echo $footer['social']['instagram']; ?>">
                        <svg class="icon">
                            <use xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#instagram"></use>
                        </svg>
                    </a>
                    </li>
                </ul>
            </div>
            <div class="menu-item__grid" >
                <?php
                wp_nav_menu( [
                    'theme_location'  => '',
                    'menu'            => 'Footer menu',
                    'container'       => '',
                    'menu_class'      => 'footer-menu',
                    'fallback_cb'     => 'wp_page_menu',
                    'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                ] );
                ?>
            </div>
            <div class="footer__copyright">
                <ul class="footer__copyright-list">
                <li class="footer__copyright-icon">
                        <svg class="icon"><use xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#visa"></use></svg>
                    </li>
                    <li class="footer__copyright-icon">
                        <svg class="icon"><use xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#mastercard"></use></svg>
                    </li>
                    <li class="footer__copyright-icon">
                        <svg class="icon"><use xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#apay"></use></svg>
                    </li>
                    <li class="footer__copyright-icon">
                        <svg class="icon"><use xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#gpay"></use></svg>
                    </li>
                    <li class="footer__copyright-icon">
                        <svg class="icon"><use xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#paypal"></use></svg>
                    </li>
                </ul>
                <p class="footer__copyright-text"><?php echo $footer['copyrights']['text']; ?></p>
            </div>
        </div>
    </div>
</footer>

<div class="page-nav">
    <a href="tel:6193165780" class="page-nav__phone">
        <div class="page-nav__phone-icon">
            <svg class="icon">
                <use xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#phone-ico"></use>
            </svg>
        </div>
    </a>
    <a href="mailto:contact@bannerprintingphoenix.com" class="page-nav__email">
        <div class="page-nav__email-icon">
            <svg class="icon">
                <use xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#email-ico"></use>
            </svg>
        </div>
    </a>
    <a href="#" id="page-up" class="page-nav__scroll">
        <svg class="icon">
            <use xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#scroll"></use>
        </svg>
    </a>
</div>

<?php wp_footer(); ?>

</body>
</html>
