<!DOCTYPE html>
<html lang="es"> <!--se escoge el idioma español-->
    <head>
        <meta charset="UTF-8"> 
        <title>Consulta Denuncia</title> <!--titulo de la pagina-->
        <link rel="icon" type="image/x-icon" href="logo_info.png">
        
        <style>

            /*estilo para la caja*/
            .cuadro{
                width: 500px; /*se define el tamaño del ancho del cudro*/
                height: auto; /* Cambiado para ajustarse al contenido */
                background-color: white; /*se define el color de fondo del cuadro*/
                border: 6px solid rgb(166, 97, 255); /*se define un color de borde y su tamaño en pixeles*/
                padding: 10px; /*tamaño interno del cuadro*/
                text-align: center; /*centra los textos dentro del cuadro */
                align-items: center; /*centra los elementos como los botones*/
                margin-top: 50px;  /*posicion de la parte de rriba de la pagina*/
                margin-left: 400px; /*posicion hacia la izquierda de la pagina*/
                border-radius: 15px; /* Agregado para bordes redondeados */
            }

            /* Estilos para los botones */
            .btn {
                padding: 10px 20px;
                margin: 5px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
                background-color:rgb(55, 201, 176);
            }

            .btn:hover {
                opacity: 0.8;
                transform: translateY(-2px);
            }

            /* Estilos para mensajes */
            .mensaje {
                padding: 10px;
                margin: 10px 0;
                border-radius: 5px;
                font-weight: bold;
            }

            .error {
                background-color: #ffebee;
                color: #c62828;
                border: 1px solid #ef5350;
            }

            .exito {
                background-color: #e8f5e8;
                color: #2e7d32;
                border: 1px solid #4caf50;
            }

            .info {
                background-color: #e3f2fd;
                color: #1565c0;
                border: 1px solid #2196f3;
            
            }
        </style>
    </head>
    
    <body style="background-image: url(img1.jpg); background-size: cover;">

    <?php
    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "cecyayuda");

    // Verificar conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Variables para manejar redirección
    if (isset($_POST['regresar'])) {
        header("Location: consulta_personal.php");
        exit();
    }

    // Variables para los datos
    $correo_d = "";
    $tipo_v = "";
    $fecha = "";
    $descripcion = "";
    $mensaje = "";
    $tipo_mensaje = "";
    $registro_encontrado = false;

    // BUSCAR REGISTRO
    if (isset($_POST['buscar']) && !empty($_POST['correo_d'])) {
        $correo_buscar = $_POST['correo_d'];
        
        $sql = "SELECT correo_d,tipo_v,fecha,descripcion FROM denuncia WHERE correo_d=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $correo_buscar);
        $stmt->execute();
        
        $stmt->bind_result($correo_d, $tipo_v,$fecha, $descripcion);
        
        if ($stmt->fetch()) {
            $registro_encontrado = true;
            $mensaje = "Denuncia encontrada exitosamente";
            $tipo_mensaje = "exito";
        } else {
            $correo_d = $correo_buscar;
            $tipo_v = $fecha = $descripcion = "";
            $mensaje = "No se encontró la denuncia";
            $tipo_mensaje = "error";
        }
        $stmt->close();
    }

    // ACTUALIZAR REGISTRO
    if (isset($_POST['actualizar']) && !empty($_POST['correo_d'])) {
        $correo_actualizar = $_POST['correo_d'];
        $nuevo_tipo_v = $_POST['tipo_v'];
        $nueva_fecha = $_POST['fecha'];
        $nueva_descripcion = $_POST['descripcion'];
        
        $sql = "UPDATE denuncia SET tipo_v=?, fecha=?, descripcion=? WHERE correo_d=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssss", $nuevo_tipo_v, $nueva_fecha, $nueva_descripcion, $correo_actualizar);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $mensaje = "Registro actualizado exitosamente";
                $tipo_mensaje = "exito";
                $correo_d = $correo_actualizar;
                $tipo_v = $nuevo_tipo_v;
                $fecha = $nueva_fecha;
                $descripcion = $nueva_descripcion;
                $registro_encontrado = true;
            } else {
                $mensaje = "No se encontró la denuncia para actualizar";
                $tipo_mensaje = "error";
            }
        } else {
            $mensaje = "Error al actualizar: " . $stmt->error;
            $tipo_mensaje = "error";
        }
        $stmt->close();
    }

    // ELIMINAR REGISTRO
    if (isset($_POST['eliminar']) && !empty($_POST['correo_d'])) {
        $correo_eliminar = $_POST['correo_d'];
        
        $sql = "DELETE FROM denuncia WHERE correo_d=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $correo_eliminar);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $mensaje = "Registro eliminado exitosamente";
                $tipo_mensaje = "exito";
                $correo_d = $tipo_v = $fecha = $descripcion = "";
                $registro_encontrado = false;
            } else {
                $mensaje = "No se encontró la denuncia para eliminar";
                $tipo_mensaje = "error";
            }
        } else {
            $mensaje = "Error al eliminar: " . $stmt->error;
            $tipo_mensaje = "error";
        }
        $stmt->close();
    }

    $conexion->close();
    ?>

    <form method="POST" action="">
        <div class="cuadro">
            <h2 style="font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; text-align: center;">Consultar Denuncia</h2>
            
            <!-- Mostrar mensajes -->
            <?php if (!empty($mensaje)): ?>
                <div class="mensaje <?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            
            <label for="correo_d">Ingresa tu correo:</label><br>
            <input type="email" id="correo_d" name="correo_d" 
                   style="background-color: rgb(244, 207, 253); " 
                   value="<?php echo htmlspecialchars($correo_d); ?>"><br><br>
            
            <label for="tipo_v">Sexo:</label><br>
            <select id="tipo_v" name="tipo_v" 
                    style="background-color: rgb(244, 207, 253); margin-left: 10px; width: 200px;">
                <option value="">Seleccionar...</option>
                <option value="Verbal" <?php echo ($tipo_v == 'Verbal') ? 'selected' : ''; ?>>Verbal</option>
                <option value="Física" <?php echo ($tipo_v == 'Fisica') ? 'selected' : ''; ?>>Física</option>
                <option value="Psicológica" <?php echo ($tipo_v == 'Psicológica') ? 'selected' : ''; ?>>Psicológica</option>
                <option value="Sexual" <?php echo ($tipo_v == 'Sexual') ? 'selected' : ''; ?>>Sexual</option>
                <option value="Económica" <?php echo ($tipo_v == 'Económica') ? 'selected' : ''; ?>>Económica</option>
                <option value="Digital" <?php echo ($tipo_v == 'Digital') ? 'selected' : ''; ?>>Digital</option>
                <option value="Simbólica" <?php echo ($tipo_v == 'Simbólica') ? 'selected' : ''; ?>>Simbólica</option>
            </select><br><br>

            <label for="fecha">Fecha:</label><br>
            <input type="date" id="fecha" name="fecha" 
                   style="background-color: rgb(244, 207, 253); margin-left: 10px;" 
                   value="<?php echo htmlspecialchars($fecha); ?>"><br><br>
            
            <label for="descripcion">Descripcion:</label><br>
            <textarea type="text" id="descripcion" name="descripcion" rows="10" cols="65"
                    style="background-color: rgb(244, 207, 253); margin-left: 10px;"></textarea>
            
            <!-- Botones de acción -->
            <div style="margin-top: 20px;">
                <button type="submit" name="buscar" class="btn btn-buscar" style="margin-left:-280px">🔍 Buscar Datos</button><br><br>
                
                <button type="submit" name="actualizar" class="btn btn-actualizar" style="float:left;margin-left:200px;margin-top:-60px;"
                        onclick="return confirm('¿Estás seguro de que quieres actualizar este registro?')">
                    ✏️ Actualizar
                </button>
                
                <button type="submit" name="eliminar" class="btn btn-eliminar"  style="float:left;margin-left:340px;margin-top:-60px;"
                        onclick="return confirm('¿Estás seguro de que quieres ELIMINAR este registro? Esta acción no se puede deshacer.')">
                    🗑️ Eliminar
                </button><br><br>
                
                <button type="submit" name="regresar" class="btn btn-regresar" style="float:left;margin-left:20px;margin-top:-30px;">⬅️ Regresar</button>
            </div>
        </div>
    </form>

    <script>
            // Validar formulario antes de enviar
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const correo_d = document.getElementById('correo_d').value.trim();
                
                // Si es el botón regresar, no validar nada
                if (e.submitter && e.submitter.name === 'regresar') {
                    return true;
                }
                
                if (correo_d === '') {
                    alert('Por favor, ingresa un correo.');
                    e.preventDefault();
                    return false;
                }
                
                // Validaciones adicionales para actualizar
                if (e.submitter && e.submitter.name === 'actualizar') {
                    const campos = ['correo_d', 'tipo_v', 'descripcion'];
                    for (let campo of campos) {
                        const valor = document.getElementById(campo).value.trim();
                        if (valor === '') {
                            alert(`Por favor, completa el campo ${campo}.`);
                            e.preventDefault();
                            return false;
                        }
                    }
                }
            });
        });
    </script>

    </body>
</html>