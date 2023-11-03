<?php
include '../config/conexion.php';

// Obtén los datos del formulario y los adicionales
$idUsuario = $_POST['idUsuario'];
$asuntoAdmin = $_POST['asuntoAdmin'];
$mensajeAdmin = $_POST['mensajeAdmin'];
$idReporte = $_POST['idReporte']; // Datos adicionales del botón
$asuntoUsuario = $_POST['asuntoUsuario']; // Datos adicionales del botón
$descripcionUsuario = $_POST['descripcionUsuario']; // Datos adicionales del botón
$estado = $_POST['estado']; // Datos adicionales del botón
$fechaHora = $_POST['fechaHora']; // Datos adicionales del botón
$idReserva = $_POST['idReserva']; // Datos adicionales del botón
$idCliente = $_POST['idCliente']; // Datos adicionales del botón

// Haz lo que necesites con estos datos
// Por ejemplo, envía el correo o realiza otras operaciones

// Devuelve una respuesta JSON para indicar el éxito o el error
$response = array('success' => true); // O false en caso de error
echo json_encode($response);
?>