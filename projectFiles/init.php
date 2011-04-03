<?php
include('config.php');

//Funktionen/Methoden
function __autoload($class){
	include("classes/class.".$class.".php");	
}

//Datenbankverbindung machen
$mysqli = new Database(
	$db_host, $db_username, $db_passwort, $db_dbname);