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
    if( $_POST['form_phone'] != '' AND $_POST['booking_service'] != '' AND $_POST['form_name'] != '') {
//    if( $_POST['car_select'] != '') {

        $phone = $_POST['form_phone'];
        $car = $_POST['booking_service'];
        $mes = $_POST['form_message'];

        $subject = isset($subject) ? $subject : 'Новое сообщение | Записаться на пробный урок';
        $name = isset($_POST['form_name']) ? $_POST['form_name'] : '';

        $botcheck = $_POST['form_botcheck'];

        $toemail = 'prihodko@globalsolutions.ru'; // Your Email Address
        $toname = 'Академия Математики';                // Receiver Name

        if( $botcheck == '' ) {

            $mail->SetFrom(  $name );
            $mail->AddReplyTo(  $name );
            $mail->AddAddress( $toemail , $toname );
            $mail->Subject = $subject;

            $name = isset($name) ? "Имя: $name<br><br>" : '';
            $phone = isset($phone) ? "Телефон: $phone<br><br>" : '';
            $car = isset($car) ? "Курс: $car<br><br>" : '';
            $mes = isset($mes) ? "Сообщение: $mes<br><br>" : '';
            $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>Форма отправлена со страницы: ' . $_SERVER['HTTP_REFERER'] : '';

            $body = "$name $phone $car $mes $referrer";

            $mail->MsgHTML( $body );
            $sendEmail = $mail->Send();

            if( $sendEmail == true ):
                $message = 'Ваше сообщение отправлено, мы скоро свяжемся с Вами';
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