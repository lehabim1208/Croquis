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
    $mail->Body = '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
                margin: 0;
                padding: 20px;
            }
            .container {
                background-color: #5b5d5f;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            h2 {
                color: #333;
            }
            .info {
                margin-top: 20px;
            }
            .info p {
                margin: 0;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Detalles del Reporte:</h2>
            <div class="info">
                <p><strong>ID del Reporte:</strong> ' . $idReporte . '</p>
                <p><strong>Asunto del Usuario:</strong> ' . $asuntoUsuario . '</p>
                <p><strong>Descripción del Usuario:</strong> ' . $descripcionUsuario . '</p>
                <p><strong>Estado:</strong> ' . $estado . '</p>
                <p><strong>Fecha y Hora:</strong> ' . $fechaHora . '</p>
                <p><strong>ID de Reserva:</strong> ' . $idReserva . '</p>
                <p><strong>ID de Cliente:</strong> ' . $idCliente . '</p>
                <span>________________________</span>
                <br><br>
                <h2>Mensaje del administrador:</h2>
                <p><strong>Mensaje:</strong><br>' . $mensajeAdmin . '</p>
            </div>
        </div>
    </body>
    </html>
    ';

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