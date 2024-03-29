<?php
// Verificar si se han enviado datos desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se han recibido los datos necesarios
    if (isset($_POST["titulo"]) && isset($_POST["autor"]) && isset($_POST["editorial"]) && isset($_POST["ano_publicacion"]) && isset($_POST["cantidad_disponible"])) {
        // Obtener los datos del formulario
        $titulo = $_POST["titulo"];
        $autor = $_POST["autor"];
        $editorial = $_POST["editorial"];
        $ano_publicacion = $_POST["ano_publicacion"];
        $cantidad_disponible = $_POST["cantidad_disponible"];

        // Configurar la conexión a la base de datos
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

        // Preparar la consulta SQL para insertar un nuevo libro
        $sql = "INSERT INTO libros (titulo, autor, editorial, ano_publicacion, cantidad_disponible) VALUES ('$titulo', '$autor', '$editorial', '$ano_publicacion', '$cantidad_disponible')";

        // Ejecutar la consulta SQL
        if ($conn->query($sql) === TRUE) {
            // Redirigir de vuelta al formulario de agregar libro con un mensaje de éxito
            header("Location: mantenedor.php");
            exit();
        } else {
            // Mostrar un mensaje de error si la consulta falla
            echo "Error al agregar el libro: " . $conn->error;
        }

        // Cerrar la conexión a la base de datos
        $conn->close();
    } else {
        // Mostrar un mensaje de error si no se reciben todos los datos necesarios
        echo "Todos los campos son obligatorios.";
    }
}
?>
