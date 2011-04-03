<?php

	include('../projectFiles/init.php');
	
	if(isset($_POST['action'])) {
		if($_POST['action']=="loginForm") {
			$Member = Member::checkLogin($_POST['username'], $_POST['password'], $mysqli);
			if(!$Member) {
				$loginMessage = "failed";
			} else {
				$loginMessage = "aaaah okkkey! " . $Member->getName();
			}
		}
	}
	
	$template = $twig->loadTemplate('login.tpl.htm');
	
	$templateData = array();
	$templateData['server'] = $_SERVER;
	$templateData['title'] = 'Login Test';
	$templateData['loginMessage'] = $loginMessage;
	print $template->render($templateData);
	