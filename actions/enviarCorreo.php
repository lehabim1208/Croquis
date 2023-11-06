<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

try {
    // Obtener los datos del formulario
    $idUsuario = $_POST['idUsuario'];
    $asuntoAdmin = $_POST['asuntoAdmin'];
    $mensajeAdmin = $_POST['mensajeAdmin'];
    $idReporte = $_POST['idReporte'];
    $asuntoUsuario = $_POST['asuntoUsuario'];
    $descripcionUsuario = $_POST['descripcionUsuario'];
    $estado = $_POST['estado'];
    $fechaHora = $_POST['fechaHora'];
    $idReserva = $_POST['idReserva'];
    $idCliente = $_POST['idCliente'];

    // Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    $mail->isSMTP(); // Send using SMTP
    $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'lehabimgroup2@gmail.com'; // SMTP username
    $mail->Password = 'twjjxpufmgnftfxr'; // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
    $mail->Port = 465; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    // Recipients
    $mail->setFrom('lehabimgroup2@gmail.com', 'Sistema de Reservaciones');
    $mail->addAddress('lehabimgroup@gmail.com', 'Jefe de carrera'); // Agrega el destinatario

    // Content
    $mail->isHTML(true); // Establece el formato del correo como HTML
    $mail->Subject = $asuntoAdmin; // Asunto del correo
    $mail->Body = "ID del Reporte: $idReporte <br>" .
        "Asunto del Usuario: $asuntoUsuario <br>" .
        "Descripción del Usuario: $descripcionUsuario <br>" .
        "Estado: $estado <br>" .
        "Fecha y Hora: $fechaHora <br>" .
        "ID de Reserva: $idReserva <br>" .
        "ID de Cliente: $idCliente <br>" .
        "Mensaje del Administrador:<br>$mensajeAdmin";

    $mail->CharSet = 'UTF-8';

    // Envía el correo
    $mail->send();

    // Genera la respuesta JSON en caso de éxito
    $response = array('success' => true, 'message' => 'Correo enviado con éxito');
} catch (Exception $e) {
    // Error al enviar el correo
    $response = array('success' => false, 'message' => 'Error al enviar el correo: ' . $mail->ErrorInfo);
}

// Retorna la respuesta JSON una sola vez
echo json_encode($response);