<?php

	include('../projectFiles/init.php');
	
	if(isset($_POST['action'])) {
		if($_POST['action'] == 'cookieForm') {
			$timestamp = time() + 30 * 24 * 60 * 60;
			setcookie('vorname', $_POST['vorname'], $timestamp, "/");
		}
	}
	
	$template = $twig->loadTemplate('form.tpl.htm');
	
	$templateData = array();
	$templateData['server'] = $_SERVER;
	$templateData['title'] = 'Form Test';
	print $template->render($templateData);
	