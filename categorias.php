<?php
$servername = "localhost";
$username = "root";
$password = "usbw";
$dbname = "preguntados";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categorías</title>
    <style>
        body {
            display: grid;
            place-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
        }

        .categorias {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .categoria {
            width: 200px;
            height: 150px;
            background-color: lightblue;
            border: 1px solid black;
            padding: 10px;
            text-align: center;
            transition: transform 0.3s;
            cursor: pointer;
        }

        .categoria:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <h1>Categorías</h1>

    <div class="categorias">
        <?php
        // Realizar consulta
        $sql = "SELECT * FROM categorias";
        $result = $conn->query($sql);

        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $idCategoria = $row["id"];
                $nombreCategoria = $row["nombre"];
                $descripcionCategoria = $row["descripcion"];

                // Generar la URL de la página de redirección
                $urlRedireccion = "preguntas.php?id=" . $idCategoria;


                // Imprimir el cuadro de categoría
                echo "<div class='categoria' onclick=\"window.location.href='$urlRedireccion'\">";
                echo "<h3>" . $nombreCategoria . "</h3>";
                echo "<p>" . $descripcionCategoria . "</p>";
                echo "</div>";
            }
        } else {
            echo "No se encontraron categorías.";
        }
        ?>
    </div>

    <?php
    // Cerrar conexión
    $conn->close();
    ?>
</body>
</html>
