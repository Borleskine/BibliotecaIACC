<?php
session_start();

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

// Función para escapar datos ingresados por el usuario
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Procesar formulario de inicio de sesión
if (isset($_POST["login_submit"])) {
    $username = sanitize_input($_POST["username"]);

    // Consulta para verificar si el usuario existe
    $sql = "SELECT id, nombre, perfil FROM usuarios WHERE nombre = '$username'";
    $result = $conn->query($sql);

    if ($result) { // Verificar si la consulta se ejecutó correctamente
        if ($result->num_rows > 0) {
            // Usuario encontrado, iniciar sesión y redirigir según el perfil
            $row = $result->fetch_assoc();
            $_SESSION["id"] = $row["id"];
            $_SESSION["nombre"] = $row["nombre"];
            $_SESSION["perfil"] = $row["perfil"];
            if ($row["perfil"] == 1) {
                header("Location: mantenedor.php");
                exit();
            } else if ($row["perfil"] == 2) {
                header("Location: perfil_usuario.php");
                exit();
            } else {
                header("Location: index.php");
                exit();
            }
        } else {
            // Usuario no encontrado, mostrar mensaje de error
            $error_message = "Nombre de usuario incorrecto.";
        }
    } else {
        // Error en la consulta SQL
        $error_message = "Error en la consulta SQL: " . $conn->error;
    }
}


// Procesar formulario de registro de cuenta
if (isset($_POST["register_submit"])) {
    $new_username = sanitize_input($_POST["new_username"]);

    // Consulta para verificar si el nombre de usuario ya está en uso
    $check_username_query = "SELECT id FROM usuarios WHERE nombre = '$new_username'";
    $check_username_result = $conn->query($check_username_query);
    
    if ($check_username_result->num_rows > 0) {
        $error_message = "El nombre de usuario ya está en uso.";
    } else {
        // Consulta para insertar un nuevo usuario en la base de datos
        $insert_user_query = "INSERT INTO usuarios (nombre, perfil) VALUES ('$new_username', 2)";
        
        if ($conn->query($insert_user_query) === TRUE) {
            // Usuario registrado exitosamente
            $_SESSION["success_message"] = "Usuario registrado correctamente.";
        } else {
            // Error al registrar usuario
            $error_message = "Error al registrar usuario: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión / Crear cuenta</title>
    <style>
        /* Estilos para las divisiones */
        .container {
            margin: 20px;
            padding: 20px 20px 20px 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            width: 25%;
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
            padding: 8px 12px 10px 10px;
            border: 1px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .form-container {
            margin: 20px 20px 20px 20px;
        }

        label {
            display: block;
            margin: 5px 5px 5px 5px ;
        }

        input[type="text"],
        input[type="number"] {
            width: 95%;
            padding: 8px 8px 8px 8px;
            margin-bottom: 10px;
            margin-right: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: 2px;
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
    <h1>SISTEMA DE REGISTRO BIBLIOTECARIO IACC</h1><br>
    <h2>Iniciar sesión</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Nombre de usuario:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <input type="submit" name="login_submit" value="Iniciar sesión">
    </form>
    <?php
    if (isset($error_message)) {
        echo "<p>$error_message</p>";
    }
    ?>
    <br>
    <h2>Crear cuenta</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="new_username">Nuevo nombre de usuario:</label><br>
        <input type="text" id="new_username" name="new_username" required><br><br>
        <input type="submit" name="register_submit" value="Crear cuenta">
    </form>
    </div>
    <?php
    if (isset($_SESSION["success_message"])) {
        echo "<p>{$_SESSION["success_message"]}</p>";
        unset($_SESSION["success_message"]);
    }
    ?>
</body>
</html>
