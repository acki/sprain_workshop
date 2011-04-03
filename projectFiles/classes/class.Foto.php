<?php

class Foto {

	protected $id;
	protected $mysqli;
	
	public function __construct($id, $mysqli){
		$this->id     = $id;
		$this->mysqli = $mysqli;
	}
	
	public function 	getFilename(){
		$query = 
		'select datei from fotos where id = ?';
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('i', $this->id);
		$stmt->execute();
		$resultat = $this->mysqli->getResults($stmt);
		return $resultat[0]["datei"];
	
	}//function	

}