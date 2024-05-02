<?php
/*
>Desarrollado por: 
Rafael Caballero
Ing. en sistemas informaticos
correo electronico: rafaeldc1300@gmail.com
numero de contacto: +507 6542-0323

>Iniciamos estableciendo el cookie de sesion para mantener 
al usuario logeado a lo largo de la pagina y solicitamos
el acceso a la base de datos a traves de "pdo.php"

*/
session_start();
require_once "pdo.php";
/*

>Manejo de cancelacion. En caso de que el usuario cancele
la accion, se le redirige a la pagina principal.

Notese como ningun cambio fue hecho en la base de datos.

*/
if (isset($_POST['cancel'])) {
	header("Location: index.php");
	return;
}

/* 

>Verificacion de autenticacion. De no estar verificado el usuario, la respuesta es un mensaje
de error.

*/
if (! isset($_SESSION['user_id']) && ! isset($_SESSION['name'])) {
	die('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><h1 class="container" style="color: red; text-align: center;">UNAUTHORIZED</h1>');
}
/*

>Si la autenticacion es exitosa, se le permite al usuario interactuar con la pagina y por ende,
con la base de datos.

*/
if (isset($_POST['add']) && isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
	/*

	>Validacion de campos. Confirmamos que los datos introducidos por el usuario esten en el formato
	requerido.

	*/
	if (strlen($_POST['first_name']) < 1 || strlen($_POST['first_name']) > 15 
		|| strlen($_POST['last_name']) < 1|| strlen($_POST['last_name']) > 15
		|| strlen($_POST['email']) < 1
		|| strlen($_POST['headline']) < 1|| strlen($_POST['headline']) > 20
		|| strlen($_POST['summary']) < 1) {

    	$_SESSION['error'] = "All fields are required";
    	header("Location: add.php");
    	return;
	}
	/*

	>Verificacion personalizada para el campo de correo electronico.

	*/
	$emai_check = $_POST['email'];
	if (! filter_var($emai_check, FILTER_VALIDATE_EMAIL) ) {
		$_SESSION['error'] = "Invalid email adress";
		header("Location: add.php");
		return;
	}
	/*

	>Verificacion de los campos dinamicos de "posiciones". Como no sabemos cuales existen,
	iteramos sobre el maximo numero admitido y les verificamos uno a uno.

	*/
	for ($i=0; $i<=8; $i++) {
      if ( ! isset($_POST['year'.$i]) ) continue;
      if ( ! isset($_POST['desc'.$i]) ) continue;
      $year = $_POST['year'.$i];
      $desc = $_POST['desc'.$i];
      if (strlen($year) < 1 || strlen($year) > 4 
      	|| strlen($desc) < 1) {

      	$_SESSION['error'] = "An error occurred. Hint: Check the position fields";
				header("Location: add.php");
				return;
      }
      if ( ! is_numeric($year)) {
	      $_SESSION['error'] = "Year must be numeric";
				header("Location: add.php");
				return;
      }
    }
    /*

    >Validacion de los campos dinamicos de "instuciones educativas".

    */
    for ($j = 0; $j <=8; $j++) {
    	if (! isset($_POST['edu_year'.$j])) continue;
      if (! isset($_POST['edu_school'.$j]))  continue;
      $edu_year = $_POST['edu_year'.$j];
      $edu_school = $_POST['edu_school'.$j]; 
      if (strlen($edu_year) < 1 || strlen($edu_year) > 4
      	|| strlen($edu_school) < 1) {
      	$_SESSION['error'] = "An error occurred. Hint: Check the position fields";
				header("Location: add.php");
				return;
      }
      if (! is_numeric($edu_year)) {
      	$_SESSION['error'] = "Year must be numeric";
				header("Location: add.php");
				return;
      }
    }
/*





>Insercion de datos: Una vez realizada toda la verificacion necesaria, procedemos a insertar
la data a nuestra base de datos

>Primero insertamos los datos escenciales pertenecientes al perfil que estamos creando.





*/
	$sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :ln, :em, :he, :su)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
		':uid' => $_SESSION['user_id'],
		':fn' => $_POST['first_name'],
		':ln' => $_POST['last_name'],
		':em' => $_POST['email'],
		':he' => $_POST['headline'],
		':su' => $_POST['summary']));
	$profile_id = $pdo->lastInsertId();
/*

>Posteriormente, insertamos los datos de las instituciones educativas. 

*/
  $rank_2 = 1;
  for ($j = 0; $j <= 8; $j++) {
  	if (! isset($_POST['edu_year'.$j])) continue;
  	if (! isset($_POST['edu_school'.$j])) continue;
  	$edu_year = $_POST['edu_year'.$j];
  	$edu_school = $_POST['edu_school'.$j];
  	/*

  	>Observese que, al estar la "institucion" separada de la tabla de "educacion", nos 
  	vemos en la necesidad de ingresar a su respectiva tabla como parte de la insercion
  	de datos con el fin de extraer el identificador de la institucion que estamos 
  	ingresando para la data en cadena. 

  	*/
  	$var_stmt =$pdo->prepare("SELECT * FROM institution WHERE name = :name");
 		$var_stmt->execute(array(
  	':name' => $edu_school));
  		while ($thisrow = $var_stmt->fetch(PDO::FETCH_ASSOC)) {
  		$edu_id =  $thisrow['institution_id'];
  	}  
  	/*

  	>Una vez extraemos el identificador de la institucion, procedemos a insertar su respectivo "id"
  	a la tabla en la que estamos trabajando (education)

  	*/
  	$stmt = $pdo->prepare("INSERT INTO education (profile_id, institution_id, rank, year) VALUES (:pid, :inst, :rank, :year)");
		$stmt->execute(array(
			':pid' => $profile_id,
			':inst' => $edu_id,
			':rank' => $rank_2,
			':year' => $edu_year));
		$rank_2++; 
		/*

		>En caso de ser una institucion que no existe en la base de datos, se genera un error.

		*/
  	if (!isset($edu_id)){
  		$_SESSION['error'] = "Institution not recognized";
  		header("Location: add.php");
  		return;
  	}
  }
/*


>Realizamos un proceso similar para la insercion de la data en la tabla de posiciones.
En esta ocasion resulta mas simple puesto que unicamente estamos insertando la data en 
una tabla, por ende, no necesitamos ningun valor externo perteneciente a otra tabla.


*/
	$rank = 1;
	for($i=0; $i<=8; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $stmt = $pdo->prepare('INSERT INTO position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
        $stmt->execute(array(
            ':pid' => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc)
        );
        $rank++;
    }
    /*

    >Finalmente, enviamos al usuario un mensaje de exito, notificandole que el proceso se completo exitosamente 
    y su data deberia proyectarse sin problemas.

    */
    $_SESSION['success'] = "Record added";
    header("Location: index.php");
    return;
   }
?>