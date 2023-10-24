<?php
// Incluir el archivo de conexión a la base de datos
include('../config/conexion.php');

// Consulta para obtener los usuarios con rol "usuario"
$sql = "SELECT idUsuario, nombre, rol FROM usuario";
$resultado = $conn->query($sql);

if ($resultado) {
    $usuarios = array();
    while ($fila = $resultado->fetch_assoc()) {
        $usuarios[] = $fila;
    }

    // Devolver los datos en formato JSON
    echo json_encode($usuarios);
} else {
    // Manejar el error si la consulta falla
    echo json_encode(['error' => 'Error en la consulta']);
}

// Cerrar el resultado
$resultado->close();

// Cerrar la conexión a la base de datos
$conn->close();
