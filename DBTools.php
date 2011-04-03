<?php

/* ------------------------------------------------------------------------ */
/*	PHP class with some DB tools to execute standard mysqli queries
/* ------------------------------------------------------------------------ */
/* Manuel Reinhard, manu@sprain.ch
/* Twitter: @sprain
/* Web: www.sprain.ch
/* ------------------------------------------------------------------------ */
/* History:
/* 2010/06/10 - Manuel Reinhard - when it all started
/* ------------------------------------------------------------------------ */  




class DBTools{

	//Set variables
	public $mysqli;
	public $query;
	public $stmt;
	public $params = array();
	public $referencedParams = array("1" => "placeholder"); //dummy placeholder > search this file for «dummy placeholder» to find out why
	public $typeString;

	/**
	 * Constructor method for this class
	 * @param object $mysqli
	 */
	public function __construct($mysqli){
		$this->mysqli = $mysqli;
		$this->reset();
	}//function



	/**
	 * Reset variables
	 */
	public function reset(){
		$this->referencedParams = array();
		$this->params = array("1" => "placeholder");  //dummy placeholder > search this file for «dummy placeholder» to find out why
		$this->typeString = "";
		$this->stmt = "";
	}//function


	/**
	 * Select db entries
	 * @param string $table
	 * @param array  $where  (Format: array("dbField" => array("type", "value"), "dbField2" => array("type", "value"))
	 * @param string $extras (stuff to add to the query like order by, group by, limit, etc.)
	 * @param string $uniqueId
	 */	
	function select($table, $where="", $extras="", $operator="and", $uniqueId="id", $cacheIsOk=true){
				
				
		//Im Cache?
		$cacheName = str_replace(" ", "-", $table."**".serialize($where)."**".$extras."**".$operator."**".$uniqueId);
		if($cacheIsOk){
			if(isset($GLOBALS["cache"][$cacheName])){
				return $GLOBALS["cache"][$cacheName];
			}//if
		}//if		
				
				
		//Reset
		$this->reset();
		#print print_r($where, true)."<br>";
		
		//Where string
		$oldwhere = $where;
		$where = $this->createWhereString($where, $operator);

		//Query
		$this->query = "select * from ".$table." where ".$where." ".$extras;
		#print $this->query."<br />";

		//Prepare
		$this->prepare();

		//Bind params
		$this->bindparams();

		//Execute
		$this->execute();
		
		//Fetch
		$result = $this->fetch($uniqueId, $table);

		//Cachen
		$GLOBALS["cache"][$cacheName] = $result;

		//Return
		return $result;
		
	}//function


	
	
	/**
	 * insert stuff into db
	 * @param string $table
	 * @param array $data       (Format: array("dbField" => array("type", "value"), "dbField2" => array("type", "value"))
	 */	
	 public function insert($table, $data){
	 	
	 	//Reset
	 	$this->reset();
	 	
	 	//Clean data
	 	$data = $this->cleanData($table, $data);
		
		//Data string
		$fields = $this->createDataString($data);
				
		//Values
		$i = 0;
		$values = "";
		while($i < count($data)){
			$values .= "?,";
			$i++;
		}//while
		$values = substr($values, 0, -1);
			
		//Query
		$this->query = "insert into ".$table." (".$fields.") values (".$values.")";
			
		//Prepare
		$this->prepare();	
		
		//Bind params
		$this->bindparams();
		
		//Execute
		if($this->execute()){		
			return $this->mysqli->insert_id;
		}else{
			return false;
		}//if
		
	}//function




