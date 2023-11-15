<?php
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $contrasena = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $correo = $_POST['correo'];
    $celular = $_POST['celular'];
    $rol = "usuario";

    $sqlCorreo = "SELECT COUNT(*) FROM usuario WHERE correo = ?";
    $stmtCorreo = $conn->prepare($sqlCorreo);
    $stmtCorreo->bind_param("s", $correo);
    $stmtCorreo->execute();
    $stmtCorreo->bind_result($correoCount);
    $stmtCorreo->fetch();
    $stmtCorreo->close();

    $sqlCelular = "SELECT COUNT(*) FROM usuario WHERE celular = ?";
    $stmtCelular = $conn->prepare($sqlCelular);
    $stmtCelular->bind_param("s", $celular);
    $stmtCelular->execute();
    $stmtCelular->bind_result($celularCount);
    $stmtCelular->fetch();
    $stmtCelular->close();

    if ($correoCount > 0) {
        echo "Ya existe una cuenta con este correo.";
        sleep(3);
        header("Location: ../login.html");
        exit;
    } elseif ($celularCount > 0) {
        echo "Ya existe una cuenta con este número de celular.";
        sleep(3);
        header("Location: ../login.html");
        exit;
    } else {
        $sqlInsert = "INSERT INTO usuario (nombre, password, correo, celular, rol) 
                    VALUES (?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("sssss", $nombre, $contrasena, $correo, $celular, $rol);

        if ($stmtInsert->execute()) {
            $response = array(
                'status' => 'success',
                'message' => 'Cuenta creada correctamente'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            echo "Error al registrar el usuario: " . $stmtInsert->error;
        }
    }
}
?>