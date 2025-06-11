<?php 

$conexion = new mysqli("localhost","root","","cecyayuda");
if ($conexion->connect_error) {
    die("ERROR DE CONEXION: ".$conexion->connect_error);
}

$control = $_POST['control'];
$sexo = $_POST['sexo'];
$semestre = $_POST['semestre'];
$nombre = $_POST['nombre'];
$edad = $_POST['edad'];
$correo = $_POST['correo'];

$sql = "INSERT INTO denunciante (control, sexo, semestre, nombre, edad, correo) VALUES ('$control', '$sexo', '$semestre', '$nombre', '$edad', '$correo')";

if($conexion->query($sql) === TRUE){
    echo "<script>
    alert('datos personales guardados, ahora ingresarás tus datos de denuncia');
    window.location.href = 'formulario_2.html';
    </script>";
}else{
   echo "<script>
   alert('error al guardar los datos, intenta de nuevo');</script>";
}

$conexion->close();

?>