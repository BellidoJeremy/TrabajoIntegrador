<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Preguntas y Alternativas</title>
</head>
<body>
    <h1>Categorias</h1>
    <form action="preguntas_alter.php" method="post">
        <label for="categoria">Seleccione una categoría:</label>
        <select name="categoria" id="categoria">
            <?php
            // Conexión a la base de datos
            $servername = "localhost";
            $username = "root";
            $password = "usbw";
            $dbname = "preguntados";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }

            // Consulta de las categorías disponibles
            $sql = "SELECT id, nombre FROM categorias";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row["id"];
                    $nombre = $row["nombre"];
                    echo "<option value='$id'>$nombre</option>";
                }
            } else {
                echo "<option value=''>No hay categorías disponibles</option>";
            }

            // Cerrar conexión
            $conn->close();
            ?>
        </select>
        <br><br>
        <input type="submit" value="Comenzar">
    </form>
</body>
</html>

