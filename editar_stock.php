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
    header("Location: mantenedor.php");
    exit();
}

// Verificar si se ha proporcionado el ID del libro a editar
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    // Redirigir si no se proporciona un ID válido
    header("Location: mantenedor.php");
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

// Obtener el ID del libro a editar desde la URL
$libro_id = $_GET["id"];

// Verificar si se envió el formulario para actualizar el stock del libro
if (isset($_POST["actualizar_stock"])) {
    // Obtener el nuevo valor del stock del formulario
    $nuevo_stock = $_POST["nuevo_stock"];

    // Actualizar el stock del libro en la base de datos
    $sql_update = "UPDATE libros SET cantidad_disponible = $nuevo_stock WHERE id = $libro_id";

    if ($conn->query($sql_update) === TRUE) {
        // Redirigir de nuevo a la lista de libros con un mensaje de éxito
        header("Location: mantenedor.php");
        exit();
    } else {
        // Mostrar un mensaje de error si la actualización falla
        $error_message = "Error al actualizar el stock del libro: " . $conn->error;
    }
}

// Obtener información del libro seleccionado para mostrar en el formulario
$sql_libro = "SELECT * FROM libros WHERE id = $libro_id";
$result_libro = $conn->query($sql_libro);

if ($result_libro->num_rows == 1) {
    // Obtener los datos del libro
    $libro = $result_libro->fetch_assoc();
} else {
    // Si no se encuentra el libro, redirigir a la lista de libros
    header("Location: mantenedor.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Stock del Libro</title>
    <style>
        /* Estilos para el formulario */
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

        label {
            display: block;
            margin-bottom: 5px;
        }

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
</head>
 
<body>
<div class="container">
    <h2>Editar Stock del Libro</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $libro_id; ?>">
        <label for="nuevo_stock">Nuevo Stock:</label>
        <input type="number" id="nuevo_stock" name="nuevo_stock" value="<?php echo $libro["cantidad_disponible"]; ?>" required>

        <input type="submit" name="actualizar_stock" value="Actualizar Stock">

        <?php
        // Mostrar mensaje de error si existe
        if (isset($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        }
        ?>
    </form>
</div>



</body>
</html>
