<?php
include '../config/conexion.php';

// Acceder a los datos JSON enviados por la solicitud POST
$data = json_decode(file_get_contents('php://input'));

// Acceder a las propiedades enviadas desde JavaScript
$horariosSeleccionados = $data->horariosSeleccionados;
$idCatalogo = $data->idEspacio;
$idReserva = $data->idReserva;
$fecha = $data->fecha;
$id_cliente = $data->selectedUserId;
$response = array();


// Función para convertir un rango de horas de 12 horas a 24 horas
function convertirHora12a24($horario)
{
    // Dividir el rango de horas en dos partes
    $horasSeparadas = explode("-", $horario);

    // Obtener las horas individuales
    $horaInicio = trim($horasSeparadas[0]);
    $horaFin = trim($horasSeparadas[1]);

    // Convertir a formato de 24 horas
    $horaMin = date("H:i:s", strtotime($horaInicio));
    $horaMax = date("H:i:s", strtotime($horaFin));

    return array('horaMin' => $horaMin, 'horaMax' => $horaMax);
}

//ELIMINA PRIMERO LAS RESERVACIONES QUE YA ESTABAN
$idReservas = explode(',', $idReserva); // Divide la cadena en un array de IDs

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
//CREA NUEVA RESERVACION Y RESERVACIONES
// Iterar sobre los horarios seleccionados y realizar la inserción
foreach ($horariosSeleccionados as $horario) {
    // Convertir el horario de 12 horas a formato de tiempo de MySQL
    $horario24 = convertirHora12a24($horario);

    // Consulta
    $sql = "INSERT INTO reserva(idCatalogo, horaMax, horaMin, horario, fecha, id_cliente) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssi", $idCatalogo, $horario24['horaMax'], $horario24['horaMin'], $horario, $fecha, $id_cliente);

    if ($stmt->execute()) {
        $response[] = array(
            'horario' => $horario,
            'message' => 'Edición exitosa. '
        );
    } else {
        $response[] = array(
            'horario' => $horario,
            'message' => 'Error en la edición'
        );
    }
}

// Devuelve una respuesta JSON con el resultado de todas las inserciones
echo json_encode($response);
?>