	/**
	 * Prepare and execute an update
	 * @param string $table
	 * @param array $where		(Format: array("dbField" => array("type", "value", ["operator"]), "dbField2" => array("type", "value"))
	 * @param array $data       (Format: array("dbField" => array("type", "value"), "dbField2" => array("type", "value"))
	 */	
	 public function update($table, $data, $where, $operator="and"){
	 	
	 	//Reset
	 	$this->reset();

		//Clean data
		$data = $this->cleanData($table, $data);

		//Data string
		$data = $this->createDataString($data, "update");
		#print_r($this->params)."<br>";
		
		//Where string
		$where = $this->createWhereString($where, $operator);
		#print_r($this->params)."<br><br>";
				
		//Query
		$this->query = "update ".$table." set ".$data." where ".$where;
		#print $this->query."<br><br>";
		
		#$content = date("Ymd-His")." - ".$this->query."\n";
		#file_put_contents($GLOBALS["pathToTempFiles"]."queries.txt", $content, FILE_APPEND);
		
		//Prepare
		$this->prepare();		
		
		//Bind params
		$this->bindparams();
		
		//Execute
		return $this->execute();
						
	}//function
	
	
	
	
		/**
		 * Deletes db entries
		 * @param string $table
		 * @param array  $where  (Format: array("dbField" => array("type", "value"), "dbField2" => array("type", "value"))
		 * @param string $extras (stuff to add to the query like order by, group by, limit, etc.)
		 */	
	public function delete($table, $where="", $extras="", $operator="and"){
					
			//Reset
			$this->reset();
			#print print_r($where, true)."<br>";
			
			//Where string
			$where = $this->createWhereString($where, $operator);
	
			//Query
			$this->query = "delete from ".$table." where ".$where." ".$extras;
			#print $this->query."<br><br>";
			
			//Prepare
			$this->prepare();
	
			//Bind params
			$this->bindparams();
	
			//Execute
			return $this->execute();
	
		}//function
	


	/**
	 * Creates where string
	 * @param array  $where    (Format: array("dbField" => array("type", "value", ["operator"]), "dbField2" => array("type", "value"))
	 * @param string $operator
	 */	

	public function createWhereString($where, $operator){
	
		$whereKeys = "";
	
		//put string together
		foreach($where as $key => $whereValues){
			$this->typeString = $this->typeString.$whereValues[0];
			$this->params[]   = $whereValues[1];
			
			$op = "=";
			if(isset($whereValues[2])){
				$op = $whereValues[2];
			}//if
			
			$whereKeys .= $key." ".$op." ? ".$operator." ";
		}
				
		//We put the string with types to the first element of the array
		//Now this is why we use the dummy placeholder, so we can put this in spot zero and we won't overwrite anything!
		$this->params[0] = $this->typeString;
		
		//remove trailing stuff from  strings
		$num = (2 + strlen($operator)) * -1;
		$whereKeys = substr($whereKeys, 0, $num);
		
		//now make array with references which we can send to bind_param
		foreach($this->params as $key => $value) $this->referencedParams[$key] = &$this->params[$key];	
		
		//return
		return $whereKeys;
		
	}//function
	
	
	/**
	 * Create data string
	 * @param array  $data    (Format: array("dbField" => array("type", "value"), "dbField2" => array("type", "value"))
	 */	
	
	public function createDataString($data, $type="insert"){
	
		$dataString = "";
			
		//go thru data and prepare variables
		foreach($data as $dbField => $value){
			if($type == "insert"){
				$dataString .= $dbField .', ';
			}elseif($type == "update"){
				$dataString .= $dbField .' = ?, ';
			}//if
			
			$this->typeString .= $value[0];
			$this->params[] = $value[1];
		}//foreach

		
		if($type == "insert" || $type == "query"){
			//We put the string with types to the first element of the array
			//Now this is why we use the dummy placeholder, so we can put this in spot zero and we won't overwrite anything!
			$this->params[0] = $this->typeString;
		}//if
						
		//now make array with references which we can send to bind_param
		foreach($this->params as $key => $value) $this->referencedParams[$key] = &$this->params[$key];	
	
		return substr($dataString, 0, -2);
	
	}//function
	
	
	/**
	 * Prepares a query
	 * @param string $query
	 */	
	public function prepare(){
		if($this->stmt = $this->mysqli->prepare($this->query)){
			return true;
		}//if
		return false;
	}//function


