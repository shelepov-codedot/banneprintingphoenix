<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package BPSD
 */

get_header();
?>
<div class="error-page container">
    <img class="error-page_img" src="<?php echo get_template_directory_uri() ?>/assets/img/icons/Production.png">
    <h1 class="error-page_title">
        Oops! That page can't be found.
    </h1>
    <a href="/"><button class="error-page_button">HOME PAGE</button></a>

</div>



<?php
get_footer();