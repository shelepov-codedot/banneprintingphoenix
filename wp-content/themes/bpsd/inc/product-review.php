<?php


add_action( 'wp_ajax_product_review', 'add_product_review' );
add_action( 'wp_ajax_nopriv_product_review', 'add_product_review' );
if ( ! function_exists( 'add_product_review' ) ):
    function add_product_review() {


        $time = current_time('mysql');
//        $current_user = wp_get_current_user();
        $info=array();
        parse_str($_POST['data'],  $info);

        $data = array(
            'comment_post_ID' => $info['product_id'],
            'comment_author' => $info['name'],
//            'comment_author_email' => 'andriy.zionoviev@gmail.com',
//            'comment_author_url' => 'http://www.xyz.com',
            'comment_content' =>  $info['text'],
            'comment_type' => '',
            'comment_parent' => 0,
//            'user_id' => $current_user->ID,
            'comment_date' => $time,
            'comment_approved' => 0,
        );

$comment_id =   wp_insert_comment($data);
        update_comment_meta( $comment_id, 'review_theme', $info['theme']  );
        update_comment_meta( $comment_id, 'rating', $info['rating'] );

        echo json_encode(    $comment_id);
        die();
    }

endif;