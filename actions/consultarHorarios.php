<?php 
include '../config/conexion.php';

// Obtener el valor de idEspacio y fecha de la solicitud AJAX
$idEspacio = $_POST['idEspacio'];
$fecha = $_POST['fecha'];

// Consulta SQL para obtener los horarios de la tabla "horarios" donde "idCatalogo" sea igual a $idEspacio
$sql = "SELECT horario FROM horarios WHERE idCatalogo = $idEspacio";
$result = $conn->query($sql);

// Consulta SQL para obtener los horarios ocupados en la tabla "reserva" para la fecha dada
$sqlreservas = "SELECT horario FROM reserva WHERE DATE(fecha) = DATE('$fecha')";
$resultreservas = $conn->query($sqlreservas);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    $horarios = array();

    // Obtener los horarios de la base de datos
    while ($row = $result->fetch_assoc()) {
        $horarios[] = $row['horario'];
    }

    // Obtener los horarios ocupados de la tabla "reserva"
    $horariosOcupados = array();
    while ($row = $resultreservas->fetch_assoc()) {
        $horariosOcupados[] = $row['horario'];
    }

    // Calcular la diferencia entre los horarios disponibles y los horarios ocupados
    $horariosDisponibles = array_diff($horarios, $horariosOcupados);

    // Convierte los horarios disponibles a formato JSON y envíalos de vuelta a JavaScript
    echo json_encode(array_values($horariosDisponibles));
} else {
    echo "No se encontraron horarios para el espacio con ID: $idEspacio";
}

// Cierra la conexión a la base de datos
$conn->close();
?>