<?php 

    session_start();
    if($_SESSION['rol'] != 1)
    {
        header("location: ./");
    }
include "conexion.php";

?>




<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>Lista de Usuarios</title>
</head>
<body>
	<header>
		<div class="header">
			
			<h1>Sistema Facturación</h1>
			<div class="optionsBar">
				<p>República Dominicana, <?php echo fechaC();?></p>
				<span>|</span>
				<span class="user"><?php echo $_SESSION['user'].'-'.$_SESSION['rol'];?></span>
				<img class="photouser" src="img/user.png" alt="Usuario">
				<a href="#"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
			</div>
		</div>
		<nav>
			<ul>
				<li><a href="#">Inicio</a></li>
				<li class="principal">
					<a href="#">Usuarios</a>
					<ul>
					<li><a href="registro_usuario.php">Nuevo Usuario</a></li>
						<li><a href="lista_usuarios">Lista de Usuarios</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Clientes</a>
					<ul>
						<li><a href="#">Nuevo Cliente</a></li>
						<li><a href="#">Lista de Clientes</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Proveedores</a>
					<ul>
						<li><a href="#">Nuevo Proveedor</a></li>
						<li><a href="#">Lista de Proveedores</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Productos</a>
					<ul>
						<li><a href="#">Nuevo Producto</a></li>
						<li><a href="#">Lista de Productos</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Facturas</a>
					<ul>
						<li><a href="#">Nuevo Factura</a></li>
						<li><a href="#">Facturas</a></li>
					</ul>
				</li>
			</ul>
		</nav>
	</header>
	<section id="container">
        <?php
        $busqueda = strtolower($_REQUEST['busqueda']);
        if(empty($busqueda))
        {
            header("location: lista_usuarios.php");
            mysqli_close($conexion);
        }
        
        
        ?>
        <h1>Lista de Usuarios</h1>
        <a href="registro_usuario.php" class="btn_new">Crear usuarios</a>

		<form action="buscar_usuario.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda ?>">
			<input type="submit" value="Buscar" class="btn_search">
		</form>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuarios</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>

            <?php

          $rol = '';

          if($busqueda == 'administrador')

           {

            $rol = "OR rol LIKE '%1%' ";

          

            }else if($busqueda == 'supervisor'){

               $rol= "OR rol LIKE '%2%' ";

            }else if($busqueda == 'vendedor') {

           $rol =" OR rol LIKE '%7%' ";

}

			//Paginador
            $sql_registe = mysqli_query($conexion, "SELECT COUNT(*) as total_registro FROM usuario 
                                       WHERE
                                        (idusuario LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR correo LIKE '%$busqueda%' OR usuario LIKE '%$busqueda%' $rol)

                                        AND
                                          estatus = 1");

            $result_register = mysqli_fetch_array($sql_registe);
            $total_registro = $result_register['total_registro'];


            $por_pagina = 8;

            if(empty($_GET['pagina']))
            {
                $pagina = 1;
            }else{
                $pagina = $_GET['pagina'];
            }

            $desde = ($pagina-1) * $por_pagina;
            $total_paginas = ceil($total_registro / $por_pagina);

            $query = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol 
            FROM usuario u 
            INNER JOIN rol r ON u.rol = r.idrol 
            WHERE 
                (u.idusuario LIKE '%$busqueda%' OR
                u.nombre LIKE '%$busqueda%' OR
                u.correo LIKE '%$busqueda%' OR
                u.usuario LIKE '%$busqueda%' OR
                r.rol LIKE '%$busqueda%')
            AND 
                estatus = 1 
            ORDER BY u.idusuario ASC 
            LIMIT $desde, $por_pagina");

            mysqli_close($conexion);
        

            $result =mysqli_num_rows($query);

            if($result > 0)
            {
                while ($data = mysqli_fetch_array($query)){

                ?>

                <tr>
                <td><?php echo $data ["idusuario"] ?></td>
                <td><?php echo $data ["nombre"] ?></td>
                <td><?php echo $data ["correo"] ?></td>
                <td><?php echo $data ["usuario"] ?></td>
                <td><?php echo $data ["rol"] ?></td>
                <td>
				<a href="editar_usuario.php?id=<?php echo $data["idusuario"]; ?>" class="link_edit">Editar</a>
				<?php if($data["idusuario"] != 1){ ?>
				
				
				|	<a href="eliminar_confirmar_usuario.php?id=<?php echo $data["idusuario"]; ?>" class="link_delete">Eliminar</a>
					<?php  } ?>
				</td>
			</tr>
			<?php
			}
		}
		?>
            
        </table>

        <?php
        if($total_registro !=0)
        {

        
        
        
        ?>

		<div class="paginador">
            <ul>
            <?php
            if($pagina != 1)
            {
              ?>  
            
                <li><a href="?pagina= <?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|<<</a></li>
                <li><a href="?pagina= <?php echo $pagina-1; ?>&busqueda=<?php echo $busqueda; ?>"><<</a></li>
                <?php
                }
                for ($i=1; $i <= $total_paginas; $i++){
                    if($i == $pagina)
                    {
                        echo '<li class= "pageSelected">'.$i.'</li>';
                    }else{
                        echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
                    }
                    
                }

                if($pagina != $total_paginas)
                {
                ?>
                <li><a href="?pagina= <?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>">>></a></li>
                <li><a href="?pagina= <?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?>">>>|</a></li>
                <?php } ?>
            </ul>
        </div>
<?php } ?>
		<?php "include/footer.php"?>
	</section>
</body>
</html>