	/**
	 * Binds params
	 * @param array @params (Format: array("dbField" => "value", "dbField2" => "value"))
	 */	
	public function bindparams($params = array()){
	
			if(count($params) == 0){
				unset($this->referencedParams[1]); //remove dummy placeholder
				$params = $this->referencedParams;
				ksort($params);
			}//if
		
			if(count($params) > 1){				
				call_user_func_array(array($this->stmt, "bind_param"), $params);
			}//if
	}//function



	/**
	 * Executes query
	 * @param string $query
	 */	
	public function execute(){		
		if($this->stmt->execute()){
			return true;
		}//if
		return false;
	}//function


	/**
	 * Fetch data
	 * based on http://www.php.net/manual/en/mysqli-stmt.bind-result.php#85470
	 * @param string $uniqueId
	 */	
	public function fetch($uniqueId="", $table=""){

		//Get fields
		$meta = $this->stmt->result_metadata();
		while ($field = $meta->fetch_field()) { 
		    $params[] = &$row[$field->name]; 
		} //while
		
		//Store
		$this->stmt->store_result();		
		
		//Bind results
		call_user_func_array(array($this->stmt, 'bind_result'), $params); 
		
		//prepare return
		$found = array();
		$i=0;
		while ($this->stmt->fetch()) {
			$entry = array();
			$k=$i;
		    foreach($row as $key => $val) { 
		        $entry[$key] = $val; 
		        if($key == $uniqueId){$k = $val;}
		    }//foreach 
		    $found[$k] = $entry;
		    
		    //Do some stuff with the values
		    foreach($entry as $key => $value){
		    
		    	//Prepare Inline-Edit-Keys
		    	$found[$k]["editInfo"][$key] = Tools::scrambleText($table."|".$k."|".$key);
		    	
		    	//Make Timestamps readable
		    	if(strpos($key, "timestamp") !== false){
		    		$found[$k][$key."_readable"] = Tools::getDateAndTimeAsString($value);
		    	}//if
		    	
		    }//foreach
		    
		    $i++;
		} //while
		
		//close statement
		$this->stmt->close();
		
		#print_r($found);
		
		//return
		return $found;

	}//function
	
	
	
		
	/**
	* Prepare and execute any query
	* @param string $table
	* @param array $data       (Format: array("dbField" => array("type", "value"), "dbField2" => array("type", "value"))
	*/	
	public function query($query, $data, $uniqueId=""){
		 	
		 	//Reset
		 	$this->reset();
									
			//Query
			$this->query = $query;
			
			//create string (we just use it here to prepare params)
			$this->createDataString($data, "query");
			
			//Prepare
			$this->prepare();	
			
			//Bind params
			if(count($data) > 0){
				$this->bindparams();
			}//if
			
			//Execute
			$this->execute();
 			
 			return $this->fetch($uniqueId);
							
	}//function
	
	
	
	/**
	 * Adds type in case $data doesn't contain it yet
	 * Example: array("dbField" => "value", "dbField2" => "value") becomes
	 *			array("dbField" => array("type", "value"), "dbField2" => array("type", "value"))
	 * @param string $table
	 * @param array $data
	 * @return array
	 */	
	protected function cleanData($table, $data){
	
		//Go thru fields
		foreach($data as $field => $value){
			if(!is_array($value)){
				$data[$field] = array($this->getFieldType($table, $field), $value);
			}//if
		}//foreach

		return $data;
	
	}//function
	
	
	
	
	public function getFieldType($table, $field){
		
		//Get field descriptions
		$fields = $this->query("show columns from ".$table, array(), "Field");
		
		if(strpos($fields[$field]["Type"], "int")){
			return "i";
		}else{
			return "s";
		}
		
		
	}//function
	
	
	

}//class
