<?

	include("class.Member.php");

	$database = new Member(1);
	print $database->id;
	print $database->getName();
	print $database->setName('hans');
	print $database->getName();
	
?>