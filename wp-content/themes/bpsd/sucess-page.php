<?php
/* Template Name: success page */
get_header();
?>

<div class="success-page container">
    <img class="success-page_img"
        src="<?php echo get_template_directory_uri() ?>/assets/img/icons/success-page.png">
    <h1 class="success-page_title">
        Success! Thank you for order.
    </h1>
    <a href="/"><button class="success-page_button">HOME PAGE</button></a>

</div>



<?php
get_footer();