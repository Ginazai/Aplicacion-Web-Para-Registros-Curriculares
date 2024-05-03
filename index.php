<?php
/*
>Desarrollado por: 
Rafael Caballero
Ing. en sistemas informaticos
correo electronico: rafaeldc1300@gmail.com
numero de contacto: +507 6542-0323


>Inicio del MC (modelo/controlador): Se solicita la variable $pdo la cual 
proviene del archivo "pdo.php" con el fin de conectarse a la base de datos

*/
session_start();
require_once "pdo.php";
/*

>Creamos una variable para almacenar el query. Este paso podria ser omitido
insertando directamente el query; sin embargo, con el fin de estructurar mejor 
el codigo, decidi separarlo

*/
$str_stmt = $pdo->query("SELECT * FROM profile");
$comprobation = $str_stmt->fetch(PDO::FETCH_ASSOC);
?>
<!-- Inicio del "view" -->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro curricular</title>
<!-- Recursos -->
<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>
<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<link 
	href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
	rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
	crossorigin="anonymous">
<link rel="stylesheet" 
href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"
	integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc"
	crossorigin="anonymous"></script>
</head>
<!-- Recursos -->
<body class="bg-background">
	<!-- <img class="position-absolute top-50 start-50 translate-middle z-n1" src="images/sin.png"> -->
	<div class="container">
		<h1 class="text-center">Resume Registry</h1>
 		<?php

 		/*

 		>Verificacion de datos / error:Comprobamos si el nombre y el identificador del usuario (user_id) pudieron ser
 		recuperados de la base de datos.

 		Esta accion fue relegada a "login.php". En caso de que no se recuperara existosamente la data, el usuario sera 
 		redireccionado a logearse. 

 		De manera alternativa se puede obsesrvar un boton que solicita al usuario ir a la pagina de login. El resultado
 		sera el mismo: el usuario debera ir a la pagina de login de no haberse autenticado.

 		*/
 		if (! isset($_SESSION['name']) && ! isset($_SESSION['user_id'])) {

 			//echo('<div class="text-center"><a class="btn btn-primary shadow" href="login.php">Please log in</a></div>');
 			
 			header("Location: login.php");
			return;
		}
		/*

		>Verificacion de datos / exito: En caso de resultar exitosa la verificacion del usuario, proyectamos nuestra vista
		de respuesta, donde permitimos al usuario ver la data.

 		*/
		if (isset($_SESSION['name']) && isset($_SESSION['user_id'])) {
			echo('<h3 class="text-center">Welcome '.$_SESSION['name'].'</h3>');
 			echo('<div class="text-center"><button class="btn glass-btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#add-modal">Add New Entry</button></br>'.'or</br>'.'<a class="btn glass-btn-danger btn-sm" href="logout.php">Log out</a></div>');
	 		/*

	 		>A continuacion tenemos el "error handling" de la pagina, donde colocamos los output de error en caso de fallar
	 		alguna accion. Esto puede ser una respuesta proviniente de "add.php", "edit.php" o cualquier otra pagina setteada
	 		para enviarnos errores de sesion.

	 		*/
	 		if (isset($_SESSION['error'])) {
	 			echo('<div class="p-3 my-3 text-danger-emphasis bg-danger-subtle rounded-3">'.$_SESSION['error'].'</div>');
	 			unset($_SESSION['error']);
	 		}
	 		if (isset($_SESSION['success'])) {
	 			echo('<div class="p-3 my-3 text-success-emphasis bg-success-subtle rounded-3">'.$_SESSION['success'].'</div>');
	 			unset($_SESSION['success']);
	 		}
	 		/*
	
			>Adicionalmente, verificamos si existen filas para evitar un mensaje de erorr de PHP, diciendo nosotros mismos al 
			usuario que no se encontro contenido. De haberse encontrado contenido, simplemente lo proyectamos en forma de 
			tabla

 			*/
 			if ($comprobation == false) {
 				echo('<div class="text-center">No rows found</div>');
 			}
			elseif ($comprobation == true) {
				echo("<table class='table table-hover rounded-3' style='margin-top: 15px; margin-left: auto; margin-right: auto;' class='text-center' border='1px'><thead><tr><th>Name</th><th>Headline</th><th>Action</th><tr></thead>");
				while ( $row = $str_stmt->fetch(PDO::FETCH_ASSOC)) {
					$id=htmlentities($row['profile_id']);
					echo("<tr><td>");
					echo("<a href='view.php?profile_id=".$row['profile_id']."'>".htmlentities($row['first_name'])." ".htmlentities($row['last_name'])."</a>");
					echo("</td><td>");
					echo(htmlentities($row['headline']));
					echo("</td><td>");
					echo("<a data-bs-toggle='modal' data-bs-target='#edit-modal-$id'><span class='fas fa-edit' aria-hidden='true'></span></a>"."   "."<a href='delete.php?profile_id=".urldecode(htmlentities($row['profile_id']))."'><span class='fas fa-trash' aria-hidden='true'></a></td></tr>");
		}
		echo('</table>');
	}
}
?>
</div>
<!-- Recursos -->
<script
	src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
	integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
	crossorigin="anonymous"></script>
<script
	src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
	integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
	crossorigin="anonymous"></script>
<link rel="stylesheet"
	type="text/css"
	href="css/stylesheet.css">
<!-- Recursos -->
</body>
</html>
<?php require_once "html/add_modal.php";?>
<?php require_once "html/edit_modal.php";?>