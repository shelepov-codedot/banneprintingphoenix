<?php
get_header();
add_action('wp_ajax_nopriv__to_basket', '_to_basket');

$product = wc_get_product(get_the_ID());
$attachment_ids = $product->get_gallery_image_ids();
array_push($attachment_ids, $product->get_image_id());
array_unique($attachment_ids);

file_put_contents('../.log-product_data', date('[Y-m-d H:i:s] !! ') . "\n" . print_r($product, true) . PHP_EOL, FILE_APPEND | LOCK_EX);

if($product->get_category_ids()):
   $table= get_field('price_table', 'product_cat_'.$product->get_category_ids()[0]);
endif;
$attributes=$product->get_attributes();

//print_r($attributes);
//die;
$comments = get_comments( array(
    'status'      => 'approve',
    'post_status' => 'publish',
    'post_type'   => 'product',
    'post_id'=> get_the_ID()
));
$review_total=$product->get_rating_count();
$average_rating=$product->get_average_rating();
$review_stats=$product->get_rating_counts();
$files=get_field('product_info');

$attribute_keys  = array_keys($attributes);
//echo '<pre>';
//print_r($attribute_keys);
//echo '</pre>';
//die;

$variations_attr = '[]';
if (!empty($attribute_keys)) {
//    echo '<pre>';
//    print_r($product->get_available_variations());
//    echo '</pre>';
//    die();
    $variations_array = array_map(function ($item) {
        return [
            'attributes'            => $item['attributes'],
            'display_price'         => $item['display_price'],
            'variation_id'          => $item['variation_id'],
            'variation_description' => $item['variation_description']
        ];
    }, $product->get_available_variations());

    $variations = wp_json_encode($variations_array);

    $ajax_url = json_encode([
        'url' => admin_url('admin-ajax.php'),
        'variations'=> $variations,
        'default_price'=>$product->get_variation_price('min')
    ]);

    echo "
        <script>var ajax_url = {$ajax_url}</script>
    ";

    $variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations ) : _wp_specialchars( $variations, ENT_QUOTES, 'UTF-8', true );
}
//echo '<pre>';
//print_r(wp_json_encode(wc_get_product(get_the_ID())->get_available_variations()));
//echo '</pre>';
//die;
?>

