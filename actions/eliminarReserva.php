<?php
include "../config/conexion.php";
// Recibe los datos JSON
$data = json_decode(file_get_contents("php://input"));

if (isset($data->idReserva)) {
    $idReserva = $data->idReserva;
    
    // Realiza la eliminación en la base de datos
    $sql = "DELETE FROM reserva WHERE idReserva = $idReserva";
    
    if ($conn->query($sql) === TRUE) {
        $response = ["status" => "success", "message" => "Reserva eliminada con éxito"];
    } else {
        $response = ["status" => "error", "message" => "Error al eliminar la reserva: " . $conn->error];
    }
} else {
    $response = ["status" => "error", "message" => "Faltan datos para eliminar la reserva"];
}

// Devuelve una respuesta en formato JSON
header("Content-Type: application/json");
echo json_encode($response);

$conn->close();
?>
