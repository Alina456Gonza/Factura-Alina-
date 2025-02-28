<?php 

    session_start();
    if($_SESSION['rol'] != 1)
    {
        header("location: ./");
    }
include "conexion.php";



include "conexion.php";

if(!empty($_POST))
{
    if($_POST['idusuario'] == 1){
        header('Location: lista_usuarios.php');
        mysqli_close($conexion);
        exit;
    }
    $idusuario = $_POST['idusuario'];
   // $query_delete = mysqli_query($connection, "DELETE FROM usuario WHERE idusuario = $idusuario");
   $query_delete = mysqli_query($conexion,"UPDATE usuario SET estatus = 0 WHERE idusuario = $idusuario");
    if($query_delete){
        header('Location: lista_usuarios.php');
    }else{
        echo "Error al eliminar";
    }
}

if(empty($_REQUEST['id']) || $_REQUEST['id'] == 1)
{
    header('Location: lista_usuarios.php');
    mysqli_close($conexion);
}else{
    include "conexion.php";

    $idusuario = $_REQUEST['id'];

    $query = mysqli_query($conexion,"SELECT u.nombre, u.usuario, r.rol FROM usuario u INNER JOIN rol r  
    ON u.rol = r.idrol WHERE u.idusuario = $idusuario");
    mysqli_close($conexion);
    $result = mysqli_num_rows($query);

    if ($result > 0){
        while($data = mysqli_fetch_array($query)) {
            $nombre = $data['nombre'];
            $usuario = $data['usuario'];
            $rol = $data['rol'];
        }
    }else{
        header('Location: lista_usuarios.php');
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/scripts.php"; ?>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>Eliminar Usuario</title>
</head>
<body>
<?php include "include/header.php"; ?>
	<section id="container">
		<div class="data_delete">
            <h2>¿Está seguro de eleiminar en siguiente usuario?</h2>
            <p>
            Nombre:<span><?php echo $nombre; ?></span>
            Usuario:<span><?php echo $usuario; ?></span>
            Tipo de Usuario:<span><?php echo $rol; ?></span>
            </p>

            <form action="" method = "post">
                <input type="hidden" value="<?php echo $idusuario; ?>" name = "idusuario">
                <a href="lista_usuarios.php" class= "btn_cancel">Cancelar</a>
                <input type="submit" value = "Aceptar" class= "btn_ok">
            </form>
        </div>
	</section>
	<?php include "include/footer.php"; ?>
</body>
</html>