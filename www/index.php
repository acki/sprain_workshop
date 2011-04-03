<?php
	include("../projectFiles/init.php");

	$template = $twig->loadTemplate('member.tpl.htm');
	echo $template->render(array(member=>'hallo'));

?>
