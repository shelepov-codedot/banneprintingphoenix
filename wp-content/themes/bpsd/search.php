<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package BPSD
 */

get_header();

wc_page_noindex()
?>
    <main class="category">
        <div class="container">
            <?php woocommerce_breadcrumb(
                array(
                    'delimiter' => '',
                    'wrap_before' => '<nav class="breadcrumb">',
                    'wrap_after' => '</nav>',
                    'before' => '',
                    'after' => '',
                    'home' => _x('Home', 'breadcrumb', 'woocommerce'),
                )
            ); ?>
            <h1 class="category__title">
                <?php
                /* translators: %s: search query. */
                printf(esc_html__('Search Results for: %s', 'bpsd'), '<span>' . get_search_query() . '</span>');
                ?>
            </h1>
        </div>
        <div class="container">
            <div class="category__wrap">
                <div class="category-list__wrap category-list" style="width: 100%">
                    <ul class="home__category-list">
                        <?php
                        /* Start the Loop */
                        $i = 0;
                        while (have_posts()) :
                            the_post();
                            get_template_part( 'template-parts/product-search', 'card');

                            $i++;
                        endwhile;

                        $total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
                        $current = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;

                        render_pagination_links($current, $total);
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>
<?php
get_footer();
