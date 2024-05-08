<?php 
/*
>Desarrollado por: 
Rafael Caballero
Ing. en sistemas informaticos
correo electronico: rafaeldc1300@gmail.com
numero de contacto: +507 6542-0323


>

*/
session_start();
require_once "pdo.php";
//Log Protection
if (! isset($_SESSION['user_id']) && ! isset($_SESSION['name'])) {
	die('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><h1 class="container" style="color: red; text-align: center;">UNAUTHORIZED</h1>');
}
//profile_id protection
if (! isset($_GET['profile_id'])) {
	$_SESSION['error'] = "Bad id for user";
	header("Location: index.php");
	return;
}
//Validation
if (isset($_GET['profile_id'])&&
	isset($_SESSION['user_id'])&&
	isset($_SESSION['name'])) {
	//Data Submit validation
	if (isset($_POST['first_name'])&&
		isset($_POST['last_name'])&&
		isset($_POST['email'])&&
		isset($_POST['headline'])&&
		isset($_POST['summary'])) {
		//Field Protection
		if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1|| strlen($_POST['email'])
   		 < 1 || strlen($_POST['headline']) < 1|| strlen($_POST['summary']) < 1) {
			$_SESSION['error'] = "All fields are required";
			header('Location: ../index.html');
			return;
		}
		//email validation
		$email_check = $_POST['email'];
		if (! filter_var($email_check, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['error'] = "Invalid email adress";
			header('Location: ../index.html');
			return;
		}
		//Dynamic fields validation
		for($i=0; $i<=8; $i++) {
       	 if ( ! isset($_POST['year'.$i]) ) continue;
       	 if ( ! isset($_POST['desc'.$i]) ) continue;
       	 $year = $_POST['year'.$i];
       	 $desc = $_POST['desc'.$i];
       	 if ( strlen($year) < 1 || strlen($desc) < 1 ) {
       	    $_SESSION['error'] = "All fields are required";
			//header("Location: edit.php");
			return;
        }
        // if ( ! is_numeric($year) ) {
        //     $_SESSION['error'] = "Year must be numeric";
		// 	header("Location: ../index.php");
		// 	return;
        // }
   	}

	/************************************************************
	 ************************************************************
	 ***      ******* *****        ***** ************************
	 *** ****** **** * *******  ******* * ***********************
	 *** ****** *** *** ******  ****** *** **********************
	 *** ****** **       *****  *****       *********************
	 ***       ** ******* ****  **** ******* ********************
	 ************************************************************/
		//data modification
		$sql = "UPDATE profile SET first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su
		WHERE profile_id = :pid";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':pid' => $_REQUEST['profile_id'],
			':fn' => $_POST['first_name'],
			':ln' => $_POST['last_name'],
			':em' => $_POST['email'],
			':he' => $_POST['headline'],
			':su' => $_POST['summary']));
		// Clear out the old position entries
		// $stmt = $pdo->prepare('DELETE FROM position WHERE profile_id = :pid');
		// $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
		// Insert the position entries
		// $rank = 0;
		// for($i=0; $i<=9; $i++) {
  		//   if ( ! isset($_POST['year'.$i]) ) continue;
  		//   if ( ! isset($_POST['desc'.$i]) ) continue;
   		//  $year = $_POST['year'.$i];
   		//  $desc = $_POST['desc'.$i];
   		//  $stmt = $pdo->prepare('INSERT INTO position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc )');
   		//  $stmt->execute(array(
    	// 	    ':pid' => $_REQUEST['profile_id'],
    	// 	    ':rank' => $rank,
    	// 	    ':year' => $year,
    	// 	    ':desc' => $desc));
   		//  $rank++;
		// }
		//For education table
		// $e_stmt = $pdo->prepare('DELETE FROM education WHERE profile_id = :pid');
		// $e_stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

		// $r = 0;
		// for($j=0; $j<=9; $j++) {
		// 	if ( ! isset($_POST['edu_year'.$j]) ) continue;
		//   if ( ! isset($_POST['edu_school'.$j]) ) continue;
 		//  $e_year = $_POST['edu_year'.$j];
 		//  $e_inst = $_POST['edu_school'.$j];

 		//  $i_stmt = "SELECT * FROM institution WHERE name = :inst";
 		//  $i_stmt = $pdo->prepare($i_stmt);
 		//  $i_stmt->execute(array(
 		//  	':inst'=> $e_inst
 		//  ));
 		//  while ($i_row = $i_stmt->fetch(PDO::FETCH_ASSOC)) {
 		//  	$inst_id = $i_row['institution_id'];

 		//  	$e_stmt = $pdo->prepare('INSERT INTO education (profile_id, institution_id, rank, year) VALUES ( :pid, :inst, :rank, :year )');
 		//  	$e_stmt->execute(array(
  		//     	':pid' => $_REQUEST['profile_id'],
  		//     	':inst' => $inst_id,
  		//     	':rank' => $r,
  		//     	':year' => $e_year));
 		//  	$r++;
 		//  	}
		// }


		$_SESSION['succes'] = "Record modified";
		header("Location: ../index.php");
		return;
	}
}
//Cancel
if (isset($_POST['cancel'])) {
	//header("Location: index.php");
	return;
}



//Database Error Validaton
$sql = "SELECT * FROM profile WHERE profile_id = :pid";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
':pid' => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row == false) {
	$_SESSION['error'] = "Invalid profile";
	//header("Location: index.php");
	return;
}
//Second-time data
$sql2 = "SELECT * FROM position WHERE profile_id = :pid";
$stmt2 = $pdo->prepare($sql);
$stmt2->execute(array(
':pid' => $_GET['profile_id']));
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

for ($i = 0; $i <=8; $i++) {
	if (! isset($row2['year'.$i])) continue;
	if (! isset($row2['desc'.$i])) continue;
	$year = htmlentities($row2['year'.$i]);
	$desc= htmlentities($row2['desc'.$i]);
}
//Retrieving Values
$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$he = htmlentities($row['headline']);
$su = htmlentities($row['summary']);
?>
