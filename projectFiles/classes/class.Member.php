<?php

class Member{

	protected $id;
	protected $mysqli;

	public function __construct($id, $mysqli){
		$this->id     = $id;
		$this->mysqli = $mysqli;
	}

	public function 	getName(){
		$query = 
		'select name from member where id = ?';
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('i', $this->id);
		$stmt->execute();
		$resultat = $this->mysqli->getResults($stmt);
		return $resultat[0]["name"];
	
	}//function	
	
	public function getImages(){
		$query = 
		'select id from fotos where member_id = ?';
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('i', $this->id);
		$stmt->execute();
		$resultat = $this->mysqli->getResults($stmt);
		return $resultat;
	}
	

}//class