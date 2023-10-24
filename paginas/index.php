<?php 
include '../config/conexion.php'; 
session_start();

$currentPage = 'inicio';

// Verificar si la variable de sesión 'idUsuario' está definida
if (!isset($_SESSION['idUsuario']) || $_SESSION['rol'] !== 'administrador') {
    // El usuario no ha iniciado sesión o no tiene el rol de "administrador", redirigir a la página de acceso denegado
    header('Location: error-403.html');
    exit();
}
?>
<body>
<?php include 'header.php'; ?>

    <div class="jumbotron">
        <h1 class="display-4">¡Bienvenido <?php echo $_SESSION['nombre']; ?>!</h1>
        <p class="lead">Hoy es un buen día para chambear :)</p>
        <hr class="my-4">
        <p>Consulte en el botón azul la disponibilidad de espacios</p>
        <a class="btn btn-primary btn-lg" href="home.php" role="button">Disponibilidad!</a>
    </div>

    <?php include 'footer.html'; ?>

</body>
</html>