<?php
header('Content-Type: application/json');

// Verificar que se recibió una petición POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "", "cecyayuda");
    
    if ($conexion->connect_error) {
        echo json_encode([
            'encontrado' => false,
            'error' => 'Error de conexión a la base de datos'
        ]);
        exit;
    }
    
    // Obtener el nombre a buscar
    $nombre = $conexion->real_escape_string(trim($_POST['nombre'] ?? ''));
    
    if (empty($nombre)) {
        echo json_encode([
            'encontrado' => false,
            'error' => 'Nombre no proporcionado'
        ]);
        exit;
    }
    
    // Buscar la opinión en la base de datos
    $sql = "SELECT * FROM mejoras WHERE nombre = '$nombre'";
    $resultado = $conexion->query($sql);
    
    if ($resultado->num_rows > 0) {
        // Opinión encontrada
        $opinion = $resultado->fetch_assoc();
        
        echo json_encode([
            'encontrado' => true,
            'opinion' => $opinion,
            'nombre_buscado' => $nombre
        ]);
        
    } else {
        // Opinión no encontrada
        echo json_encode([
            'encontrado' => false,
            'nombre_buscado' => $nombre,
            'mensaje' => 'No se encontró ninguna opinión con ese nombre'
        ]);
    }
    
    $conexion->close();
    
} else {
    // Método no permitido
    echo json_encode([
        'encontrado' => false,
        'error' => 'Método no permitido'
    ]);
}
?>