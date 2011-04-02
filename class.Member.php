<?

	class Member {
	
		public 		$id;
		protected 	$mysqli;
		protected 	$name;
	
		public function __construct($id, $mysqli) {
			$this->id 		= $id;
			$this->mysqli 	= $mysqli;
		}
		
		public function setName($name) {
			//$this->name = $name;
		}
		
		public function getName() {
			$query = 'select * from member where id = ?';
			$stmt = $this->mysqli->prepare($query);
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			$resultat = $this->mysqli->getResults($stmt);
			print $resultat[0]['name'];
		}
		
		
	}
	
?>