<?php

$conexion = new mysqli("localhost","root","","cecyayuda");
if ($conexion->connect_error) {
    die("ERROR DE CONEXION: ".$conexion->connect_error);
}

$tipo_v = $_POST['tipo_v'];
$tipo_p = $_POST['tipo_p'];
$archivo = $_POST['archivo'];
$fecha = $_POST['fecha'];
$correo_d = $_POST['correo_d'];
$descripcion = $_POST['descripcion'];

$sql = "INSERT INTO denuncia (correo_d,tipo_v, tipo_p, archivo, fecha, descripcion) VALUES ('$correo_d','$tipo_v', '$tipo_p', '$archivo', '$fecha', '$descripcion')";

if($conexion->query($sql) === TRUE){
    echo "<script>
    alert('Tu denuncia se ha realizado correctamente, si deseas cambiar algun dato puedes consultarlo');
    window.location.href = 'portada.html';
    </script>";
}else{
    echo "ERROR AL GUARDAR DATOS";
}

$conexion->close();

?>