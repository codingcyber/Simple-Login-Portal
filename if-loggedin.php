<?php
// check the current page
//echo basename($_SERVER['PHP_SELF']);
if((basename($_SERVER['PHP_SELF']) == 'login.php') || (basename($_SERVER['PHP_SELF']) == 'register.php')){
	if(isset($_SESSION['id']) & !empty($_SESSION['id'])){
		// redirect to dashboard page
		header("location: index.php");
	}
}
?>