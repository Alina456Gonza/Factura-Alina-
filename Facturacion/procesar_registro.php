<?php
session_start();
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre  = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $correo  = mysqli_real_escape_string($conexion, $_POST['correo']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $clave   = mysqli_real_escape_string($conexion, $_POST['clave']);
    $rol     = mysqli_real_escape_string($conexion, $_POST['rol']);

    $query_exist = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario = '$usuario' OR correo = '$correo'");

    if (mysqli_num_rows($query_exist) > 0) {
        $mensaje = "⚠ El usuario o correo ya existen. Intente con otro.";
    } else {
        $query = "INSERT INTO usuario (nombre, correo, usuario, clave, rol) VALUES ('$nombre', '$correo', '$usuario', '$clave', '$rol')";
        $result = mysqli_query($conexion, $query);

        if ($result) {
            $mensaje = "✔ Usuario registrado correctamente.";
        } else {
            $mensaje = "⚠ Hubo un error al registrar el usuario. Por favor, inténtelo de nuevo.";
        }
    }

    // Mostrar el mensaje en una alerta antes de redirigir
    echo "<script>
            alert('$mensaje');
            window.location.href = 'registro_usuario.php';
          </script>";
    exit();
}


?>
