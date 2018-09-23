<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

$mail = new PHPMailer();


//$mail->SMTPDebug = 3;                               // Enable verbose debug output
//$mail->isSMTP();                                      // Set mailer to use SMTP
//$mail->Host = 'just55.justhost.com';                  // Specify main and backup SMTP servers
//$mail->SMTPAuth = true;                               // Enable SMTP authentication
//$mail->Username = 'themeforest@ismail-hossain.me';    // SMTP username
//$mail->Password = 'AsDf12**';                         // SMTP password
//$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
//$mail->Port = 465;                                    // TCP port to connect to

$status = "false";

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if( $_POST['reservation_email'] != '' AND $_POST['reservation_phone'] != '') {

        $email = $_POST['reservation_email'];
        $phone = $_POST['reservation_phone'];

        $subject = isset($subject) ? $subject : 'Новое сообщение | Записаться на пробный урок';
        $name = isset($_POST['reservation_name']) ? $_POST['reservation_name'] : '';

        $botcheck = $_POST['form_botcheck'];

        $toemail = 'nataliabasalaeva@mail.ru'; // Your Email Address
//        $toemail = 'prihodkoalesia@gmail.com'; // Your Email Address
        $toname = 'Академия Математики';                // Receiver Name

        if( $botcheck == '' ) {

            $mail->SetFrom( $email , $name );
            $mail->AddReplyTo( $email , $name );
            $mail->AddAddress( $toemail , $toname );
            $mail->Subject = $subject;

            $name = isset($name) ? "Имя: $name<br><br>" : '';
            $email = isset($email) ? "Email: $email<br><br>" : '';
            $phone = isset($phone) ? "Телефон: $phone<br><br>" : '';

            $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>Форма отправлена со страницы: ' . $_SERVER['HTTP_REFERER'] : '';

            $body = "$name $email $phone $referrer";

            $mail->MsgHTML( $body );
            $sendEmail = $mail->Send();

            if( $sendEmail == true ):
                $message = 'Ваше сообщение отправлено, мы скоро свяжемся с Вами.';
                $status = "true";
            else:
                $message = 'Сообщение не отпралено, повторите попытку позже<br /><br /><strong>Причина:</strong><br />' . $mail->ErrorInfo . '';
                $status = "false";
            endif;
        } else {
            $message = 'Bot <strong>Detected</strong>.! Clean yourself Botster.!';
            $status = "false";
        }
    }
    else {
        $message = 'Заполните все поля';
        $status = "false";
    }
} else {
    $message = 'Произошла непредвиденная ошибка. Пожалуйста, повторите попытку позже.';
    $status = "false";
}

$status_array = array( 'message' => $message, 'status' => $status);
echo json_encode($status_array);
?>