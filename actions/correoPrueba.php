<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'lehabimgroup2@gmail.com';                     //SMTP username
    $mail->Password   = 'twjjxpufmgnftfxr';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('lehabimgroup2@gmail.com', 'Sistema Reservaciones');
    $mail->addAddress('lehabimgroup@gmail.com', 'Lehabim');     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'PRUEBA DE CORREO 1';
    $mail->Body    = 'PRUEBA DE CORREO';
    $mail->AltBody = 'Prueba 3';

    $mail->CharSet = 'UTF-8';
    $mail->send();
    echo 'Correo enviado';
} catch (Exception $e) {
    echo "No se envió el correo: {$mail->ErrorInfo}";
}