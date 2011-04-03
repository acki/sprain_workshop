<?php
include("../projectFiles/init.php");

//Memberdaten holen
$Member = new Member($_GET["id"], $mysqli);
print $Member->getName();
print '<br />';

$images = $Member->getImages();

foreach($images as $image){
	$Foto = new Foto($image["id"], $mysqli);
	print $Foto->getFilename();
	print "<br />";
}