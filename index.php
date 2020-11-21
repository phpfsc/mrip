<?php
session_start();
if(isset($_SESSION['login']) && !empty($_SESSION['login'])){
	header('Location:default/index.php');
	
}
else{
	  header('Location:login.php');
    }
	
?>