<?php
/* Template Name: Text page */
get_header();
?>
<main class="text-page">
    <div class="container">
        <h1 class="text-page__title"><?php the_title(); ?></h1>
        <?php the_content(); ?>
    </div>
</main>

<?php
get_footer();
?>