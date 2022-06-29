<li class="category-card">
    <a href="<?php echo get_category_link($args['cat_id']); ?>" class="category-card__link">
        <b class="category-card__title"><?php echo get_the_category_by_ID($args['cat_id']); ?></b>
        <?php
        $thumbnail_id = get_woocommerce_term_meta( $args['cat_id'], 'thumbnail_id', true );
        $image = wp_get_attachment_url( $thumbnail_id );
        ?>
        <img src="<?php echo $image;?>" class="category-card__image">
        <?php
//        echo kama_thumb_img([
//            'width' => 186,
//            'height'=> 134,
//            'class' => 'category-card__image',
//            'src'   => $image,
//        ]);
        ?>
        <div class="category-card__price">
            Starts at
            <b>
                <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                    'orderby' => 'meta_value_num',
                    'meta_key' => '_price',
                    'order' => 'asc',
                    'tax_query'             => array(
                        array(
                            'taxonomy'      => 'product_cat',
                            'field' => 'term_id',
                            'terms'         => $args['cat_id'],
                            'operator'      => 'IN'
                        ),
                    )
                );
                $products = get_posts( $args );
                if($products):
                    if (wc_get_product($products[0]->ID)->get_regular_price()) {
                        echo wc_get_product($products[0]->ID)->get_regular_price();
                    } else {
                        $price = explode('.', wc_get_product($products[0]->ID)->get_variation_price('min'))[0];
                        echo ($price >= 30) ? $price : '30';
                    }
                endif;
                ?>
            </b>
        </div>
    </a>
</li>
