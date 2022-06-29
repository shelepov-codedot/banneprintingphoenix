<?php

function render_cat_list($data){ ?>
    <section class="main-menu__category">
        <!--<ul class="main-menu__category-list">
            <?php /*foreach ($data as $item):
            $args = array(
                'taxonomy' => 'product_cat',
                'orderby' => 'name',
                'hierarchical' => 'true',
                'parent' => $item->term_id,
                'hide_empty' => true,
            );

            $sub_categories = get_categories($args);
            $main_title = $item->name;
            $main_link = get_category_link($item->term_id);
             */?>
            <li class="main-menu__category-list-item is_mobile">
                <a href="<?php /*echo $main_link;  */?>"><?php /*echo $main_title;  */?></a>
                <?php /*if($sub_categories):
                    render_sub_cat_list($sub_categories, $main_title, $main_link);
                    echo '<div class="main-menu__category-arrow main-menu__category-next"></div>';
                else:  */?>
                <ul>
                    <?php
/*
                            $args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => '-1','product_cat' => $item->slug, 'orderby' =>'date','order' => 'ASC' );
                            $products = new WP_Query( $args );
                            while ( $products->have_posts() ) : $products->the_post();  */?>
                    <li class="show">
                        <a href="<?php /*the_permalink();  */?>"><?php /*the_title(); */?></a>
                    </li>
                    <?php /*endwhile; wp_reset_query();  */?>
                </ul>
                <?php /*endif;  */?>
            </li>
            <?php /*endforeach; */?>
        </ul>-->
       <?php
        wp_nav_menu([
            'menu'        => 'Category Menu',
            'container'   => '',
            'items_wrap'  => '<ul class="%2$s">%3$s</ul>',
            'menu_class'  => 'main-menu__category-list',
            'fallback_cb' => 'wp_page_menu',
            'after'       => '<div class="main-menu__category-arrow main-menu__category-next"></div>',
            'before'      => '<div class="main-menu__category-arrow main-menu__category-prev is_close"></div>'
        ]);
        ?>
    </section>
<?php }

function render_sub_cat_list($sub_cat, $title, $link) { ?>
    <ul class="sub-menu__category">
        <div class="main-menu__category-arrow main-menu__category-prev is_close"></div>
        <a href="<?php echo $link; ?>" class="sub-menu__category-title"><?php echo $title; ?></a>
        <?php foreach ($sub_cat as $key => $item) :
        $args = array(
                'taxonomy' => 'product_cat',
                'orderby' => 'name',
                'hierarchical' => 'true',
                'parent' => $item->term_id,
                'hide_empty' => true,
            );?>
        <li <?php if ($key == 0): ?> class="show" <?php endif; ?>>
            <a href="<?php echo get_category_link($item->term_id); ?>"><?php echo $item->name; ?></a>

            <ul>
                <?php
                    $args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => '-1','product_cat' => $item->slug, 'orderby' =>'date','order' => 'ASC' );
                    $products = new WP_Query( $args );
                    while ( $products->have_posts() ) : $products->the_post(); ?>
                        <li><a href="<?php the_permalink(); ?>"><?php the_title();?></a></li>
                <?php endwhile; wp_reset_query(); ?>
            </ul>
        </li>
        <?php endforeach; ?>
    </ul>
<?php }

function render_category_menu($data,$cur_id){ ?>
    <ul class="category-menu__list">
        <?php foreach ($data as $item):
            $args = array(
                'taxonomy' => 'product_cat',
                'orderby' => 'name',
                'hierarchical' => 'true',
                'parent' => $item->term_id,
                'hide_empty' => false,
            );
            $sub_categories = get_categories($args);?>
            <li class="category-menu__item <?php if ($item->term_id==$cur_id) echo 'is-active' ?>">
                <a href="<?php echo get_category_link($item->term_id); ?>" class="category-menu__link"><?php echo $item->name; ?></a>
                <?php if($sub_categories):
                    render_category_menu($sub_categories,$cur_id);
                 endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

<?php }