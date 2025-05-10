<?php
$server="localhost";
$utilisateur="root";
$password="";
$db_name="Tourisme";
try{
    $connect= new PDO("mysql:host=".$server.";dbname=".$db_name,$utilisateur,$password);
}
catch (PDOException $ex){
    echo $ex->getMessage();
}
?>