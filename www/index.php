<?php
include("../projectFiles/init.php");
?>

<html>
<head>
	<title><?php print "Der Titel"; ?></title>
</head>
<body>
	<?php
		$Member = new Member($_GET["id"], $mysqli);
		print $Member->getName();
	?>
</body>
</html>