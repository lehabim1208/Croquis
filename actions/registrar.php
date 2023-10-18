<?php
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $contrasena = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashear la contraseña
    $correo = $_POST['correo'];
    $celular = $_POST['celular'];
    $rol = "usuario";

    // Realizar la inserción en la base de datos
    $sql = "INSERT INTO usuario (nombre, password, correo, celular, rol) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $contrasena, $correo, $celular, $rol);

    if ($stmt->execute()) {
        echo "Registro exitoso";
        header("Location: ../login.html");
        exit;
    } else {
        echo "Error al registrar el usuario: " . $stmt->error;
    }
}
?>