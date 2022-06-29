<?php
$current_category = get_queried_object();
$per_page=6;
$products = new WP_Query(array(
    'post_type' => 'product',
    'posts_per_page' => $per_page,
    'orderby' => 'meta_value_num',
    'meta_key' => '_price',
    'order' => 'asc',
    'paged'=> get_query_var('page'),
    'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => $current_category->term_id,
            'operator' => 'IN'
        ),
    )
));

get_header(null, [ 'header_arg' => $products->max_num_pages ]);

$content=get_field('content',$current_category );
?>
<main class="category">
    <div class="container">
        <?php  woocommerce_breadcrumb(
            array(
                'delimiter'   => '',
                'wrap_before' => '<nav class="breadcrumb">',
                'wrap_after'  => '</nav>',
                'before'      => '',
                'after'       => '',
                'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
            )
        );?>
        <h1 class="category__title"><?php echo $current_category->name ?></h1>
    </div>
    <div class="container">
        <div class="category__wrap">
            <div class="category-menu is-desktop">
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
                    render_category_menu($top_categories,$current_category->term_id); ?>

                <?php endif; ?>

            </div>
            <div class="category-list__wrap category-list">
                <?php
                $args = array(
                    'taxonomy' => 'product_cat',
                    'orderby' => 'name',
                    'parent' => $current_category->term_id,
                    'hide_empty' => 'false',
                    'exclude' => '15',
                );
                $sub_categories = get_categories($args);
                if ($sub_categories): ?>
                    <ul class="home__category-list">
                        <?php foreach($sub_categories as $item): ?>
                            <?php get_template_part( 'template-parts/category', 'card-price',array(
                                'cat_id'=>$item->term_id
                            ) ); ?>
                        <?php endforeach; ?>
                    </ul>
                <?php else:

                    $products = new WP_Query(array(
                        'post_type' => 'product',
                        'posts_per_page' => $per_page,
                        'orderby' => 'menu_order',
                        'meta_key' => '_price',
                        'order' => 'asc',
                        'paged'=> get_query_var('page'),
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => $current_category->term_id,
                                'operator' => 'IN'
                            ),
                        )
                    ));
                ?>
                <div class="product-list">
                    <?php  if($products->have_posts()): ?>
                    <div class="sort">
                        Sort by
                        <select class="sort__box">
                        <option class="sort__item">Popularity</option>
                        </select>
                    </div>
                <ul class="home__products-list">
                    <?php while($products->have_posts()):
                        $products->the_post();

//                        echo '<script>';
//                        echo json_encode($products);
//                        echo '</script>';
//                        die;

                        get_template_part( 'template-parts/product', 'card');
                    endwhile; ?>
                </ul>
                        <?php render_pagination_links(get_query_var('page'),$products->max_num_pages)?>
                    <?php else: ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <section class="home__products">
        <div class="container">
            <h2 class="home__section-title">Popular Products</h2>
            <?php
            $featured = new WP_Query(array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => -1,
                'orderby' => 'name',
                'order' => 'ASC',
                'post__in' => wc_get_featured_product_ids()
            ));
            if($featured->have_posts()): ?>
                <ul id="product-slider" class="home__products-list">
                    <?php while($featured->have_posts()):
                        $featured->the_post();
                        get_template_part( 'template-parts/product', 'card');
                    endwhile; ?>
                </ul>

            <?php endif; ?>

        </div>
    </section>

    <?php if ($content): ?>
        <div class="container">
            <div class="category__seo-block">
                <?php for ($i = 0; $i < count($content); $i++): ?>
                    <div class="text-block">
                        <div class="text-block__content"><?php echo $content[$i]['info']; ?></div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>

</main>
<?php
get_footer();
?>
