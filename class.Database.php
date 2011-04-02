<?

	class Database {
	
/*		function __construct() {
			$this->database = new mysqli('localhost','sprain','sprain','sprain');
		}
*/		
		public static function getResults($stmt) {
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

		
	} //class
	
?>