<?

	class Database {
	
		function __construct() {
			$this->database = new mysqli('localhost','sprain','sprain','sprain');
		}
		
		function query($query) {
			$this->stmt = $this->database->prepare($query);
			$this->stmt->execute();
			$this->meta = $this->stmt->result_metadata();
			while ($field = $this->meta->fetch_field())
				$params[] = &$row[$field->name];
			call_user_func_array($array($this->stmt, "bind_result"), $params);
		}
		
	} //class
	
?>