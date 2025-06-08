<!DOCTYPE html>
<html lang="es"> <!--se escoge el idioma español-->
    <head>
        <meta charset="UTF-8"> 
        <title>Consulta Personal</title> <!--titulo de la pagina-->
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

            /* Estilos para campos con error */
            .campo-error {
                border: 2px solid #c62828 !important;
                background-color: #ffebee !important;
            }
        </style>
    </head>
    
    <body style="background-image: url(img1.jpg); background-size: cover;">

    <?php
    // Función para validar número de control
    function validarControl($control) {
        return preg_match('/^\d{14}$/', $control);
    }

    // Función para validar correo electrónico
    function validarCorreo($correo) {
        return filter_var($correo, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Función para validar nombre (solo letras y espacios)
    function validarNombre($nombre) {
        return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre);
    }

    // Función para validar edad
    function validarEdad($edad) {
        return is_numeric($edad) && $edad >= 13 && $edad <= 21 && strlen($edad) == 2;
    }

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "cecyayuda");

    // Verificar conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Variables para manejar redirección
    if (isset($_POST['regresar'])) {
        header("Location: portada.html");
        exit();
    }

    // Variables para manejar redirección
    if (isset($_POST['consulta_denuncia'])) {
        header("Location: consulta_denuncia.php");
        exit();
    }

    // Variables para los datos
    $control = "";
    $correo = "";
    $sexo = "";
    $nombre = "";
    $edad = "";
    $semestre = "";
    $mensaje = "";
    $tipo_mensaje = "";
    $registro_encontrado = false;
    $errores_validacion = array();

    // BUSCAR REGISTRO
    if (isset($_POST['buscar']) && !empty($_POST['control'])) {
        $control_buscar = $_POST['control'];
        
        // Validar número de control
        if (!validarControl($control_buscar)) {
            $errores_validacion[] = "El número de control debe tener exactamente 14 dígitos";
        }
        
        if (empty($errores_validacion)) {
            $sql = "SELECT control, correo, sexo, nombre, edad, semestre FROM denunciante WHERE control=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $control_buscar);
            $stmt->execute();
            
            $stmt->bind_result($control, $correo, $sexo, $nombre, $edad, $semestre);
            
            if ($stmt->fetch()) {
                $registro_encontrado = true;
                $mensaje = "Registro encontrado exitosamente";
                $tipo_mensaje = "exito";
            } else {
                $control = $control_buscar;
                $correo = $sexo = $nombre = $edad = $semestre = "";
                $mensaje = "No se encontró el registro";
                $tipo_mensaje = "error";
            }
            $stmt->close();
        } else {
            $control = $control_buscar;
            $mensaje = implode("<br>", $errores_validacion);
            $tipo_mensaje = "error";
        }
    }

    // ACTUALIZAR REGISTRO
    if (isset($_POST['actualizar']) && !empty($_POST['control'])) {
        $control_actualizar = $_POST['control'];
        $nuevo_correo = $_POST['correo'];
        $nuevo_sexo = $_POST['sexo'];
        $nuevo_nombre = $_POST['nombre'];
        $nueva_edad = $_POST['edad'];
        $nuevo_semestre = $_POST['semestre'];
        
        // Validaciones del lado del servidor
        if (!validarControl($control_actualizar)) {
            $errores_validacion[] = "El número de control debe tener exactamente 14 dígitos";
        }
        
        if (!validarCorreo($nuevo_correo)) {
            $errores_validacion[] = "El correo electrónico no tiene un formato válido";
        }
        
        if (!validarNombre($nuevo_nombre)) {
            $errores_validacion[] = "El nombre solo debe contener letras y espacios";
        }
        
        if (!validarEdad($nueva_edad)) {
            $errores_validacion[] = "La edad debe ser un número de 2 dígitos entre 13 y 21 años";
        }
        
        if (empty($nuevo_sexo)) {
            $errores_validacion[] = "Debe seleccionar un sexo";
        }
        
        if (empty($nuevo_semestre)) {
            $errores_validacion[] = "Debe seleccionar un semestre";
        }
        
        if (empty($errores_validacion)) {
            $sql = "UPDATE denunciante SET correo=?, sexo=?, nombre=?, edad=?, semestre=? WHERE control=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssiss", $nuevo_correo, $nuevo_sexo, $nuevo_nombre, $nueva_edad, $nuevo_semestre, $control_actualizar);
            
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $mensaje = "Registro actualizado exitosamente";
                    $tipo_mensaje = "exito";
                    $control = $control_actualizar;
                    $correo = $nuevo_correo;
                    $sexo = $nuevo_sexo;
                    $nombre = $nuevo_nombre;
                    $edad = $nueva_edad;
                    $semestre = $nuevo_semestre;
                    $registro_encontrado = true;
                } else {
                    $mensaje = "No se encontró el registro para actualizar";
                    $tipo_mensaje = "error";
                }
            } else {
                $mensaje = "Error al actualizar: " . $stmt->error;
                $tipo_mensaje = "error";
            }
            $stmt->close();
        } else {
            $control = $control_actualizar;
            $correo = $nuevo_correo;
            $sexo = $nuevo_sexo;
            $nombre = $nuevo_nombre;
            $edad = $nueva_edad;
            $semestre = $nuevo_semestre;
            $mensaje = implode("<br>", $errores_validacion);
            $tipo_mensaje = "error";
        }
    }

    // ELIMINAR REGISTRO
    if (isset($_POST['eliminar']) && !empty($_POST['control'])) {
        $control_eliminar = $_POST['control'];
        
        // Validar número de control
        if (!validarControl($control_eliminar)) {
            $errores_validacion[] = "El número de control debe tener exactamente 14 dígitos";
        }
        
        if (empty($errores_validacion)) {
            $sql = "DELETE FROM denunciante WHERE control=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $control_eliminar);
            
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $mensaje = "Registro eliminado exitosamente";
                    $tipo_mensaje = "exito";
                    $control = $correo = $sexo = $nombre = $edad = $semestre = "";
                    $registro_encontrado = false;
                } else {
                    $mensaje = "No se encontró el registro para eliminar";
                    $tipo_mensaje = "error";
                }
            } else {
                $mensaje = "Error al eliminar: " . $stmt->error;
                $tipo_mensaje = "error";
            }
            $stmt->close();
        } else {
            $control = $control_eliminar;
            $mensaje = implode("<br>", $errores_validacion);
            $tipo_mensaje = "error";
        }
    }

    $conexion->close();
    ?>

    <form method="POST" action="">
        <div class="cuadro">
            <h2 style="font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; text-align: center;">Consultar Registro Personal</h2>
            
            <!-- Mostrar mensajes -->
            <?php if (!empty($mensaje)): ?>
                <div class="mensaje <?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            
            <label for="control">Ingresa tu número de control (14 dígitos):</label><br>
            <input type="text" id="control" name="control" 
                   style="background-color: rgb(244, 207, 253); " 
                   value="<?php echo htmlspecialchars($control); ?>"
                   maxlength="14" pattern="\d{14}"
                   title="Debe contener exactamente 14 dígitos"><br><br>
            
            <label for="correo">Correo electrónico:</label><br>
            <input type="email" id="correo" name="correo" 
                   style="background-color: rgb(244, 207, 253);" 
                   value="<?php echo htmlspecialchars($correo); ?>"
                   title="Ingrese un correo electrónico válido"><br><br>
            
            <label for="sexo">Sexo:</label><br>
            <select id="sexo" name="sexo" 
                    style="background-color: rgb(244, 207, 253); margin-left: 10px; width: 200px;">
                <option value="">Seleccionar...</option>
                <option value="Masculino" <?php echo ($sexo == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                <option value="Femenino" <?php echo ($sexo == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                <option value="Otro" <?php echo ($sexo == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                <option value="Prefiero no decirlo" <?php echo ($sexo == 'Prefiero no decirlo') ? 'selected' : ''; ?>>Prefiero no decirlo</option>
            </select><br><br>
            
            <label for="nombre">Nombre (solo letras):</label><br>
            <input type="text" id="nombre" name="nombre" 
                   style="background-color: rgb(244, 207, 253); margin-left: 10px;" 
                   value="<?php echo htmlspecialchars($nombre); ?>"
                   pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                   title="Solo se permiten letras y espacios"><br><br>
            
            <label for="edad">Edad (13-21 años):</label><br>
            <input type="number" id="edad" name="edad" 
                   style="background-color: rgb(244, 207, 253); margin-left: 10px;" 
                   value="<?php echo htmlspecialchars($edad); ?>" 
                   min="13" max="21"
                   title="La edad debe estar entre 13 y 21 años"><br><br>
            
            <label for="semestre">Semestre:</label><br>
            <select id="semestre" name="semestre" 
                    style="background-color: rgb(244, 207, 253); margin-left: 10px; width: 200px;">
                <option value="">Seleccionar...</option>
                <?php for($i = 1; $i <= 6; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($semestre == $i) ? 'selected' : ''; ?>>
                        <?php echo $i; ?>° Semestre
                    </option>
                <?php endfor; ?>
            </select><br><br>
            
            <!-- Botones de acción -->
            <div style="margin-top: 20px;">
                <button type="submit" name="buscar" class="btn" style="margin-left:-280px">🔍 Buscar Datos</button><br><br>
                
                <button type="submit" name="actualizar" class="btn" style="float:left;margin-left:200px;margin-top:-60px;"
                        onclick="return confirm('¿Estás seguro de que quieres actualizar este registro?')">
                    ✏️ Actualizar
                </button>
                
                <button type="submit" name="eliminar" class="btn"  style="float:left;margin-left:340px;margin-top:-60px;"
                        onclick="return confirm('¿Estás seguro de que quieres ELIMINAR este registro? Esta acción no se puede deshacer.')">
                    🗑️ Eliminar
                </button><br><br>
                
                <button type="submit" name="regresar" class="btn" style="float:left;margin-left:20px;margin-top:-30px;">⬅️ Regresar</button>

                <button type="submit" name="consulta_denuncia" class="btn" style="float:left;margin-left:100px;margin-top:-30px;">📝 Consultar Denuncia</button>
            </div>
        </div>
    </form>

    <script>
        // Función para validar número de control
        function validarControl(control) {
            const regex = /^\d{14}$/;
            return regex.test(control);
        }

        // Función para validar correo electrónico
        function validarCorreo(correo) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(correo);
        }

        // Función para validar nombre
        function validarNombre(nombre) {
            const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
            return regex.test(nombre);
        }

        // Función para validar edad
        function validarEdad(edad) {
            const edadNum = parseInt(edad);
            return !isNaN(edadNum) && edadNum >= 13 && edadNum <= 21 && edad.length === 2;
        }

        // Función para mostrar errores en tiempo real
        function mostrarError(campo, mensaje) {
            campo.classList.add('campo-error');
            campo.title = mensaje;
        }

        function limpiarError(campo) {
            campo.classList.remove('campo-error');
            campo.title = '';
        }

        // Validar formulario antes de enviar
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const controlInput = document.getElementById('control');
            const correoInput = document.getElementById('correo');
            const nombreInput = document.getElementById('nombre');
            const edadInput = document.getElementById('edad');

            // Validación en tiempo real para el número de control
            controlInput.addEventListener('input', function() {
                const valor = this.value;
                
                // Solo permitir números
                this.value = valor.replace(/\D/g, '');
                
                if (this.value.length > 0 && this.value.length !== 14) {
                    mostrarError(this, 'El número de control debe tener exactamente 14 dígitos');
                } else if (this.value.length === 14) {
                    limpiarError(this);
                }
            });

            // Validación en tiempo real para el correo
            correoInput.addEventListener('blur', function() {
                if (this.value && !validarCorreo(this.value)) {
                    mostrarError(this, 'El formato del correo electrónico no es válido');
                } else {
                    limpiarError(this);
                }
            });

            // Validación en tiempo real para el nombre
            nombreInput.addEventListener('input', function() {
                const valor = this.value;
                
                if (valor && !validarNombre(valor)) {
                    mostrarError(this, 'El nombre solo puede contener letras y espacios');
                } else {
                    limpiarError(this);
                }
            });

            // Validación en tiempo real para la edad
            edadInput.addEventListener('input', function() {
                const valor = this.value;
                
                if (valor && !validarEdad(valor)) {
                    mostrarError(this, 'La edad debe ser un número de 2 dígitos entre 13 y 21 años');
                } else {
                    limpiarError(this);
                }
            });

            // Validación al enviar el formulario
            form.addEventListener('submit', function(e) {
                const control = document.getElementById('control').value.trim();
                
                // Si es el botón regresar o consulta_denuncia, no validar nada
                if (e.submitter && (e.submitter.name === 'regresar' || e.submitter.name === 'consulta_denuncia')) {
                    return true;
                }
                
                let errores = [];
                
                // Validar número de control
                if (control === '') {
                    errores.push('Por favor, ingresa un número de control.');
                } else if (!validarControl(control)) {
                    errores.push('El número de control debe tener exactamente 14 dígitos.');
                }
                
                // Validaciones adicionales para actualizar
                if (e.submitter && e.submitter.name === 'actualizar') {
                    const correo = document.getElementById('correo').value.trim();
                    const nombre = document.getElementById('nombre').value.trim();
                    const edad = document.getElementById('edad').value.trim();
                    const sexo = document.getElementById('sexo').value;
                    const semestre = document.getElementById('semestre').value;
                    
                    if (correo === '') {
                        errores.push('Por favor, ingresa un correo electrónico.');
                    } else if (!validarCorreo(correo)) {
                        errores.push('El formato del correo electrónico no es válido.');
                    }
                    
                    if (nombre === '') {
                        errores.push('Por favor, ingresa un nombre.');
                    } else if (!validarNombre(nombre)) {
                        errores.push('El nombre solo puede contener letras y espacios.');
                    }
                    
                    if (edad === '') {
                        errores.push('Por favor, ingresa una edad.');
                    } else if (!validarEdad(edad)) {
                        errores.push('La edad debe ser un número de 2 dígitos entre 13 y 21 años.');
                    }
                    
                    if (sexo === '') {
                        errores.push('Por favor, selecciona un sexo.');
                    }
                    
                    if (semestre === '') {
                        errores.push('Por favor, selecciona un semestre.');
                    }
                }
                
                if (errores.length > 0) {
                    alert(errores.join('\n'));
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>

    </body>
</html>