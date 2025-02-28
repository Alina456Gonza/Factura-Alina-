<?php

$alert = '';

if (!empty($_POST)) {
    if (empty($_POST['usuario']) || empty($_POST['clave'])) {
        $alert = 'Ingrese su usuario y su clave';
    } else {
        require_once "conexion.php";  // Incluye el archivo de conexión
        $user = mysqli_real_escape_string($conexion, $_POST['usuario']);  // Usa $conexion en lugar de $connection
        $password = mysqli_real_escape_string($conexion, $_POST['clave']);  // Usa $conexion en lugar de $connection
        
        $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario = '$user' AND clave = '$password'");
        mysqli_close($conexion);
        $result = mysqli_num_rows($query);

        if ($result > 0) {
            $data = mysqli_fetch_array($query);
            session_start();
            $_SESSION['active'] = true;
            $_SESSION['idUser'] = $data['idusuario'];
            $_SESSION['nombre'] = $data['nombre'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['user'] = $data['usuario'];
            $_SESSION['rol'] = $data['rol'];

            header('location: principal.php');
        } else {
            $alert = 'El usuario o clave de usuario son incorrectos';
            session_destroy();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/Estilos.css">
</head>
<body>
    <section id="container">
        <div class="image-section">
            <img src="img/login7.jpg" alt="Illustration">
        </div>
        <div class="form-section">
            <h3>Login</h3>
            <form action="" method="post">
                <input type="text" name="usuario" placeholder="Email">
                <input type="password" name="clave" placeholder="Password">
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
                <input type="submit" value="Log In">
            </form>
        </div>
    </section>
</body>
</html>
