<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="estilos/login.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link  rel="icon" href="" type="image/png"/>
    <title>Reservaciones</title>
</head>
<body>
    <div class="wrapper fadeInDown">
        <div id="formContent">
          <!-- Cabecera -->
          <h2 class="active">Registro</h2>
      
          <!-- Icono -->
          <div class="fadeIn first">
            <img src="https://i.ibb.co/kHsQsMX/user-5-svgrepo-com.png" id="icon" alt="User Icon" style="width: 100px;"/>
          </div>
      
          <!-- Inicio de sesión -->
          <form action="#" id="formulario">
            <input type="text" class="fadeIn second" name="login" placeholder="Nombre" required>
            <input type="text" id="login" class="fadeIn second" name="login" placeholder="Usuario" required>
            <input type="text" id="password" class="fadeIn third" name="login" placeholder="Contraseña" required>
            <input type="text"  class="fadeIn second" name="login" placeholder="Correo" required>
            <input type="text"  class="fadeIn second" name="login" placeholder="Celular" required>
            <input type="text"  class="fadeIn second" name="login" placeholder="Rol" required>
            <input type="submit" class="fadeIn fourth" value="Registrarse">
          </form>
      
          <!-- Olvidé contraseña -->
          <div id="formFooter">
            <a class="underlineHover" href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
          </div>
      
        </div>
      </div>
</body>
</html>