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
if (!isset($_SESSION['user_id'])&&
	!isset($_SESSION['name'])) {
	die('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><h1 class="container" style="color: red; text-align: center;">UNAUTHORIZED</h1>');
	$_SESSION['error'] = "Please login";
	header("Location: ../login.php");
	return;
}
//profile_id protection
if (!isset($_GET['profile_id'])) {
	$_SESSION['error'] = "Bad id for user";
	header("Location: ../index.php");
	return;
}
//Validation
if (isset($_GET['profile_id'])&&
	isset($_SESSION['user_id'])&&
	isset($_SESSION['name'])) {
	//Data Submit validation
	if (isset($_POST['edit-fname'])&&
		isset($_POST['edit-lname'])&&
		isset($_POST['edit-email'])&&
		isset($_POST['edit-headline'])&&
		isset($_POST['edit-summary'])) {
		//Field Protection
		if (strlen($_POST['edit-fname']) < 1 || strlen($_POST['edit-lname']) < 1|| strlen($_POST['edit-email'])
   		 < 1 || strlen($_POST['edit-headline']) < 1|| strlen($_POST['edit-summary']) < 1) {
			$_SESSION['error'] = "All fields are required";
			//header('Location: ../index.html');
			return;
		}
		//email validation
		$email_check = $_POST['edit-email'];
		if (! filter_var($email_check, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['error'] = "Invalid email adress";
			//header('Location: ../index.html');
			return;
		}   	
		//Database Error Validaton
		$sql = "SELECT * FROM profile WHERE profile_id = :pid";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
		':pid' => $_GET['profile_id']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row==false) {
			$_SESSION['error'] = "Bad id for profile";
			//header("Location: ../index.php");
			return;
		}
		var_dump($_POST['Edit_edu_years']);
		echo "<br>";
		//var_dump($_POST['Edit_edu_inst']);
		if(isset($_POST['Edit_edu_years'])&&
			isset($_POST['Edit_edu_inst'])){
			echo "inside if";
			$clear_data=$pdo->prepare("DELETE FROM education WHERE profile_id = :pid");
			$clear_data->execute([':pid'=>$_GET['profile_id']]);
			$edu_years=$_POST['Edit_edu_years'];
			$edu_inst=$_POST['Edit_edu_inst'];
			$edu_years_keys=array_keys($edu_years);
			$edu_inst_keys=array_keys($edu_inst);

			var_dump($edu_years);
			echo "<br>";
			var_dump($edu_inst);
			if(count($edu_years)==count($edu_inst)){

				for($i=0;$i<count($edu_years);$i++){
					$curr_year=$edu_years[$edu_years_keys[$i]];
					$curr_inst=$edu_inst[$edu_inst_keys[$i]];
					if($curr_year!=''&&$curr_inst!=''){
						$get_inst=$pdo->prepare("SELECT * FROM institution WHERE name = :name");
						$get_inst->execute([':name' => $curr_inst]);
						$ret_inst=$get_inst->fetchAll();
						if($ret_inst&&$get_inst->rowCount()>0){
							$institution_id=$ret_inst[0]['institution_id'];
							$insert_data=$pdo->prepare("INSERT INTO education (profile_id,institution_id,year)
														VALUES (:pid,:inst_id,:year)");
							$insert_data->execute([
								':pid' => $_GET['profile_id'],
								':inst_id' => $institution_id,
								':year' => $curr_year
							]);
						} else {
							$_SESSION['error'] = "Failed added institution (institution not found)";
							//header("Location: ../index.php");
							return;
						}
					} else {
						$_SESSION['error'] = "All education fields are required";
						//header("Location: ../index.php");
						return;
					}
				}
			} else {
				$_SESSION['error'] = "There was an error processing education information. Check the fields and try again";
				//header("Location: ../index.php");
				return;
			}
		}
		$_SESSION['succes'] = "Record successfully modified!";
		//header("Location: ../index.php");
		return;
	}
}
?>
