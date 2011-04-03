<?php
class Database extends mysqli{

	public function getResults($stmt){
	
		$meta = $stmt->result_metadata();
		while ($field = $meta->fetch_field()) {
		    $params[] = &$row[$field->name]; 
		}
		
		call_user_func_array(array($stmt, 'bind_result'), $params);
		
		$allRows = array();
		$i=0;
		while($stmt->fetch()){
			foreach($row as $spaltennamen => $wert){
				$allRows[$i][$spaltennamen] = $wert;
			}//foreach
			$i++;
		}//while
		return $allRows;
	
	}//function
	

}