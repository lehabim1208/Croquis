<?php
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $contrasena = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashear la contraseña
    $correo = $_POST['correo'];
    $celular = $_POST['celular'];
    $rol = "usuario";

    // Verificar si el correo ya existe en la base de datos
    $sqlCorreo = "SELECT COUNT(*) FROM usuario WHERE correo = ?";
    $stmtCorreo = $conn->prepare($sqlCorreo);
    $stmtCorreo->bind_param("s", $correo);
    $stmtCorreo->execute();
    $stmtCorreo->bind_result($correoCount);
    $stmtCorreo->fetch();
    $stmtCorreo->close(); // Cerrar la consulta preparada

    // Verificar si el celular ya existe en la base de datos
    $sqlCelular = "SELECT COUNT(*) FROM usuario WHERE celular = ?";
    $stmtCelular = $conn->prepare($sqlCelular);
    $stmtCelular->bind_param("s", $celular);
    $stmtCelular->execute();
    $stmtCelular->bind_result($celularCount);
    $stmtCelular->fetch();
    $stmtCelular->close(); // Cerrar la consulta preparada

    if ($correoCount > 0) {
        echo "Ya existe una cuenta con este correo.";
        sleep(3);
        // Realizar la redirección
        header("Location: ../login.html");
        exit;
    } elseif ($celularCount > 0) {
        echo "Ya existe una cuenta con este número de celular.";
        sleep(3);
        // Realizar la redirección
        header("Location: ../login.html");
        exit;
    } else {
        // Realizar la inserción en la base de datos
        $sqlInsert = "INSERT INTO usuario (nombre, password, correo, celular, rol) 
                    VALUES (?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("sssss", $nombre, $contrasena, $correo, $celular, $rol);

        if ($stmtInsert->execute()) {
            echo "Registro exitoso";
            header("Location: ../login.html");
            exit;
        } else {
            echo "Error al registrar el usuario: " . $stmtInsert->error;
        }
    }
}
?>