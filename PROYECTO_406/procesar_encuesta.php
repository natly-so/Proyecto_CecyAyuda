<?php
// Verificar que se recibieron datos POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "cecyayuda");
    if ($conexion->connect_error) {
        die("ERROR DE CONEXION: " . $conexion->connect_error);
    }

    // Obtener y validar el nombre (llave primaria)
    $nombre = $conexion->real_escape_string(trim($_POST['nombre'] ?? ''));
    
    if (empty($nombre)) {
        echo "<script>
        alert('El nombre es obligatorio.');
        window.history.back();
        </script>";
        exit;
    }

    // Verificar si ya existe una encuesta para esta persona
    $check_sql = "SELECT nombre FROM mejoras WHERE nombre = '$nombre'";
    $result = $conexion->query($check_sql);
    
    if ($result->num_rows > 0) {
        // Si ya existe, preguntar si quiere actualizar
        echo "<script>
        if (confirm('Ya existe una encuesta con este nombre. ¿Deseas actualizarla?')) {
            // Si acepta, proceder con UPDATE
            window.location.href = 'procesar_encuesta.php?action=update&nombre=" . urlencode($nombre) . "&" . http_build_query($_POST) . "';
        } else {
            // Si no acepta, regresar
            window.history.back();
        }
        </script>";
        $conexion->close();
        exit;
    }

    // Procesar arrays de checkboxes (convertir a string separado por comas)
    $preg_3 = isset($_POST['preg_3']) ? implode(',', $_POST['preg_3']) : '';
    $preg_6 = isset($_POST['preg_6']) ? implode(',', $_POST['preg_6']) : '';
    $preg_8 = isset($_POST['preg_8']) ? implode(',', $_POST['preg_8']) : '';
    
    // Obtener datos individuales y escapar caracteres especiales para evitar SQL injection
    $preg_1 = $conexion->real_escape_string($_POST['preg_1'] ?? '');
    $preg_2 = $conexion->real_escape_string($_POST['preg_2'] ?? '');
    $preg_3 = $conexion->real_escape_string($preg_3);
    $preg_4 = $conexion->real_escape_string($_POST['preg_4'] ?? '');
    $preg_5 = $conexion->real_escape_string($_POST['preg_5'] ?? '');
    $preg_6 = $conexion->real_escape_string($preg_6);
    $preg_7 = $conexion->real_escape_string($_POST['preg_7'] ?? '');
    $preg_8 = $conexion->real_escape_string($preg_8);
    $preg_9 = $conexion->real_escape_string($_POST['preg_9'] ?? '');
    $preg_10 = $conexion->real_escape_string($_POST['preg_10'] ?? '');

    // Crear la consulta SQL INSERT
    $sql = "INSERT INTO mejoras (nombre, preg_1, preg_2, preg_3, preg_4, preg_5, preg_6, preg_7, preg_8, preg_9, preg_10) 
            VALUES ('$nombre', '$preg_1', '$preg_2', '$preg_3', '$preg_4', '$preg_5', '$preg_6', '$preg_7', '$preg_8', '$preg_9', '$preg_10')";

    // Ejecutar la consulta
    if ($conexion->query($sql) === TRUE) {
        echo "<script>
        alert('Gracias $nombre por enviarnos tu opinión, mejoraremos nuestra página de acuerdo a tus necesidades.');
        window.location.href = 'portada.html';
        </script>";
    } else {
        echo "<script>
        alert('Hubo un error al enviar tu opinión, vuelve a intentarlo.');
        console.log('Error SQL: " . $conexion->error . "');
        </script>";
    }

    $conexion->close();
    
} elseif (isset($_GET['action']) && $_GET['action'] == 'update') {
    // Manejo de actualización de encuesta existente
    
    $conexion = new mysqli("localhost", "root", "", "cecyayuda");
    if ($conexion->connect_error) {
        die("ERROR DE CONEXION: " . $conexion->connect_error);
    }

    $nombre = $conexion->real_escape_string($_GET['nombre']);
    
    // Procesar arrays de checkboxes
    $preg_3 = isset($_GET['preg_3']) ? implode(',', $_GET['preg_3']) : '';
    $preg_6 = isset($_GET['preg_6']) ? implode(',', $_GET['preg_6']) : '';
    $preg_8 = isset($_GET['preg_8']) ? implode(',', $_GET['preg_8']) : '';
    
    // Escapar datos
    $preg_1 = $conexion->real_escape_string($_GET['preg_1'] ?? '');
    $preg_2 = $conexion->real_escape_string($_GET['preg_2'] ?? '');
    $preg_3 = $conexion->real_escape_string($preg_3);
    $preg_4 = $conexion->real_escape_string($_GET['preg_4'] ?? '');
    $preg_5 = $conexion->real_escape_string($_GET['preg_5'] ?? '');
    $preg_6 = $conexion->real_escape_string($preg_6);
    $preg_7 = $conexion->real_escape_string($_GET['preg_7'] ?? '');
    $preg_8 = $conexion->real_escape_string($preg_8);
    $preg_9 = $conexion->real_escape_string($_GET['preg_9'] ?? '');
    $preg_10 = $conexion->real_escape_string($_GET['preg_10'] ?? '');

    // UPDATE query
    $sql = "UPDATE mejoras SET 
            preg_1='$preg_1', preg_2='$preg_2', preg_3='$preg_3', preg_4='$preg_4', preg_5='$preg_5',
            preg_6='$preg_6', preg_7='$preg_7', preg_8='$preg_8', preg_9='$preg_9', preg_10='$preg_10'
            WHERE nombre='$nombre'";
    
    if ($conexion->query($sql) === TRUE) {
        echo "<script>
        alert('Encuesta de $nombre actualizada exitosamente.');
        window.location.href = 'portada.html';
        </script>";
    } else {
        echo "<script>
        alert('Error al actualizar la encuesta.');
        </script>";
    }
    
    $conexion->close();
    
} else {
    // Si no se recibieron datos POST, redireccionar
    echo "<script>
    alert('Acceso no válido.');
    window.location.href = 'portada.html';
    </script>";
}
?>