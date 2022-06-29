<?php
/**
 * Order Notes
 *
 * @package WooCommerce\Admin\Meta Boxes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WC_Meta_Box_Order_Notes Class.
 */
class WC_Meta_Box_Order_Members_Chat {

    /**
     * Output the metabox.
     *
     * @param WP_Post $post Post object.
     */
    public static function output( $post ) {
        global $post;
        global $wpdb;

        $order_id = $post->ID;
        $users = $wpdb->get_results("SELECT u.`user_nicename`, u.`user_email` FROM `wp_users` u LEFT JOIN `wp_usermeta` um ON u.`ID`=um.`user_id` WHERE um.`meta_key`='wp_capabilities' AND um.`meta_value` NOT LIKE  '%user%' AND um.`meta_value` NOT LIKE  '%customer%'");
        $comments = $wpdb->get_results("SELECT * FROM `wp_codedot_comments` WHERE `comment_post_ID`='$order_id' ORDER BY `comment_date` DESC");
//      $post->ID
?>
        <style>
            #messages {
                width: 100%;
                height: 100%;
                min-height: 200px;
            }

            .order_members_chat li {
                border-bottom: 1px solid #ccd0d4;
            }
            .order_members_chat li:last-child {
                border-bottom: none;
            }

            .order_members_chat .note_content {
                padding: 5px;
                background: #efefef;
            }

            .order_members_chat .note_content p,
            .order_members_chat .note_content a{
                word-break: break-word;
                margin: 0;
            }

            .members,
            .files {
                display: flex;
                gap: 5px;
                margin-top: 10px;
            }

            .members__emails,
            .files__list {
                margin: 0;
                word-break: break-word;
            }

            .meta {
                display: flex;
                justify-content: space-between;
                margin: 10px 0;
            }

            .delete_note {
                color: #ff0000;
                cursor: pointer;
            }
        </style>

        <ul class="order_members_chat">
            <?php
                foreach ($comments as $comment) {
                    if (stristr($comment->comment_content, 'https')) {
                        echo '
                        <li class="note">
                            <div class="note_content">
                                <p>'.$comment_content = preg_replace('/\b(https?:\/\/[\S]+)/si', '<a target="_blank" href="$1">$1</a>', htmlspecialchars($comment->comment_content)).'</p>
                            </div>';
                    } else {
                        echo '
                        <li class="note">
                            <div class="note_content">
                                <p>'.$comment->comment_content.'</p>
                            </div>';
                    }

                    $members = json_decode($comment->comment_members, true);
                    if ($members) {
                        echo '<div class="members">
                            <span class="members__title">Mentioned:</span>
                            <p class="members__emails">' . implode(',', $members) . '</p>
                        </div>';
                    }

                    $files = json_decode($comment->comment_files, true);
                    $files_hash = json_decode($comment->comment_files_hash, true);
                    if ($files) {
                        echo '<div class="files">
                            <span class="files__title">Files:</span>
                            <p class="files__list">';
                        foreach ($files as $k => $file) {
                            if ($k !== 0) echo ', ';

                            if ($files_hash) {
                                foreach ($files_hash as $file_hash) {
                                    echo '<a href="' . site_url('/order_note_files/' . $order_id . '/' . $file_hash) . '" download>' . $file . '</a>';
                                }
                            }
                        }
                        echo '</p>
                        </div>';
                    }

                    echo '<div class="meta">
                            <abbr class="exact-date">'.$comment->comment_date.'</abbr>
                            <a data-note-id='.$comment->comment_ID.' class="delete_member_note">Delete</a>
                        </div>
                    </li>';
                }
            ?>
        </ul>

        <div class="add_note">
            <p>
                <textarea type="text" id="messages" class="input-text"></textarea>
                <select id="members" multiple>
                    <?php
                    foreach ($users as $user) {
                    ?>
                    <option value="<?= $user->user_email ?>"><?= $user->user_nicename ?></option>
                    <?php
                    }
                    ?>
                </select>
                <input type="file" id="documents" multiple>
            </p>
            <p>
                <button type="button" class="add_members_chat button"><?php esc_html_e( 'Add', 'woocommerce' ); ?></button>
            </p>
        </div>
<?php
    }
}
