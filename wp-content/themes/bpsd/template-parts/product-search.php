<?php
$product = wc_get_product();

if (is_array($product) || is_object($product)):
?>
<li class="product-card">
    <a href="<?php the_permalink(); ?>" class="product-card__image-wrap">
        <img
                src="<?php echo wp_get_attachment_url($product->get_image_id()); ?>"
                class="product-card__image"
                alt="San Diego <?= the_title(); ?> Printing"
                title="San Diego <?= the_title(); ?> Printing"
        />
    </a>
    <div class="product-card__rating">
        <div class="product-card__rating-star star-<?php echo get_star_class($product->get_average_rating()*10,5); ?>">

        </div>
    </div>
    <a href="<?php the_permalink(); ?>" class="product-card__title"><?php the_title(); ?></a>
    <?php if($product->is_type( 'variable' )): ?>
        <div class="product-card__price">Starts at<b><?php $price = $product->get_variation_price('min'); echo ($price > 30) ? $price : '30.00' ?></b></div>
    <?php else: ?>
        <div class="product-card__price">Starts at<b><?php echo $product->get_regular_price() ?></b></div>
    <?php endif; ?>
    <div class="product-card__like is-active"></div>
</li>
<?php endif; ?>