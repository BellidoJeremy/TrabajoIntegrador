<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Preguntas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 8px;
        }

        td.options {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        button {
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Preguntas</h1>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "usbw";
    $dbname = "Preguntados";

    // Establecer la conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta a la tabla Preguntas
    $sql = "SELECT * FROM Preguntas";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Texto</th><th>Opciones</th><th>Respuesta</th><th>Categoría ID</th><th>Dificultad</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td><h3>" . $row["texto"] . "</h3></td>";
            echo "<td class='options'>";

            // Dividir las opciones en un array
            $opciones = explode(",", $row["opciones"]);

            // Imprimir cada opción como un botón
            foreach ($opciones as $opcion) {
                echo "<button>" . $opcion . "</button>";
            }

            echo "</td>";
            echo "<td>" . $row["respuesta"] . "</td>";
            echo "<td>" . $row["categoría_id"] . "</td>";
            echo "<td>" . $row["dificultad"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No se encontraron preguntas.";
    }

    // Cerrar la conexión
    $conn->close();
    ?>

</body>
</html>
