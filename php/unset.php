<?php
session_start();
if(isset($_SESSION['success'])){
	echo "
	$(document).ready(function() {
		setTimeout(() => {
			$('.session-success').addClass('visually-hidden');
		}, 2500);
	});";
	unset($_SESSION['success']);
} 
if(isset($_SESSION['error'])) {
	echo "
	$(document).ready(function() {
		setTimeout(() => {
			$('.session-error').addClass('visually-hidden');
		}, 2500);
	});";
	unset($_SESSION['error']);
}
?>