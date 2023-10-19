<?php
include '../config/conexion.php';
// Obtener el valor de idEspacio de la solicitud AJAX
$idEspacio = $_POST['idEspacio'];

// Consulta SQL para obtener los horarios de la tabla "horarios" donde "idCatalogo" sea igual a $idEspacio
$sql = "SELECT horario FROM horarios WHERE idCatalogo = $idEspacio";
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    $horarios = array();

    // Obtener los horarios de la base de datos
    while ($row = $result->fetch_assoc()) {
        $horarios[] = $row['horario'];
    }

    // Convierte los horarios a formato JSON y envíalos de vuelta a JavaScript
    echo json_encode($horarios);
} else {
    echo "No se encontraron horarios para el espacio con ID: $idEspacio";
}

// Cierra la conexión a la base de datos
$conn->close();
?>
