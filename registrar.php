<?php
include 'config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $contrasena = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashear la contraseña
    $correo = $_POST['correo'];
    $celular = $_POST['celular'];
    $rol = $_POST['rol'];

    // Realizar la inserción en la base de datos
    $sql = "INSERT INTO usuario (nombre, idUsuario, password, correo, celular, rol) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nombre, $usuario, $contrasena, $correo, $celular, $rol);

    if ($stmt->execute()) {
        echo "Registro exitoso";
        header("Location: login.html");
        exit;
    } else {
        echo "Error al registrar el usuario: " . $stmt->error;
    }
}
?>