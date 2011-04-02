<?

	class Member{
	
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
			$resultat = $this->getResults($stmt);
			print $resultat[0]['name'];
		}
		
		protected function getResults($stmt) {
			$meta = $stmt->result_metadata();
			while ($field = $meta->fetch_field()) {
				$params[] = &$row[$field->name];
			}
			
			call_user_func_array(array($stmt, 'bind_result'), $params);
			
			$allRows = array();
			while ($stmt->fetch())
				$allRows[] = $row;
				
			return $allRows;
		}
		
	}
	
?>