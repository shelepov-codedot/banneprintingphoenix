<?php
if (isset ($_POST['email']) && isset($_POST['name'])) {
    $to       = "contact@bannerprintingphoenix.com";
    $from     = $_POST['email'];
    $subject  = "Completed contact form with https://bannerprintingphoenix.com/";
    $message  = "Name: ".$_POST['name']."\nEmail: ".$from."\nType: ".$_POST['type']."\nMessage: ".$_POST['message'];
    $boundary = md5(date('r', time()));
    $filesize = '';
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
    $message="
Content-Type: multipart/mixed; boundary=\"$boundary\"

--$boundary
Content-Type: text/plain; charset=\"utf-8\"
Content-Transfer-Encoding: 7bit

$message";
    for($i=0;$i<count($_FILES['uploaded_file']['name']);$i++) {
        if(is_uploaded_file($_FILES['uploaded_file']['tmp_name'][$i])) {
            $attachment = chunk_split(base64_encode(file_get_contents($_FILES['uploaded_file']['tmp_name'][$i])));
            $filename = $_FILES['uploaded_file']['name'][$i];
            $filetype = $_FILES['uploaded_file']['type'][$i];
            $filesize += $_FILES['uploaded_file']['size'][$i];
            $message.="

--$boundary
Content-Type: \"$filetype\"; name=\"$filename\"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename=\"$filename\"

$attachment";
        }
    }
    $message.="
--$boundary--";

    if ($filesize < 10000000) {
        mail($to, $subject, $message, $headers);
        echo 'ok';
    } else {
        echo 'Sorry, the email was not sent. All files are oversized.';
    }
}