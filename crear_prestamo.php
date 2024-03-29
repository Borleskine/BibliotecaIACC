<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit();
}

// Verificar si se recibió una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $usuario_id = $_POST["usuario_id"];
    $fecha_prestamo = $_POST["fecha_prestamo"];
    $fecha_limite = $_POST["fecha_limite"];
    $libros = $_POST["libros"];

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

    // Insertar nuevo préstamo en la tabla prestamos
    $sql_insert_prestamo = "INSERT INTO prestamos (usuario_id, fecha_prestamo, fecha_limite) VALUES ('$usuario_id', '$fecha_prestamo', '$fecha_limite')";
    if ($conn->query($sql_insert_prestamo) === TRUE) {
        // Obtener el ID del préstamo recién insertado
        $prestamo_id = $conn->insert_id;

        // Insertar los libros asociados al préstamo en la tabla prestamo_libros
        foreach ($libros as $libro_id) {
            $sql_insert_prestamo_libro = "INSERT INTO prestamo_libros (prestamo_id, libro_id) VALUES ('$prestamo_id', '$libro_id')";
            $conn->query($sql_insert_prestamo_libro);
        }
                
        // Redireccionar o mostrar un mensaje de éxito
        header("Location: mantenedor.php?success=1");
        exit();
        
    } else {
        echo "Error al crear el préstamo: " . $conn->error;
    }

    // Cerrar conexión
    $conn->close();
} else {
    // Si la solicitud no es POST, redirigir a otra página o mostrar un mensaje de error
    header("Location: otra_pagina.php");
    exit();
}
?>
