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
require_once "php/pdo.php";
/*

>Creamos una variable para almacenar el query. Este paso podria ser omitido
insertando directamente el query; sin embargo, con el fin de estructurar mejor 
el codigo, decidi separarlo

*/
$str_stmt = $pdo->prepare("SELECT * FROM profile");
$str_stmt->execute();
$comprobation = $str_stmt->fetchAll();

$get_positions = $pdo->prepare("SELECT * FROM position");
$get_positions->execute();
while($row=$get_positions->fetch(PDO::FETCH_ASSOC)){$position_data[]=$row;}
$position_data = json_encode($position_data, JSON_PRETTY_PRINT);

$get_edu = $pdo->prepare("SELECT * FROM education
							JOIN institution
							ON education.institution_id = institution.institution_id");
$get_edu->execute();
while($row=$get_edu->fetch(PDO::FETCH_ASSOC)){$edu_data[]=$row;}
$edu_data = json_encode($edu_data, JSON_PRETTY_PRINT);
?>
<!-- Inicio del "view" -->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro curricular</title>
<!-- Recursos -->
<link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">
<link 
	href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
	rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
	crossorigin="anonymous">
<link rel="stylesheet" 
href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
<script 
	src="https://code.jquery.com/jquery-3.7.1.min.js" 
	integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
	crossorigin="anonymous"></script>
<script 
	src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js" 
	integrity="sha256-sw0iNNXmOJbQhYFuC9OF2kOlD5KQKe1y5lfBn4C9Sjg=" 
	crossorigin="anonymous"></script>
<script
	src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
	integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
	crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" 
	integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" 
	crossorigin="anonymous"></script>
<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"
	integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc"
	crossorigin="anonymous"></script>

<script type="application/javascript">var edit_fields_id=[];</script>
<?= "<script type='application/javascript'>var position_data = $position_data; var edu_data = $edu_data;</script>" ?>
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
 			echo('<div class="text-center">
 					<button class="btn glass-btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#add-modal">Add New Entry</button>
 					</br>
 					or
 					</br>
 					<a class="btn glass-btn-danger btn-sm" href="php/logout.php">Log out</a>
 				</div>');
	 		/*

	 		>A continuacion tenemos el "error handling" de la pagina, donde colocamos los output de error en caso de fallar
	 		alguna accion. Esto puede ser una respuesta proviniente de "add.php", "edit.php" o cualquier otra pagina setteada
	 		para enviarnos errores de sesion.

	 		*/
	 		if (isset($_SESSION['error'])) {
	 			$error_time = time();
	 			echo('<div class="session-error p-3 my-3 glass-bg-danger glass-text-success rounded-3"><b>Error:</b> '.$_SESSION['error'].'</div>');
	 		} 
	 		if (isset($_SESSION['success'])) {
	 			$success_time = time();
	 			echo('<div class="session-success p-3 my-3 glass-bg-success glass-text-danger rounded-3">'.$_SESSION['success'].'</div>');
	 		}
	 		/*
	
			>Adicionalmente, verificamos si existen filas para evitar un mensaje de erorr de PHP, diciendo nosotros mismos al 
			usuario que no se encontro contenido. De haberse encontrado contenido, simplemente lo proyectamos en forma de 
			tabla

 			*/
 			if ($comprobation&&$str_stmt->rowCount()>0) {
 				echo("
 					<table class='table table-hover rounded-3 my-3 text-center'>
 						<thead>
 							<tr>
 								<th>Name</th>
 								<th>Headline</th>
 								<th>Actions</th>
 							<tr>
 						</thead>");
 				foreach($comprobation as $data){
					$id=htmlentities($data['profile_id']);
					$name=htmlentities($data['first_name']);
					$lastname=htmlentities($data['last_name']);
					$email=htmlentities($data['email']);
					$headline=htmlentities($data['headline']);
					$summary=htmlentities($data['summary']);
					echo("<tr><td>");
					echo("<a href='#' data-bs-toggle='modal' data-bs-target='#view-profile-$id'>$name $lastname</a>");
					echo("</td><td>");
					echo(htmlentities($data['headline']));
					echo("</td><td>");
					echo("
						<button class='btn btn-sm glass-btn-danger' data-bs-toggle='modal' data-bs-target='#edit-modal-$id'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button> 
						<button class='btn btn-sm glass-btn-danger' data-bs-toggle='modal' data-bs-target='#delete-modal-$id'>
							<span class='fas fa-trash' aria-hidden='true'></span>
						</button>
						</td>
						</tr>");
					echo "
					<script type='application/javascript'>
						edit_fields_id.push($id);
					</script>
					";
					
				}
				echo('</table>');
				foreach($comprobation as $data){
					$id=htmlentities($data['profile_id']);
					$name=htmlentities($data['first_name']);
					$lastname=htmlentities($data['last_name']);
					$email=htmlentities($data['email']);
					$headline=htmlentities($data['headline']);
					$summary=htmlentities($data['summary']);
					include "html/view_modal.php";
					include "html/edit_modal.php";
					include "html/delete_modal.php";
				}
 			}
			else {
				echo('<div class="text-center">No rows found</div>');
		}
}
?>
</div>
<!-- Recursos -->
<link rel="stylesheet"
	type="text/css"
	href="css/stylesheet.css">
<!-- Recursos -->
<?php require_once "html/add_modal.php";?>
<script type="text/javascript" src="js/edu.js"></script>
<script type="text/javascript" src="js/position.js"></script>
<script type="text/javascript" src="js/ajax-unset.js"></script>
</body>
</html>