<main class="new-class">
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
        <h1 class="product__title"><?php the_title(); ?></h1>
        <script type="application/ld+json">
            <?php
            if ($attachment_ids) {
                $image = '"image": [';
                foreach( $attachment_ids as &$attachment_id ) {
                    $attachment_id = '"'.wp_get_attachment_image_url( $attachment_id, [520, 400], '$icon' ).'"';
                }
                $image .= implode(', ', $attachment_ids);
                $image .= '],';
            }
            ?>

            {
                "@context": "http://schema.org/",
                "@type": "Product",
                "name": "<?php the_title(); ?>",
                <?php if (!empty($image)) { echo $image; } ?>
                "brand": {
                    "@type": "Thing",
                    "name": "Banner Printing"
                },
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "<?= $average_rating ?>",
                    "ratingCount": "<?= $review_total ?>"
                },
                "offers": {
                    "@type": "Offer",
                    "priceCurrency": "USD",
                    "price": "<?php echo $product->get_price() ?>",
                    "availability": "http://schema.org/InStock",
                    "seller": {
                        "@type": "Organization",
                        "name": "https://bannerprintingsanfrancisco.com/"
                    }
                }
            }
        </script>
    </div>

    <div class="container product">
        <div class="product__wrapper">
            <div class="product__wrap">
                <?php if ($attachment_ids): ?>
                <ul id="banner-slider" class="product__box">
                    <?php  foreach( $attachment_ids as $attachment_id ): ?>
                    <img class="product__image"
                        src=<?php echo $attachment_id;?>
                        alt="San Francisco <?= the_title(); ?> Printing"
                        title="San Francisco <?= the_title(); ?> Printing"
                    >
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <h2 class="product__quantity">Quantity</h2>
                <form id="quantity" class="product__button">
                    <div class="product__minus"></div>
                    <input name="quantity" type="number" min="1" value="1" placeholder="0" class="product__numbers">
                    <div class="product__plus"></div>
                </form>

            </div>
            <form id="variations-form" class="product-options">
                <div id="custom-size" class="product-options__custom_wrap" style="display: none">
                    <input class="product-options__custom" name="custom-width" type="number" min="0.1" placeholder="Width">
                    <input class="product-options__custom" name="custom-height" type="number" min="0.1" placeholder="Height">
                    <span class="error-custom-size" style="display: block;position: absolute;margin-top: -20px;color: #ec3c24;font-weight: 600;"></span>
                </div>
                <div class="product-options__preload"></div>
                <?php  if($attributes): ?>
                <?php foreach ($attributes as $item): ?>
                <p class="product-options__title"><?php echo wc_attribute_label($item->get_data()['name']); ?></p>
                <select name="attribute_<?php echo $item->get_data()['name']; ?>" class="product-options__box">
                    <?php foreach (wc_get_product_terms( get_the_ID(), $item->get_data()['name'], array( 'fields' =>  'all' ) ) as $value):?>
                    <option value="<?php echo $value->slug;?>" class="product-options__item"><?php echo $value->name;?></option>
                    <?php endforeach; ?>
                </select>
                <?php endforeach; ?>
                <?php endif; ?>
                <p class="product-options__subtitle">Add files for print</p>
                <div class=" hide-in-pc product-options__loaded-files-wrap">
                    <div class="product-options__loaded-files">

                    </div>
                </div>
                <div class="product-options__wrap">
                    <input name="files[]" id="uploadFiles" type="file" style="visibility: hidden; width: 0" multiple="multiple" accept="image/jpeg, image/jpg, image/psb, image/png, image/tiff, image/pdf, image/ai, image/eps, image/svg, image/indd"/>
                    <button class="product-options__upload" for="uploadFiles">upload file</button>
                </div>
                <input type="hidden" name="product_id" value="<?php echo get_the_ID();?>">
                <?php if ($product->is_type('variable')): ?>
                <input type="hidden" name="variation_id" value="">
                <?php endif; ?>
            </form>
        </div>
        <div class="price-desktop">
            <div class="price-desktop__wrapper">

                <div class="product-action">
                    <p class="product-action__text">Product Price <span
                            class="product-action__price"> <span><?php echo $product->get_price() ?></span><?php  echo get_woocommerce_currency_symbol();?></span>
                    </p>
                </div>
                <h2 class="product__quantity">Quantity</h2>
                <div class="product__button">
                    <div class="product__minus"></div>
                    <input type="number" id="number-two" min="1" value="1" placeholder="0" class="product__numbers"></input>
                    <div class="product__plus"></div>
                </div>
                <p class="product-options__subtitle">Add files for print</p>
                <div class="product-options__loaded-files-wrap">
                    <div class="product-options__loaded-files">

                    </div>
                </div>
                <div class="product-options__wrap">
                    <button class="product-options__upload" for="uploadFiles">upload file</button>
                </div>
                    <div class="product-action__buttons">
                        <button
                                class="product-action__add add_to_cart_button"
                                data-product_id="<?php echo absint( $product->get_id() ); ?>"
                                data-quantity="5"
                        >ADD TO CART</button>

                        <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
                        <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
                        <input type="hidden" name="variation_id" class="variation_id" value="0" />

                        <button class="product-action__buy add_to_cart_and_checkout">BUY NOW</button>
                    </div>
            </div>
        </div>
    </div>
    <?php if($table): ?>
    <div class="container product__table">
        <h3 class="product__table_title">Discount options</h3>
        <table>
            <?php if($table['header']): ?>
            <tr>
                <th>&nbsp;</th>
                <?php foreach ($table['header'] as $item): ?>
                <?php if($item['c']): ?>
                <th class="title" colspan="2"><?php echo $item['c'];?></th>
                <?php endif; ?>
                <?php endforeach;?>
            </tr>
            <?php endif; ?>
            <?php if($table['body']): ?>
            <?php foreach ($table['body'] as $row): $i=0; ?>
            <tr>
                <?php foreach ($row as $item): ?>
                <td class="<?php if($i==0) echo 'number'; else echo 'text' ?>"><?php echo $item['c']; ?></td>
                <?php $i++; endforeach; ?>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
    <?php endif; ?>
    <div class="product-description">
        <div class="product-description__top">
            <div class="product-description__title product-description__title1 is-active">Description</div>
            <div class="product-description__title product-description__title2">Product specs</div>
        </div>
        <div class="container product-description__wrap">

            <div class="product-description__upload">
                <?php if ($files['template']): ?>
                    <div class="product-description__upload_box">
                        <div class="product-description__upload_text">Template</div>
                        <a href="<?php echo $files['template']; ?>" class="product-description__upload_button" target="_blank">
                            <svg class="product-description__upload_icon">
                                <use
                                        xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#upload">
                                </use>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if ($files['specsheet']): ?>
                    <div class="product-description__upload_box">
                        <div class="product-description__upload_text">Specsheet</div>
                        <a href="<?php echo $files['specsheet']; ?>" class="product-description__upload_button"
                           target="_blank">
                            <svg class="product-description__upload_icon">
                                <use
                                        xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#upload">
                                </use>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
            </div>


            <div class="product-description__text">
                <?php echo the_content();?>
            </div>
        </div>

    </div>

    <div class="product-action">
        <p class="product-action__text">Product Price <span
                    class="product-action__price"> <span><?php echo $product->get_price() ?></span><?php  echo get_woocommerce_currency_symbol();?></span>
        </p>
        <div class="product-action__buttons">
            <button class="product-action__add add_to_cart_button"
                    data-product_id="<?php echo absint( $product->get_id() ); ?>"
                    data-quantity="5"
            >ADD TO CART</button>
            <button class="product-action__buy add_to_cart_and_checkout">BUY NOW</button>
        </div>
    </div>


    <?php if ($comments): ?>
    <div class="review container">
        <h4 class="review__title">Reviews</h4>
        <div class="review__wrap">
            <div class="review__rating">
                <div class="product-card__rating-star star-<?php echo get_star_class($product->get_average_rating()*10,5); ?>">
                </div>
            </div>
            <div class="review__text"><?php echo $review_total . ' Review'; ?></div>
        </div>
        <div class="review__box">
            <?php for ($i = 5; $i > 0; $i--): ?>
            <?php if ($review_stats[$i]!=''): ?>
            <div class="review__rate">
                <div class="review__number"><?php echo $i; ?></div>
                <div>
                    <div class="review__line is-color"
                        style="width:<?php echo round(($review_stats[$i]/$review_total)*75) ?>px"></div>
                    <div class="review__line"></div>
                </div>
                <div class="review__value"><?php echo '('. $review_stats[$i]. ')' ?></div>
            </div>
            <?php else: ?>
            <div class="review__rate">
                <div class="review__number"><?php echo $i; ?></div>
                <div class="review__line"></div>
                <div class="review__value">(0)</div>
            </div>
            <?php endif; ?>
            <?php endfor; ?>
        </div>
        <div class="review__button_wrap">
            <button class="review__button">Write a review</button>
        </div>
        <?php foreach( $comments as $comment ) :
            $comment_meta=get_comment_meta($comment->comment_ID);
            $user_name_str=$pieces = explode(" ", $comment->comment_author, 2);
            $user_name='';
            if(count($user_name_str)<=1){
                $user_name.=mb_substr( $user_name_str[0],0,1);
                $user_name.=mb_substr( $user_name_str[0],0,1);
            } else{
                $user_name.=mb_substr( $user_name_str[0],0,1);
                $user_name.=mb_substr( $user_name_str[1],0,1);
            }

            ?>



        <div class="review__user">
            <div class="review__user_wrap">
                <div class="review__fix">
                    <div class="review__user_icon"><?php echo $user_name;?></div>
                    <div class="review__user_name">
                        <div class="review__user_type"><?php echo $comment->comment_author;?></div>
                        <div class="product-card__rating">
                            <div class="product-card__rating-star star-<?php echo get_star_class($comment_meta['rating'][0]*10,5); ?>">
                            </div>
                        </div>
                        <div class="review__user_date"><?php  echo date('m/d/y', strtotime($comment->comment_date)); ?></div>
                    </div>
                </div>
                <div class="review__user_wrapper">
                    <div class="review__user_title"><?php echo $comment_meta['review_theme'][0]; ?></div>
                    <div class="review__user_text"><?php echo $comment->comment_content; ?></div>
                    <div class="review__user_grade">
                        <div class="review__user_up"><span class="review__user_thumbsup"></span>1</div>
                        <div class="review__user_down"><span class="review__user_thumbsdown"></span>0</div>
                    </div>
                </div>
            </div>
        </div>
      <?php endforeach; ?>


    </div>

    <?php endif; ?>
    <div class="container">
        <div class="review-new">
            <form id="validate" class="review-form container review-new__wrapper">
                <div class="review-new__label">Name*</div>
                <input name="name" class="review-new__box" type="text" placeholder="Enter your name">
                <div class="review-new__label">Review theme*</div>
                <input name="theme" class="review-new__box" type="text" placeholder="Enter your review theme">
                <div class="review-new__label">Rating*</div>
                <div class="comment-form-rating">
                    <p class="comment-form-rating__stars">
                            <a class="star-1 is-active" href="#">1</a>
                            <a class="star-2 is-active" href="#">2</a>
                            <a class="star-3 is-active" href="#">3</a>
                            <a class="star-4 is-active" href="#">4</a>
                            <a class="star-5 is-active" href="#">5</a>
                    </p>
                    <select class="comment-form-rating__select" name="rating" style="display: none;">
                        <option value="5" selected>Perfect</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Not that bad</option>
                        <option value="1">Very poor</option>
                    </select>
                </div>
                <div class="review-new__label">Review*</div>
                <textarea name="text" class="review-new__box review-new__text"
                          placeholder="Write your review text"></textarea>
                <div class="review-new__wrap">
                    <button class="review-new__send">Send</button>
                    <button class="review-new__cancel">Cancel</button>
                </div>
                <input type="hidden" name="product_id" value="<?php the_id(); ?>">
            </form>
        </div>
    </div>

    <div class="modal-load">
        <span class="modal-load__close" style="display: block;" data-popup-close>ₓ</span>
        <div class="popup-load__title">Wait, your files are loading!</div>
        <div class="popup-load__inner"></div>
    </div>

    <div id="myModal">
        <span id="myModal__close" class="close" style="display: block;" data-popup-close>ₓ</span>
        <div class="popup-cart">
            <div class="popup-cart__inner">
                <div class="popup-cart__left" style="display: flex" data-bodyImage>
                    <img class="popup-cart__img" src="" data-image>
                </div>
                <div class="popup-cart__right">
                    <div class="popup-cart__title" data-tite></div>
                    <div class="attributes" data-attributes></div>

                    <form id="quantity" class="popup-product__button">
                        <div class="popup-product__minus" data-quantity-minus></div>
                        <input name="quantity" type="number" min="1" value="1" placeholder="0" class="popup-product__numbers" data-quantity>
                        <div class="popup-product__plus" data-quantity-plus></div>
                    </form>
                    <input type="hidden" value="" data-product-id>
                </div>
            </div>
            <hr class="popup-cart__line" style="display: block">
            <div class="popup-cart__btn">
                <button class="popup-product-action__open" data-redirect data-rlink="/cart/">Open cart</button>
                <button class="popup-product-action__continue" data-popup-close>Continue shopping</button>
            </div>
        </div>
    </div>
    <div id="myOverlay"></div>

</main>

<section class="home__products">
    <div class="container">
        <h2 class="home__section-title">Related Products</h2>
        <?php
        $upsells = $product->get_upsells();
            $featured = new WP_Query(array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => '-1',
                'orderby' => 'name',
                'order' => 'ASC',
                'post__in' => $upsells,
                'post__not_in' => array(get_the_ID()),
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

<input id="product_variants" type="hidden" value='<?php if (isset($variations_attr)) echo $variations_attr; // WPCS: XSS ok. ?>'>

<?php
get_footer();
?>