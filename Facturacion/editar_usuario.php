<?php 

    session_start();
    if($_SESSION['rol'] != 1)
    {
        header("location: ./");
    }
include "conexion.php";



include "conexion.php";

if(!empty($_POST)) {
    $alert = '';

    if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || 
       empty($_POST['rol'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {
        // Validar si el idusuario existe en el formulario
        if (!isset($_POST['idUsuario']) || $_POST['idUsuario'] == '') {
            die('<p class="msg_error">Error: ID de usuario no válido.</p>');
        }

        $idusuario = $_POST['idUsuario'];
        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $user = $_POST['usuario'];
        $clave = isset($_POST['clave']) ? $_POST['clave'] : '';
        $rol = $_POST['rol'];

        // Validar si el usuario o correo ya existen en otro usuario
        $query = mysqli_query($conexion, 
            "SELECT * FROM usuario 
             WHERE (usuario = '$user' AND idusuario != '$idusuario') 
             OR (correo = '$email' AND idusuario != '$idusuario')");

        if(mysqli_num_rows($query) > 0) {
            $alert = '<p class="msg_error">El correo o el usuario ya existe.</p>';
        } else {
            // Si la clave está vacía, no actualizarla
            if(empty($clave)) {
                $sql_update = mysqli_query($conexion, 
                    "UPDATE usuario 
                     SET nombre='$nombre', correo='$email', usuario='$user', rol='$rol' 
                     WHERE idusuario='$idusuario'");
            } else {
                $sql_update = mysqli_query($conexion, 
                    "UPDATE usuario 
                     SET nombre='$nombre', correo='$email', usuario='$user', clave='$clave', rol='$rol' 
                     WHERE idusuario='$idusuario'");
            }

            if ($sql_update) {
                $alert = '<p class="msg_save">Usuario actualizado correctamente</p>';
            } else {
                $alert = '<p class="msg_error">Error al actualizar el usuario.</p>';
            }
        }
    }
    mysqli_close($conexion);
}

// Mostrar datos del usuario
if(empty($_GET['id'])) {
    header('Location: lista_usuarios.php');
    mysqli_close($conexion);
}

$iduser = $_GET['id'];
$sql = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, 
                                      (u.rol) as idrol, (r.rol) as rol 
                               FROM usuario u 
                               INNER JOIN rol r ON u.rol = r.idrol 
                               WHERE idusuario = '$iduser'");
                               mysqli_close($conexion);

if(mysqli_num_rows($sql) == 0) {
    header('Location: lista_usuarios.php');
    exit();
} else {
    $data = mysqli_fetch_array($sql);
    $iduser = $data['idusuario'];
    $nombre = $data['nombre'];
    $correo = $data['correo'];
    $usuario = $data['usuario'];
    $idrol = $data['idrol'];
    $rol = $data['rol'];



        if($idrol == 1){
            $option = '<option value = "'.$idrol.'">'.$rol.'</option>';
        }else if($idrol == 2){
            $option = '<option value = "'.$idrol.'">'.$rol.'</option>';
        }else if($idrol == 7){
            $option = '<option value = "'.$idrol.'">'.$rol.'</option>';
        }
       
    }


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php include "include/scripts.php"; ?> 
    <title>Actualizar Usuario</title>
    <link rel="stylesheet" href="css_registro.css">
</head>
<body>
    <?php include "include/header.php"; ?>  
    <section id="container">
        <div class="form_register">
            <h1>Actualizar Usuario</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
            <form action="" method="POST">
               <input type="hidden" name="idUsuario" value="<?php echo $iduser; ?>">               
            <label for="">Nombre</label>
                <input type="text" name = "nombre" id = "nombre" placeholder = "Nombre Completo" value = "<?php echo $nombre; ?>">
                <label for="">Correo</label>
                <input type="email" name = "correo" id = "correo" placeholder = "Correo Electronico" value = "<?php echo $correo; ?>">
                <label for="">Usuario</label>
                <input type="text" name = "usuario" id = "usuario" placeholder = "Usuario" value = "<?php echo $usuario; ?>">
                <label for="">Clave</label>
                <input type="text" name = "clave" id = "clave" placeholder = "Clave de acceso">

                <label for="rol">Tipo Usuario</label>
                <?php
                include "conexion.php";
                $query_rol = mysqli_query($conexion, "SELECT * FROM rol");
                mysqli_close($conexion);
                $result_rol = mysqli_num_rows($query_rol);
                ?>
                <select name="rol" id="rol">
                    <option value="1">Administrador</option>
                    <option value="2">Supervisor</option>
                    <option value="7">Vendedor</option>
                </select>

                <input type="submit" value="Actualizar Usuario" class="btn_save">
            </form>
        </div>
    </section>

    <?php include "include/footer.php"; ?>
</body>
</html>
