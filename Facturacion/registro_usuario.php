<?php 

    session_start();
    if($_SESSION['rol'] != 1)
    {
        header("location: ./");
    }

    if(!empty($_POST))
    {
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) ||
         empty($_POST['clave']) || empty($_POST['rol']) )
        {
            $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
        }else{
            include "conexion.php";
            $nombre = $_POST['nombre'];
            $email = $_POST['correo'];
            $user = $_POST['usuario'];
            $clave = $_POST['clave'];
            $rol = $_POST['rol'];
        

        $query = mysqli_query($conexion,"SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email'");
        mysqli_close($conexion);
        $result = mysqli_fetch_array($query);
        
        if($result > 0){
            $alert = '<p class="msg_save">El correo o el usuario ya existe</p>';
        }else{
            $query_insert = mysqli_query($conexion, "INSERT INTO usuario(nombre, correo, usuario, clave, rol)
            VALUES('$nombre','$email','$user','$clave','$rol')");

            if ($query_insert){
                $alert = '<p class="msg_save">Usuario creado correctamente</p>';
            }else{
                $alert = '<p class="msg_error">Error alcrear el usuario</p>';
            }
        }
    }
    mysqli_close($conexion);
    
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/scripts.php"; ?>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>Registro Usuario</title>
</head>
<body>
<?php include "include/header.php"; ?>
	<section id="container">
		<div class="form_register">
            <h1>Registro Usuario</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>


            <form action="" method = "POST">
                <label for="">Nombre</label>
                <input type="text" name = "nombre" id = "nombre" placeholder = "Nombre Completo">
                <label for="">Correo</label>
                <input type="email" name = "correo" id = "correo" placeholder = "Correo Electronico">
                <label for="">Usuario</label>
                <input type="text" name = "usuario" id = "usuario" placeholder = "Usuario">
                <label for="">Clave</label>
                <input type="text" name = "clave" id = "clave" placeholder = "Clave de acceso">
                <label for="rol">Tipo de usuario</label>
                <?php
                
                
                ?>
                <select name="rol" id="rol">

                    <option value="1">Administrador</option>
                    <option value="2">Supervisor</option>
                    <option value="7">Vendedor</option>
                </select>
                <input type="submit" value="Crear usuario" class="btn_save">
            </form>

        </div>
	</section>
	<?php include "include/footer.php"; ?>
</body>
</html>
