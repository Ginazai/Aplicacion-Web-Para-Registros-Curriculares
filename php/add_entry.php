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

>Verificacion de autenticacion. De no estar verificado el usuario, la respuesta es un mensaje
de error.
*/
if (! isset($_SESSION['user_id']) && ! isset($_SESSION['name'])) {
	die('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><h1 class="container" style="color: red; text-align: center;">UNAUTHORIZED</h1>');
} else {
	/*
	>Si la autenticacion es exitosa, se le permite al usuario interactuar con la pagina y por ende,
	con la base de datos.
	*/
	/*
	>Validacion de campos. Confirmamos que los datos introducidos por el usuario esten en el formato
	requerido.
	*/
	if (strlen($_POST['fname']) < 1 || strlen($_POST['fname']) > 15 
		|| strlen($_POST['last_name']) < 1|| strlen($_POST['last_name']) > 15
		|| strlen($_POST['email']) < 1
		|| strlen($_POST['headline']) < 1|| strlen($_POST['headline']) > 20
		|| strlen($_POST['summary']) < 1) {

    	$_SESSION['error'] = "All fields are required";
    	header("Location: ../index.php");
    	return;
	}
	/*
	>Verificacion personalizada para el campo de correo electronico.
	*/
	$emai_check = $_POST['email'];
	if (! filter_var($emai_check, FILTER_VALIDATE_EMAIL) ) {
		$_SESSION['error'] = "Invalid email adress";
		header("Location: ../index.php");
		return;
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
		':fn' => $_POST['fname'],
		':ln' => $_POST['last_name'],
		':em' => $_POST['email'],
		':he' => $_POST['headline'],
		':su' => $_POST['summary']));
	$profile_id = $pdo->lastInsertId();
	  /*
  >Validacion de los campos dinamicos de "instuciones educativas".
  */
  if(isset($_POST['Add_edu_years'])&&
			isset($_POST['Add_edu_inst'])){
  	$edu_years = $_POST['Add_edu_years'];
  	$edu_inst = $_POST['Add_edu_inst'];
  	$edu_years_keys = array_keys($edu_years);
  	$edu_inst_keys = array_keys($edu_inst);
  	if(count($edu_years)==count($edu_inst)){
  		for($i=0;$i<count($edu_years);$i++){
  			$curr_year = $edu_years[$edu_years_keys[$i]];
  			$curr_inst = $edu_inst[$edu_inst_keys[$i]];
  			echo $curr_inst . "<br>"; 
  			if($curr_year!=''&&$curr_inst!=''){
  				$check_inst = $pdo->prepare("SELECT * FROM institution WHERE name = :inst");
  				$check_inst->execute([':inst'=>$curr_inst]);
  				$data = $check_inst->fetchAll();
  				if($data&&$check_inst->rowCount()>0){
  					echo "inside if for insertion<br>";
  					$inst_id = $data[0]['institution_id'];
  					$institution = $data[0]['name'];

  					$insert_institution = $pdo->prepare("INSERT INTO education (profile_id,institution_id,year) 
  																							VALUES (:pid,:inst_id,:year)");
  					$insert_institution->execute([
  						':pid' => $profile_id,
  						':inst_id' => $inst_id,
  						':year'=>	$curr_year
  					]);
  				} else {
  					$_SESSION['error'] = "There was an error with the institution name. Verify and try again";
						header("Location: ../index.php");
						return;}
  			} else {
  				$_SESSION['error'] = "All education fields must be filled";
					header("Location: ../index.php");
					return;}
  		}
  	} else {
  		$_SESSION['error'] = "There was an error in the education fields. Verify and try again";
			header("Location: ../index.php");
			return;}
  }
	/*
	>Verificacion de los campos dinamicos de "posiciones". Como no sabemos cuales existen,
	iteramos sobre el maximo numero admitido y les verificamos uno a uno.
	*/
	if(isset($_POST['Add_years'])&&
			isset($_POST['Add_descriptions'])){
		$year_array = $_POST['Add_years'];
		$description_array = $_POST['Add_descriptions'];
		$year_keys = array_keys($year_array);
		$description_keys = array_keys($description_array);
		if(count($year_array)==count($description_array)){
			for($i=0;$i<count($year_array);$i++){
				$curr_year = $year_array[$year_keys[$i]];
				$curr_desc = $description_array[$description_keys[$i]];
				if($curr_year!=''&&$curr_desc!=''){
					$insert_position = $pdo->prepare("INSERT INTO position (profile_id,year,description) VALUES (:pid,:year,:description)");
					$insert_position->execute(array(
						':pid' => $profile_id,
						':year' => $curr_year,
						':description' => $curr_desc
					));
				} else {
					$_SESSION['error'] = "All position fields are required";
					header("Location: ../index.php");
					return;}
			}
		} else {
			$_SESSION['error'] = "There was an error in the position fields. Verify and try again";
			header("Location: ../index.php");
			return;}
	}
  $_SESSION['success'] = "Record successfully added!";
  header("Location: ../index.php");
  return;
}
?>