<?php
// Iniciar o reanudar la sesión
session_start();

// Destruir la sesión actual
session_destroy();

// Redirigir al usuario a login.html
header('Location: ../login.html');
exit();
?>
