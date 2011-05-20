<?php
error_reporting(E_ALL);
include('config.php');

session_start();

//Funktionen/Methoden
function __autoload($class){
	include("classes/".str_replace('_','/',$class).".php");	
}

//Datenbankverbindung machen
//mysqli = new Database(
//	$db_host, $db_username, $db_passwort, $db_dbname);
	
//Template Engine initialisieren
//$loader = new Twig_Loader_Filesystem($templatePath);
//$twig = new Twig_Environment($loader, array(
//  'cache' => false,
//));