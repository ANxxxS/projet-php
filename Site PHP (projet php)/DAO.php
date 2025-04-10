<?php 
$servername="localhost";
$rootname="root";
$password="";
$dbname="BTS";
$con =  mysqli_connect($servername,$rootname,$password,$dbname);
if(!$con){
    echo"ERROR DE CONNEXION ".mysqli_connect_error();}
?>