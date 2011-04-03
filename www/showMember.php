<?php
include("../projectFiles/init.php");

$templateData = array();
//Memberdaten holen
$Member = new Member($_GET["id"], $mysqli);
$templateData['Member'] = $Member;

$images = $Member->getImages();

foreach($images as $image){
	$Foto = new Foto($image["id"], $mysqli);
	$templateData['fotos'][] = $Foto->getFilename();
	//print "<br />";
}

$templateData['server'] = $_SERVER;

$template = $twig->loadTemplate('member.tpl.htm');
echo $template->render($templateData);