<?php
session_start();
$nombreAdmin = $_SESSION['nombre'];

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
    $mail->Username = 'aqui_el_correo_de_envio'; // SMTP username
    $mail->Password = 'aqui_la_contrasena'; // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
    $mail->Port = 465; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    // Recipients
    $mail->setFrom('lehabimgroup2@gmail.com', 'Sistema de Reservaciones');
    $mail->addAddress('lehabimgroup@gmail.com', 'Jefe de carrera'); // Agrega el destinatario jefe de carrera

    // Content
    $mail->isHTML(true); // Establece el formato del correo como HTML
    $mail->Subject = $asuntoAdmin; // Asunto del correo
    $mail->Body = '
    <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Comprobante de pago - Acuario Isla Tortuga</title>
            <style>
            @media only screen and (max-width: 600px) {
                .main {
                    width: 320px !important;
                }
                .top-image-2{
                    width: 50% !important;
                }
                .top-image {
                    width: 100% !important;
                }
                .inside-footer {
                    width: 320px !important;
                }
                table[class="contenttable"] { 
                    width: 320px !important;
                    text-align: left !important;
                }
                td[class="force-col"] {
                    display: block !important;
                }
                 td[class="rm-col"] {
                    display: none !important;
                }
                .mt {
                    margin-top: 15px !important;
                }
                *[class].width300 {width: 255px !important;}
                *[class].block {display:block !important;}
                *[class].blockcol {display:none !important;}
                .emailButton{
                    width: 100% !important;
                }
        
                .emailButton a {
                    display:block !important;
                    font-size:18px !important;
                }
        
            }
            </style>
        </head>
  <body link="#00a5b5" vlink="#00a5b5" alink="#00a5b5">
        <table class=" main contenttable" align="center" style="font-weight: normal;border-collapse: collapse;border: 0;margin-left: auto;margin-right: auto;padding: 0;font-family: Arial, sans-serif;color: #555559;background-color: white;font-size: 16px;line-height: 26px;width: 600px;">
                <tr>
                    <td class="border" style="border-collapse: collapse;border: 1px solid #eeeff0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                        <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                            <tr>
                                <td colspan="4" valign="top" class="image-section" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background-color: #fff;border-bottom: 4px solid #00a5b5">
                                   <center> 
                                        <h1 style="color:#1394a0">Sistema de reservaciones</h1>
                                        <img src="https://i.ibb.co/PGqF0Lb/calendario.png" id="icon" alt="User Icon" style="width: 100px; padding-bottom:10px;"/>
                                    </center>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" class="side title" style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;vertical-align: top;background-color: white;border-top: none;">
                                    <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                                        <tr>
                                            <td class="head-title" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 28px;line-height: 34px;font-weight: bold; text-align: center;">
                                                <div style="color: rgb(148, 146, 19)"mktEditable" id="main_title">
                                                    Reporte de reservación
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="sub-title" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;padding-top:5px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 18px;line-height: 29px;font-weight: bold;text-align: center;">
                                            <div class="mktEditable" id="intro_title">
                                               <strong> Se solicita apoyo al jefe de carrera para el siguiente caso </strong>
                                            </div></td>
                                        </tr>
                                        <tr>
                                            <td class="top-padding" style="border-collapse: collapse;border: 0;margin: 0;padding: 5px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;"></td>
                                        </tr>
                                        <tr>
                                            <td class="grey-block" style="border-collapse: collapse;border: 0;margin: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background-color: #fff; text-align:center;">
                                            <div class="mktEditable" id="cta">
                                                <div class="container">
                                                    <h2>Detalles del Reporte:</h2>
                                                    <div class="info" style="text-align: left;">
                                                        <p><strong style="color: #7fd2d8;">ID del Reporte:</strong> ' . $idReporte . '</p>
                                                        <p><strong style="color: #7fd2d8;">Asunto del Usuario:</strong> ' . $asuntoUsuario . '</p>
                                                        <p><strong style="color: #7fd2d8;">Descripción del Usuario:</strong> ' . $descripcionUsuario . '</p>
                                                        <p><strong style="color: #7fd2d8;">Estado:</strong> ' . $estado . '</p>
                                                        <p><strong style="color: #7fd2d8;">Fecha y Hora:</strong> ' . $fechaHora . '</p>
                                                        <p><strong style="color: #7fd2d8;">ID de Reserva:</strong> ' . $idReserva . '</p>
                                                        <p><strong style="color: #7fd2d8;">ID de Cliente:</strong> ' . $idCliente . '</p>
                                                        <span>________________________</span>
                                                        <br><br>
                                                        <h2 style="color: #7fd2d8;">Mensaje del administrador ' . $nombreAdmin . ':</h2>
                                                        <p>' . $mensajeAdmin . '</p>
                                                    </div>
                                                </div>
                                            </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="top-padding" style="border-collapse: collapse;border: 0;margin: 0;padding: 15px 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 21px;">
                                                <hr size="1" color="#eeeff0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 24px;">
                                            &nbsp;<br>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>										
                            <tr bgcolor="#fff" style="border-top: 4px solid #00a5b5;">
                                <td valign="top" class="footer" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background: #fff;text-align: center;">
                                    <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                                        <tr>
                                            <td class="inside-footer" align="center" valign="middle" style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">
        <div id="address" class="mktEditable">
            <b>Copyright &copy; Sistema de Reservaciones UV</b><br>
        </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
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
