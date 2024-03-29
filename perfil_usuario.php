<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit();
}

// Verificar el perfil del usuario
if ($_SESSION["perfil"] != 2) {
    // Si el perfil no es 2, redirigir a otra página o mostrar un mensaje de error
    header("Location: otra_pagina.php");
    exit();
}

// Obtener la información del usuario desde la sesión
$id = $_SESSION["id"];
$nombre = $_SESSION["nombre"];
$perfil = $_SESSION["perfil"];

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

// Crear conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los préstamos asociados al usuario
$sql_prestamos = "SELECT * FROM prestamos WHERE usuario_id = $id";
$result_prestamos = $conn->query($sql_prestamos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de usuario</title>
</head>
<body>
    <h2>Información del usuario</h2>
    <p><strong>Código de usuario:</strong> <?php echo $id; ?></p>
    <p><strong>Bienvenido:</strong> <?php echo $nombre; ?></p>
    <p><strong>Perfil:</strong> <?php echo $perfil; ?></p>
    <br>
    <h3>Préstamos asociados</h3>
    <table border="1">
        <tr>
            <th>ID Préstamo</th>
            <th>Fecha de Préstamo</th>
            <th>Fecha Límite de Devolución</th>
            <!-- Agrega más columnas según la información que desees mostrar -->
        </tr>
        <?php
        // Mostrar los préstamos en la tabla
        if ($result_prestamos->num_rows > 0) {
            while($row = $result_prestamos->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["fecha_prestamo"] . "</td>";
                echo "<td>" . $row["fecha_limite"] . "</td>";
                // Puedes agregar más columnas según la información que desees mostrar
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay préstamos asociados.</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
