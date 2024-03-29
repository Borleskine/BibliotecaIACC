<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit();
}

// Verificar el perfil del usuario
if ($_SESSION["perfil"] != 1) {
    // Si el perfil no es 1 (admin), redirigir a otra página o mostrar un mensaje de error
    header("Location: otra_pagina.php");
    exit();
}

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

// Obtener los usuarios existentes para cargar en el formulario de selección
$sql_usuarios = "SELECT id, nombre FROM usuarios";
$result_usuarios = $conn->query($sql_usuarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenedor - Administrador</title>
    <style>
        /* Estilos para las divisiones */
        .container {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            width: 50%;
            margin-left: auto;
            margin-right: auto;
        }

        /* Estilos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Estilos para los botones */
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .form-container {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
<body>

<div class="container">
    <h2>Bienvenido, <?php echo isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Usuario"; ?> (Perfil: <?php echo isset($_SESSION["perfil"]) ? $_SESSION["perfil"] : "Perfil"; ?>)</h2>
    <div class="form-container">
        <form action="agregar_libro_form.php">
        <input type="submit" value="Agregar Libro">
        </form>
    </div>

    <h3>Lista de libros</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Editorial</th>
            <th>Año de Publicación</th>
            <th>Cantidad Disponible</th>
            <th>Acciones</th>
        </tr>
        <?php
        // Consulta para obtener todos los libros
        $sql_libros = "SELECT * FROM libros";
        $result_libros = $conn->query($sql_libros);

        if ($result_libros->num_rows > 0) {
            // Mostrar los libros en la tabla
            while($row = $result_libros->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["titulo"] . "</td>";
                echo "<td>" . $row["autor"] . "</td>";
                echo "<td>" . $row["editorial"] . "</td>";
                echo "<td>" . $row["ano_publicacion"] . "</td>";
                echo "<td>" . $row["cantidad_disponible"] . "</td>";
                echo "<td><a href='editar_stock.php?id=" . $row["id"] . "' class='btn'>Stock</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No hay libros registrados.</td></tr>";
        }
        ?>
    </table>

    <br>

    <div class="form-container">
    <h3>Crear Nuevo Préstamo</h3>
    <form method="post" action="crear_prestamo.php">
        <label for="usuario_id">Usuario:</label><br>
        <select name="usuario_id" id="usuario_id" required>
            <option value="" disabled selected>Selecciona un usuario</option>
            <?php
            // Mostrar opciones para seleccionar usuarios
            if ($result_usuarios->num_rows > 0) {
                while($row = $result_usuarios->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                }
            } else {
                echo "<option value='' disabled>No hay usuarios disponibles</option>";
            }
            ?>
        </select><br><br>

        <label for="fecha_prestamo">Fecha de Préstamo:</label>
        <input type="date" id="fecha_prestamo" name="fecha_prestamo" required><br><br>

        <label for="fecha_limite">Fecha Límite de Devolución:</label>
        <input type="date" id="fecha_limite" name="fecha_limite" required><br><br>

        <label for="libros">Libros:</label><br>
        <select name="libros[]" id="libros" multiple required>
            <?php
            // Consulta para obtener todos los libros disponibles
            $sql = "SELECT id, titulo FROM libros WHERE cantidad_disponible > 0";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["titulo"] . "</option>";
                }
            } else {
                echo "<option value='' disabled>No hay libros disponibles</option>";
            }
            ?>
        </select><br><br>

        <input type="submit" name="create_loan" value="Crear Préstamo">
    </form>
    </div>

    <br>
    <a href="logout.php">Cerrar sesión</a>
</div>


</body>
</html>
