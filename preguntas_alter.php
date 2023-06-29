<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Preguntas y Alternativas</title>
    <style>
        .opcion {
            display: block;
            padding: 5px;
            cursor: pointer;
        }

        .opcion:hover {
            background-color: lightblue;
        }

        .correcta {
            color: green;
        }

        .incorrecta {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Preguntas y Alternativas</h1>

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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos de la pregunta actual
        $pregunta_id = $_POST["pregunta_id"];
        $respuesta_seleccionada = $_POST["respuesta_seleccionada"];

        // Consultar la respuesta correcta de la pregunta actual
        $sql_respuesta_correcta = "SELECT opcion FROM alternativas WHERE pregunta_id = $pregunta_id AND es_correcta = 1";
        $result_respuesta_correcta = $conn->query($sql_respuesta_correcta);

        if ($result_respuesta_correcta->num_rows > 0) {
            $row_respuesta_correcta = $result_respuesta_correcta->fetch_assoc();
            $respuesta_correcta = $row_respuesta_correcta["opcion"];

            echo "<h2>Respuesta seleccionada:</h2>";

            if ($respuesta_seleccionada == $respuesta_correcta) {
                echo "<p class='correcta'>¡Respuesta correcta!</p>";
            } else {
                echo "<p class='incorrecta'>Respuesta incorrecta. La respuesta correcta es: $respuesta_correcta</p>";
            }

            // Obtener la siguiente pregunta de la misma categoría
            $sql_siguiente_pregunta = "SELECT id, texto FROM preguntas WHERE categoria_id = (SELECT categoria_id FROM preguntas WHERE id = $pregunta_id) AND id > $pregunta_id LIMIT 1";
            $result_siguiente_pregunta = $conn->query($sql_siguiente_pregunta);

            if ($result_siguiente_pregunta->num_rows > 0) {
                $row_siguiente_pregunta = $result_siguiente_pregunta->fetch_assoc();
                $siguiente_pregunta_id = $row_siguiente_pregunta["id"];
                $siguiente_pregunta_texto = $row_siguiente_pregunta["texto"];

                echo "<h2>Siguiente pregunta:</h2>";
                echo "<p>$siguiente_pregunta_texto</p>";

                // Consulta de las alternativas de la siguiente pregunta
                $sql_siguiente_alternativas = "SELECT id, opcion FROM alternativas WHERE pregunta_id = $siguiente_pregunta_id ORDER BY orden";
                $result_siguiente_alternativas = $conn->query($sql_siguiente_alternativas);

                if ($result_siguiente_alternativas->num_rows > 0) {
                    echo "<h2>Alternativas:</h2>";
                    while ($row_siguiente_alternativas = $result_siguiente_alternativas->fetch_assoc()) {
                        $opcion_id = $row_siguiente_alternativas["id"];
                        $opcion_texto = $row_siguiente_alternativas["opcion"];

                        echo "<div class='opcion' onclick=\"verificarRespuesta(this, '$opcion_texto', $siguiente_pregunta_id)\">$opcion_texto</div>";
                    }
                } else {
                    echo "<p>No hay alternativas disponibles para la siguiente pregunta.</p>";
                }
            } else {
                echo "<p>No hay más preguntas disponibles para esta categoría.</p>";
            }
        }
    }

    // Obtener el ID de la categoría seleccionada
    $categoria_id = $_POST["categoria"];

    // Consulta de la primera pregunta de la categoría seleccionada
    $sql_primera_pregunta = "SELECT id, texto FROM preguntas WHERE categoria_id = $categoria_id ORDER BY id LIMIT 1";
    $result_primera_pregunta = $conn->query($sql_primera_pregunta);

    if ($result_primera_pregunta->num_rows > 0) {
        $row_primera_pregunta = $result_primera_pregunta->fetch_assoc();
        $primera_pregunta_id = $row_primera_pregunta["id"];
        $primera_pregunta_texto = $row_primera_pregunta["texto"];

        echo "<h2>Pregunta:</h2>";
        echo "<p>$primera_pregunta_texto</p>";

        // Consulta de las alternativas de la primera pregunta
        $sql_primera_alternativas = "SELECT id, opcion FROM alternativas WHERE pregunta_id = $primera_pregunta_id ORDER BY orden";
        $result_primera_alternativas = $conn->query($sql_primera_alternativas);

        if ($result_primera_alternativas->num_rows > 0) {
            echo "<h2>Alternativas:</h2>";
            while ($row_primera_alternativas = $result_primera_alternativas->fetch_assoc()) {
                $opcion_id = $row_primera_alternativas["id"];
                $opcion_texto = $row_primera_alternativas["opcion"];

                echo "<div class='opcion' onclick=\"verificarRespuesta(this, '$opcion_texto', $primera_pregunta_id)\">$opcion_texto</div>";
            }
        } else {
            echo "<p>No hay alternativas disponibles para esta pregunta.</p>";
        }
    } else {
        echo "<p>No hay preguntas disponibles para esta categoría.</p>";
    }

    // Cerrar conexión
    $conn->close();
    ?>

    <script>
        function verificarRespuesta(elemento, respuestaSeleccionada, preguntaId) {
            // Obtener todas las opciones
            var opciones = document.getElementsByClassName("opcion");

            // Quitar la clase seleccionada a todas las opciones
            for (var i = 0; i < opciones.length; i++) {
                opciones[i].classList.remove("seleccionada");
            }

            // Agregar la clase seleccionada a la opción clicada
            elemento.classList.add("seleccionada");

            // Enviar la respuesta seleccionada al servidor
            var formulario = document.createElement("form");
            formulario.method = "post";
            formulario.action = "";
            
            var preguntaIdInput = document.createElement("input");
            preguntaIdInput.type = "hidden";
            preguntaIdInput.name = "pregunta_id";
            preguntaIdInput.value = preguntaId;
            formulario.appendChild(preguntaIdInput);
            
            var respuestaSeleccionadaInput = document.createElement("input");
            respuestaSeleccionadaInput.type = "hidden";
            respuestaSeleccionadaInput.name = "respuesta_seleccionada";
            respuestaSeleccionadaInput.value = respuestaSeleccionada;
            formulario.appendChild(respuestaSeleccionadaInput);

            document.body.appendChild(formulario);
            formulario.submit();
        }
    </script>
</body>
</html>
