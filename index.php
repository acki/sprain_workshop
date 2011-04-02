<?

	include("class.Member.php");

	$mysqli = new mysqli(
							'localhost',
							'sprain',
							'sprain',
							'sprain'
						);

	$member = new Member(1, $mysqli);
//	print $member->id;
	print $member->getName();
//	print $member->setName('hans');
//	print $member->getName();
	
?>