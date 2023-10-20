<?php
session_start(); // Iniciar la sesión

include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta la base de datos para obtener el hash de contraseña correspondiente al correo proporcionado
    $sql = "SELECT idUsuario, nombre, password, rol FROM usuario WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // Enlazar el parámetro
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($idUsuario, $nombre, $dbPassword, $rol);
        $stmt->fetch();

        if (password_verify($password, $dbPassword)) {
            // Las credenciales son válidas
            $_SESSION['idUsuario'] = $idUsuario;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['rol'] = $rol; 
            
            if ($rol === 'usuario') {
                // Redirigir al usuario a la página de usuario si es "usuario"
                echo "user_success";
            } else {
                // Redirigir al usuario a la página de administrador o a cualquier otro lugar si no es "usuario"
                echo "admin_success";
            }
        } else {
            // Credenciales incorrectas
            echo "error"; // Esto se enviará de vuelta al cliente si las credenciales son incorrectas
        }
    } else {
        // Usuario no encontrado
        echo "error"; // Esto se enviará de vuelta al cliente si las credenciales son incorrectas
    }
    
    // Cerrar la conexión a la base de datos
    $stmt->close();
    $conn->close();
}
?>