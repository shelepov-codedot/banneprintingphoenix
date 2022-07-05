<?php
$thumbnail_id = get_woocommerce_term_meta( $args['cat_id'], 'thumbnail_id', true );
$image = wp_get_attachment_url( $thumbnail_id );
$prod_term = get_term($args['cat_id'], 'product_cat');
$category_description = $prod_term->description;
//        echo kama_thumb_img([
//            'width' => 185,
//            'height'=> 133,
//            'class' => 'category-card__image',
//            'src'   => $image,
//            'title' => 'San Diego ' . get_the_category_by_ID($args['cat_id']) . ' Printing',
//            'alt'   => 'San Diego ' . get_the_category_by_ID($args['cat_id']) . ' Printing',
//        ]);
if ((is_front_page())):
?>
<li class="home-category__card">
    <a href="<?= get_category_link($args['cat_id']); ?>">
        <div class="home-category__card-link">
            <div class="home-category__card-image">
                <img src="<?= $image;?>">
            </div>
            <div class="home-category__card-info">
                <h2 class="home-category__card-title"><?= ucwords(mb_strtolower(get_the_category_by_ID($args['cat_id']))); ?></h2>
                <p class="home-category__card-description"><?= $category_description ?></p>
            </div>
        </div>
    </a>
</li>
<?php endif; ?>