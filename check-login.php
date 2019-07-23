<?php
session_start();
if(isset($_SESSION['login']) & ($_SESSION['login'] == true)){

}else{
	// redirect user to login page
	header("location: login.php");
}
if(isset($_SESSION['id']) & !empty($_SESSION['id'])){

}else{
	// redirect user to login page
	header("location: login.php");
}
if(isset($_SESSION['last_login']) & !empty($_SESSION['last_login'])){

}else{
	// redirect user to login page
	header("location: login.php");
}
?>