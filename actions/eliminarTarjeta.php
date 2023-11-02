<?php
include "../config/conexion.php";

// Recibe los datos JSON
$data = json_decode(file_get_contents("php://input"));

if (isset($data->idReserva)) {
    $idReserva = $data->idReserva;

    // Obtener la información de la tarjeta antes de eliminarla
    $sqlTarjeta = "SELECT * FROM reserva WHERE idReserva = $idReserva";
    $resultTarjeta = $conn->query($sqlTarjeta);

    if ($resultTarjeta->num_rows > 0) {
        $tarjeta = $resultTarjeta->fetch_assoc();
        
        // Eliminar todas las reservas asociadas a la tarjeta
        $idCliente = $tarjeta['id_Cliente'];
        $idCatalogo = $tarjeta['idCatalogo'];
        $fecha = $tarjeta['fecha'];
        $horaMin = $tarjeta['horaMin'];
        $horaMax = $tarjeta['horaMax'];

        $sqlEliminarReservas = "DELETE FROM reserva WHERE id_Cliente = $idCliente AND idCatalogo = $idCatalogo AND fecha = '$fecha' AND horaMin = '$horaMin' AND horaMax = '$horaMax'";
        if ($conn->query($sqlEliminarReservas) === TRUE) {
            // Eliminación exitosa
            $response = ["status" => "success", "message" => "Tarjeta y sus reservas eliminadas con éxito"];
        } else {
            $response = ["status" => "error", "message" => "Error al eliminar la tarjeta y sus reservas: " . $conn->error];
        }
    } else {
        $response = ["status" => "error", "message" => "No se encontró la tarjeta a eliminar"];
    }
} else {
    $response = ["status" => "error", "message" => "Faltan datos para eliminar la tarjeta"];
}

// Devuelve una respuesta en formato JSON
header("Content-Type: application/json");
echo json_encode($response);

$conn->close();
?>