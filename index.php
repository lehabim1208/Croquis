<?php
// Iniciar la sesión (si aún no está iniciada)
session_start();

// Verificar si hay una sesión activa
if (isset($_SESSION['idUsuario'])) {
    $rolUsuario = $_SESSION['rol']; // Obtén el valor del usuario desde la sesión

    // Verificar si es administrador
    if ($rolUsuario == 'administrador') {
        // La sesión está activa y el usuario es un administrador, redirige al administrador a una página específica
        header("Location: /paginas/index.php");
        exit();
    } elseif ($rolUsuario == 'usuario') {
        // La sesión está activa y el usuario es un usuario normal, redirige al usuario a una página específica
        header("Location: /paginas/index_usuario.php");
        exit();
    } else {
        // Si no se reconoce el tipo de usuario, puedes redirigir a una página de error o realizar alguna otra acción
        header("Location: /paginas/error-403.html");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="estilos/inicio.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link  rel="icon" href="" type="image/png"/>
    <title>Reservaciones</title>
</head>
<body>
    <div class="wrapper fadeInDown">
        <div id="formContent">
          <!-- Cabecera -->
          <h1 class="active"> Bienvenido </h1>
          <h3>Reservaciones UV</h3>

         <!-- Icono -->
          <div class="fadeIn first">
              <img src="img/calendario.svg" id="icon" alt="User Icon" style="width: 100px; padding-bottom:10px;"/>
          </div>

            <a href="login.html"><input type="submit" class="fadeIn fourth" value="Iniciar sesión" style="cursor: pointer;"></a>
            <a href="registro.html"><input type="submit" class="fadeIn fourth" value="Registrarse" style="cursor: pointer;"></a>
        </div>
      </div>
</body>
</html>
