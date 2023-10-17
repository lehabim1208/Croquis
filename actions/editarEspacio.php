<?php
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera los datos enviados por POST
    $idEspacio = $_POST['idEspacio'];
    $nombreEspacio = $_POST['nombreEspacio'];
    $capacidadEspacio = $_POST['capacidadEspacio'];
    $nombreEdificio = $_POST['nombreEdificio']; // Asegúrate de que coincida con el nombre de la columna real
    $zona = $_POST['zona'];

    // Realiza la consulta para actualizar los datos en la base de datos
    $sql = "UPDATE catalogo SET nombre = ?, capacidad = ?, numeroEdificio = ?, zona = ? WHERE idCatalogo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nombreEspacio, $capacidadEspacio, $nombreEdificio, $zona, $idEspacio);

    if ($stmt->execute()) {
        // Edición exitosa
        echo "Edición exitosa.";
    } else {
        // Error en la edición
        echo "Error al editar el espacio: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Método de solicitud incorrecto.";
}
?>