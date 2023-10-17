<?php
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombreEspacio'];
    $capacidad = $_POST['capacidadEspacio'];
    $nombreEdificio = $_POST['nombreEdificio'];
    $zonaRegion = $_POST['zonaRegion'];

    $sql = "INSERT INTO catalogo (nombre, capacidad, numeroEdificio, zona) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $nombre, $capacidad, $nombreEdificio, $zonaRegion);

    if ($stmt->execute()) {
        // Inserción exitosa
        echo "Registro exitoso.";
        header("Location: ../paginas/home.php");
        exit;
    } else {
        // Error en la inserción
        echo "Error al registrar los datos: " . $stmt->error;
    }
    $stmt->close();
}
?>
