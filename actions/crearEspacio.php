<?php
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombreEspacio'];
    $capacidad = $_POST['capacidadEspacio'];

    $sql = "INSERT INTO catalogo (nombre, capacidad) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nombre, $capacidad);

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
