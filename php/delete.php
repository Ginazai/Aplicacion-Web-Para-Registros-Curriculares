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

if (isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
	$sql = "DELETE FROM profile WHERE profile_id = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
		':id' => $_GET['profile_id']));
	$_SESSION['success'] = "Record deleted";
	header('Location: ../index.php');
	return;
}
//Error_Check
$sql = "SELECT * FROM profile WHERE profile_id = :pid";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':pid' => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
	$_SESSION['error'] = "Bad id for selected element";
	header('Location: ../index.php');
	return;
}
?>