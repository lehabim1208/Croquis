<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../estilos/home2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="../imagenes/logo.png" type="image/png" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Actualiza la referencia a Bootstrap CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Actualiza la referencia a jQuery -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- Actualiza la referencia a Bootstrap JS -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservaciones</title>
</head>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark text-white"> <!-- Utiliza las clases de Bootstrap para un fondo oscuro y texto blanco -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php if ($_SESSION['rol'] == 'administrador'): ?>
                    <li class="nav-item <?=($currentPage === 'inicio') ? 'active' : ''; ?>">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item <?=($currentPage === 'espacios') ? 'active' : ''; ?>">
                        <a class="nav-link" href="home.php">Espacios y Consultas</a>
                    </li>
                    <li class="nav-item <?=($currentPage === 'usuarios') ? 'active' : ''; ?>">
                        <a class="nav-link" href="usuarios.php">Usuarios</a>
                    </li>
                    <li class="nav-item <?=($currentPage === 'horarios') ? 'active' : ''; ?>">
                        <a class="nav-link" href="horarios.php">Horarios</a>
                    </li>
                    <li class="nav-item <?=($currentPage === 'consultas') ? 'active' : ''; ?>">
                        <a class="nav-link" href="consultar.php">Reservaciones</a>
                    </li>
                    <li class="nav-item <?=($currentPage === 'reportes') ? 'active' : ''; ?>">
                        <a class="nav-link" href="reportes.php">Reportes</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item <?=($currentPage === 'inicio') ? 'active' : ''; ?>">
                        <a class="nav-link" href="index_usuario.php">Inicio</a>
                    </li>
                    <li class="nav-item <?=($currentPage === 'espacios') ? 'active' : ''; ?>">
                        <a class="nav-link" href="home_usuario.php">Espacios y Consultas</a>
                    </li>
                    <li class="nav-item <?=($currentPage === 'consultas') ? 'active' : ''; ?>">
                        <a class="nav-link" href="consultar_usuario.php">Mis reservas</a>
                    </li>
                <?php endif; ?>
            </ul>                   
        </div>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="btn btn-danger" href="../actions/logout.php" type="button">Cerrar Sesión</a>
            </li>
        </ul>
    </nav>
</header>