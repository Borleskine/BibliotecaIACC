<?php
session_start();

// Verificar si el usuario ya ha iniciado sesión
if(isset($_SESSION['nombre_usuario'])) {
    echo "Ya has iniciado sesión como " . $_SESSION['nombre_usuario'];
} else {
    echo "Iniciaste sesión correctamente.";
    $_SESSION['nombre_usuario'] = "usuario_prueba"; // Esto es solo para demostración, debes obtener el nombre de usuario de donde sea que lo almacenes
}
?>
