<?php
include "../config/conexion.php";
// Recibe los datos JSON
$data = json_decode(file_get_contents("php://input"));

if (isset($data->idReserva)) {
    $idReservas = explode(',', $data->idReserva); // Divide la cadena en un array de IDs
    
    $deletedCount = 0; // Contador de reservas eliminadas con éxito
    $deletedReportCount = 0; // Contador de registros de reportes eliminados con éxito
    
    foreach ($idReservas as $idReserva) {
        // Realiza la eliminación en la base de datos
        $sql = "DELETE FROM reserva WHERE idReserva = $idReserva";
        
        if ($conn->query($sql) === TRUE) {
            $deletedCount++;
        }
        
        // Verifica si el ID existe en la tabla "reportes" y elimina los registros coincidentes
        $sql = "DELETE FROM reportes WHERE idReserva = $idReserva";
        
        if ($conn->query($sql) === TRUE) {
            $deletedReportCount++;
        }
    }
    
    $responseMessage = '';
    
    if ($deletedCount > 0 || $deletedReportCount > 0) {
        $response = ["status" => "success", "message" => $responseMessage];
    } else {
        $response = ["status" => "error", "message" => "Error al eliminar las reservas"];
    }
} else {
    $response = ["status" => "error", "message" => "Faltan datos para eliminar la reserva"];
}

// Devuelve una respuesta en formato JSON
header("Content-Type: application/json");
echo json_encode($response);

$conn->close();
?>