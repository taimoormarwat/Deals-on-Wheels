<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer-master/src/SMTP.php';
require __DIR__ . '/../PHPMailer-master/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'sendMail') {

        $receiver=$_POST['receiver'];
        $status=$_POST['status'];
        $title=$_POST['title'];

        $name='User';

        $sub='';
        $body='';

        if($status=='0'){
            $body='Your ad for <b>' .$title . '</b> was removed from the Listing';
            $sub=$title .' Disapproved';
        }
        if($status=='1'){
            $body='<b>Congratulations!</b><br> Your ad for <b>' .$title . '</b> was added to the Listing';
            $sub=$title .' Approved';
        }

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'dealsonwheelsmail@gmail.com';                     //SMTP username
            $mail->Password   = 'nkuqvpqnigcybdpt';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('dealsonwheelsmail@gmail.com', 'Deasl on Wheels');
            $mail->addAddress($receiver, $name);     //Add a recipient
            // $mail->addAddress('ellen@example.com');               //Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $sub;
            $mail->Body    = $body;
            $mail->AltBody = 'Mail from Deals on Wheels';

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->send();
            $response = array('status' => true, 'message' => 'Mail Sent');
            echo json_encode($response);
    } catch (Exception $e) {
            $response = array('status' => false, 'message' => $mail->ErrorInfo);
            echo json_encode($response);
        }

        // $response = array('status' => true, 'message' => 'Mail Sent');
        // echo json_encode($response);
    }
}
