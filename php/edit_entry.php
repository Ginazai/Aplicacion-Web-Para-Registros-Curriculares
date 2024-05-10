<?php 
/*
>Desarrollado por: 
Rafael Caballero
Ing. en sistemas informaticos
correo electronico: rafaeldc1300@gmail.com
numero de contacto: +507 6542-0323
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
			header('Location: ../index.html');
			return;
		}
		//email validation
		$email_check = $_POST['edit-email'];
		if (! filter_var($email_check, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['error'] = "Invalid email adress";
			header('Location: ../index.html');
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
			header("Location: ../index.php");
			return;
		}
		//insert education reocrds
		if(isset($_POST['Edit_edu_years'])&&
			isset($_POST['Edit_edu_inst'])){
			$clear_data=$pdo->prepare("DELETE FROM education WHERE profile_id = :pid");
			$clear_data->execute([':pid'=>$_GET['profile_id']]);
			$edu_years=$_POST['Edit_edu_years'];
			$edu_inst=$_POST['Edit_edu_inst'];
			$edu_years_keys=array_keys($edu_years);
			$edu_inst_keys=array_keys($edu_inst);
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
							header("Location: ../index.php");
							return;
						}
					} else {
						$_SESSION['error'] = "All education fields are required";
						header("Location: ../index.php");
						return;
					}
				}
			} else {
				$_SESSION['error'] = "There was an error processing education information. Check the fields and try again";
				header("Location: ../index.php");
				return;
			}
		}
		//insert position records
		if(isset($_POST['Edit_years'])&&
			isset($_POST['Edit_descriptions'])){
			$clear_positions=$pdo->prepare("DELETE FROM position WHERE profile_id = :pid");
			$clear_positions->execute([':pid'=>$_GET['profile_id']]);
			$edit_years=$_POST['Edit_years'];
			$edit_descs=$_POST['Edit_descriptions'];
			$edit_years_keys=array_keys($edit_years);
			$edit_descs_keys=array_keys($edit_descs);
			if(count($edit_years)==count($edit_descs)){
				for($i=0;$i<count($edit_descs);$i++){
					$curr_year=$edit_years[$edit_years_keys[$i]];
					$curr_desc=$edit_descs[$edit_descs_keys[$i]];
					if($curr_year!=''&&$curr_desc!=''){
						$insert_position = $pdo->prepare("INSERT INTO position (profile_id,year,description) VALUES (:pid,:year,:description)");
						$insert_position->execute(array(
							':pid' => $_GET['profile_id'],
							':year' => $curr_year,
							':description' => $curr_desc
						));
					} else {
						$_SESSION['error'] = "Failed to add positions. All position fields are required";
						header("Location: ../index.php");
						return;
					}
				}
			} else {
				$_SESSION['error'] = "There was an error processing positions information. Check the fields and try again";
				header("Location: ../index.php");
				return;
			}
		}
		$update_data=$pdo->prepare("UPDATE profile SET user_id=:uid,first_name=:fname,last_name=:lname,email=:em,headline=:hl,summary=:sm WHERE profile_id=:pid");
		$update_data->execute([
			':pid' => $_GET['profile_id'],
			':uid' => $_SESSION['user_id'],
			':fname' => $_POST['edit-fname'],
			':lname' => $_POST['edit-lname'],
			':em' => $_POST['edit-email'],
			':hl' => $_POST['edit-headline'],
			':sm' => $_POST['edit-summary']
		]);
		$_SESSION['success'] = "Record successfully modified";
		header("Location: ../index.php");
		return;
	}
}
?>
