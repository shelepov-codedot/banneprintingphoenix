<?php
$product = wc_get_product();
?>
<li class="product-card">
    <a href="<?php the_permalink(); ?>" class="product-card__image-wrap">
        <img
                src="<?php echo wp_get_attachment_url($product->get_image_id()); ?>"
                class="product-card__image"
                alt="San Diego <?= the_title(); ?> Printing"
                title="San Diego <?= the_title(); ?> Printing"
        />
    <?php
//    echo kama_thumb_img([
//        'width' => 185,
//        'height'=> 133,
//        'class' => 'product-card__image',
//        'src'   => wp_get_attachment_url($product->get_image_id()),
//    ]);
    ?>
    </a>
    <div class="product-card__rating">
        <div class="product-card__rating-star star-<?php echo get_star_class($product->get_average_rating()*10,5); ?>">

        </div>
    </div>
    <a href="<?php the_permalink(); ?>" class="product-card__title"><?php the_title(); ?></a>
    <?php if($product->is_type( 'variable' )): ?>
        <?php
//            $variants = $product->get_available_variations();
//            $array_variant = [];
//            $min_price = '';
//            $min_price_description = '';
//            foreach ($variants as $variant) {
//                echo '<pre>';
//                print_r($variant);
//                echo '</pre>';
//            }
        ?>
        <div class="product-card__price">Starts at<b><?php $price = $product->get_variation_price('min'); echo ($price > 30) ? explode('.', $price)[0] : '30' ?></b></div>
    <?php else: ?>
        <div class="product-card__price">Starts at<b><?php echo $product->get_regular_price() ?></b></div>
    <?php endif; ?>
    <div class="product-card__like is-active"></div>
</li>