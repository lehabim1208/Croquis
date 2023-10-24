<?php
include '../config/conexion.php';
// Acceder a los datos JSON enviados por la solicitud POST
$data = json_decode(file_get_contents('php://input'));

// Acceder a la propiedad 'horariosSeleccionados' del objeto JSON
$horariosSeleccionados = $data->horariosSeleccionados;
$idCatalogo = 13;
$horaMax = 10;
$horaMin = 11;
$fecha ="2023-11-24";
$id_cliente = 2;


// Consulta
$sql = "INSERT INTO reserva(idCatalogo, horaMax, horaMin, horario, fecha, id_cliente) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiissi", $idCatalogo, $horaMax, $horaMin, $horariosSeleccionados, $fecha, $id_cliente);

if ($stmt->execute()) {
    $response = array(
        'message' => 'Reserva exitosa'
    );
    exit;
} else {
    echo "error";
}

echo json_encode($response);
?>