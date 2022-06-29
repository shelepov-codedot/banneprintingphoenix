<?php


add_action('wp_ajax_nopriv_subscription', 'save_subscriber');
add_action('wp_ajax_subscription', 'save_subscriber' );
if( !function_exists('save_subscriber') ):
    function save_subscriber(){

        if(isset($_POST['email'])){

            $id=generateRandomString();


            $post_data = array(
                'post_title'    => 'Subscriber_'. $id,
                'post_type'     => 'subscriber',
                'post_name' => $id,
                'post_status'   => 'publish',
                'post_author'   => 1
            );

            $post_id = wp_insert_post( $post_data );
            update_field('sub_info_email', $_POST['email'], $post_id);
        }
        echo json_encode('Form sent, thanks');

        die();
    }
endif;



function generateRandomString($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

add_action('admin_menu', 'subscriber_create_menu');

function subscriber_create_menu() {
    add_menu_page('Subscriber', 'Subscriber', 'administrator','all_subscriber', 'all_subscribers','',0);
}

function all_subscribers(){
    ?>
    <div style="margin-top: 50px">
        <button id="subscriber" class="button button-primary button-large">Export subscribers</button>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('#subscriber').on('click',function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        'action': 'export_subscribers',
                    },
                    success: function(data) {
                        if(data){
                            window.location.href =JSON.parse(data);
                        }

                    }
                });

            })
        });
    </script>
<?php

}


add_action('wp_ajax_export_subscribers', 'export_subscribers' );
if( !function_exists('export_subscribers') ):
    function export_subscribers(){
        include_once(get_template_directory() ."/lib/excel-parser/xlsxwriter/XLSXWriter.php");

        $args = array( 'post_type' => 'subscriber', 'posts_per_page' => -1);
        $donors = get_posts($args);
        $data1=array();
        $header = array(
            'Email'=>'string',
        );
        if ($donors):
            foreach ($donors as $item):
                $item_id = $item->ID;
                $email = get_field('sub_info_email', $item_id);
                array_push($data1, array(
                    $email,
                ));
            endforeach;
        endif;


        $writer = new XLSXWriter();
        $writer->setAuthor('BPSD');
        $writer->writeSheet($data1,'Subscribers',$header);
        $writer->writeToFile('subscribers.xlsx');
        echo json_encode(get_admin_url() . '/subscribers.xlsx');
        die();

    }
endif;