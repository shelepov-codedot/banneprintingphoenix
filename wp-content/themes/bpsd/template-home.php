<?php
/* Template Name: Home page */
get_header();
$hello_block = get_field('hello_block');
$home_categories = get_field('home_categories');
$home_products = get_field('home_products');
$home_process = get_field('home_process');
$home_content = get_field('home_content');
?>
<main class="home">
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "item": {
                        "@id": "https://bannerprintingphoenix.com",
                        "name": "Banner Printing Phoenix"
                    }
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "item": {
                        "@id": "https://bannerprintingphoenix.com/#promotion",
                        "name": "💡 Banner Printing in Phoenix!"
                    }
                }
            ]
        }
    </script>

    <section class="home__hello home-hello">
        <?php if ($hello_block['slides']): ?>
            <div id="home-hello" class="home-hello__slider container">
                <?php foreach ($hello_block['slides'] as $slide): ?>
                <div class="home-hello__list">
                    <!--<div class="home-hello__item" style="background-image: url(<?php /*echo $slide['image']; */?>);"></div>-->
                    <div class="div">
                        <img class="home-hello__img" src="<?php print_r($slide['image']); ?>" alt="">
                    </div>
                    <div class="slick-container">
                        <b class="home-hello__title"><?php echo $slide['title']; ?></b>
                        <div class="home-hello__sub-title"><?php echo $slide['sub-title']; ?></div>
                        <a class="home-hello__button"
                           href="<?php echo $slide['button-link']; ?>"><?php echo $slide['button-name']; ?></a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="home__categories">
        <div class="container">

            <?php if ($home_categories['categories']): ?>
                <ul class="home__category-list">
                    <?php foreach ($home_categories['categories'] as $item): ?>
                        <?php get_template_part('template-parts/category', 'card', array(
                            'cat_id' => $item
                        )); ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

        </div>
    </section>

    <section class="home__process">
        <div class="container">
            <h2 class="home__section-title"><?php echo $home_process['title']; ?></h2>
            <?php if ($home_process['list']): $i = 1; ?>
                <ul class="process-block__list">
                    <?php foreach ($home_process['list'] as $item): ?>
                        <li class="process-block__item">
                            <div class="process-block__counter">
                                <span>
                                    <img src="<?php echo $item['image']['url']; ?>" alt="<?php echo $item['title'] ?>"
                                         class="process-block__image">
                                </span>
                                <b class="process-block__title"><?php echo $item['title']; ?></b>
                                <p><?php echo $item['info']; ?></p>
                            </div>

                        </li>
                        <?php $i++; endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </section>

    <section class="home__products">
        <div class="container">
            <h2 class="home__section-title"><?php echo $home_products['title']; ?></h2>
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
            if ($featured->have_posts()): ?>
                <ul id="product-slider" class="home__products-list">
                    <?php while ($featured->have_posts()):
                        $featured->the_post();
                        get_template_part('template-parts/product', 'card');
                    endwhile; ?>
                </ul>
            <?php endif; ?>

        </div>
    </section>

    <?php if ($home_content['content']): ?>
    <section class="home__seo-text">
        <!--<div class="home__seo-text__background"></div>-->
        <div class="container">
            <div class="text-block">
                <div class="text-block__content"> <?php echo $home_content['content']; ?></div>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php
get_footer();
?>
