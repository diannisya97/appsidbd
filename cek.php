<?php 
// jika belum login 

if(!isset($_SESSION['log'])){
    header('location:login.php');
} else {
    header('login.php');
}

?>

