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
>Verificacion de campos: se verifica el input del form del usuario, el email y 
la 
*/
if (isset($_POST['log']) && isset($_POST['email']) && isset($_POST['password'])) {
	/*
	>En esta seccion, se incluye un "salt" a la contrasenia para hacerla mas segura.
	Resulta evidente pues, que al momento de realizar un registro, tambien se incluira
	este mismo "salt" a la contrasenia provista por el usuario para mantener uniformidad 
	en la data almacenada. Adicionalmente, se encripta en formato "sha256".

	Nota: de no hacerse exactamente de la misma manera en ambos, el registro y la autenticacion,
	el proceso fallara.
	*/
	$str_salt = 'XyZzy12*_';
	$str_email_check = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
	$str_check = $_POST['password'];
	$str_check = hash('sha256', $str_salt.$_POST['password']);
	/*
	>Verificacion de campos:

	Verificamos la longitud del input del usuario para confirmar que no
	este vacio.

	Posteriormente verificamos si el formato de correo electronico 
	provisto es valido mediante la funcion de php "filter_var".
	*/
	if (strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1) {
		$_SESSION['error'] = "All fields are required";
		header("Location: login.php");
		return;
	}
	if ($str_email_check == false) {
		$_SESSION['error'] = "Invalid email Adress";
		header("Location: login.php");
		return;
	}
		/*
		>Verificacion de datos del usuario en la base de datos: se compara
		la informacion suministrada con la informacion existente en nuestra
		base de datos para proveer la autenticacion.
		*/
		$str_sql = "SELECT user_id, name FROM users WHERE email = :em AND password = :pw";
		$str_stmt = $pdo->prepare($str_sql);
		$str_stmt->execute(array(
			':pw' => $str_check,
			':em' => $str_email_check));
		$row = $str_stmt->fetch(PDO::FETCH_ASSOC);
		/*
		>En caso de ser exitosa la autenticacion, se asigna el nombre
		del usuario a la cookie de sesion para mantener almacenada 
		esta informacion.
		*/
		if ($row !== false) {
			$_SESSION['name'] = $row['name'];
			$_SESSION['user_id'] = $row['user_id'];
			header("Location: index.php");
			return;
		} else {
			/*
			>De no encajar con los registros, el usuario recibe una
			respuesta de error.
			*/
			$_SESSION['error'] = "Invalid credentials";
			header("Location: login.php");
			return;
		}

	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">

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
<body class="d-flex align-items-center bg-background">
<!-- <img class="position-absolute top-50 start-50 translate-middle z-n1" src="images/sin.png"> -->
<main class="form-signin w-100 m-auto z-0">
	<form class="card glass login rounded-3 p-0 form-login" method="post">
	<!-- <img class="mb-4" src="/docs/5.3/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> -->
	<div class="card-header">
		<h1 class="h3 glass-text-danger">Resume Registry</h1>
		<p>Please Login</p>
	</div>
<div class="card-body glass border rounded-0">
		<?php
		/*
		>Seccion para el output de los errores.
		*/
		if (isset($_SESSION['error'])) {
		echo("<div class='p-3 session-error glass-bg-danger glass-text-success rounded-3 my-2'>".$_SESSION['error']."</div>");
		} 
		?>
		<div class="form-floating my-2">
		  <input type="text" class="form-control" id="e-mail" name="email" placeholder="name@example.com">
		  <label for="e-mail">Email address</label>
		</div>

		<div class="form-floating my-2">
		  <input type="password" class="form-control" id="id_1723" name="password" placeholder="Password">
		  <label for="id_1723">Password</label>
		</div>
	<!-- 		<div class="form-check text-start my-2">
			  <input class="form-check-input" type="checkbox" value="remember-me" id="remember" name="remember" disabled>
			  <label class="form-check-label" for="remember">
			    Remember me
			  </label>
			</div> -->
		</div>

		<div class="card-footer" >
			<div class="row me-0">
					<input class="btn glass-btn-success py-2 col-5 btn-sm " type="submit" name="log" value="Log In">
				</div>
		</div>

	</form>
</main>
<!-- Resources -->
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
<script type="text/javascript" src="js/ajax-unset.js"></script>
</body>
</html>