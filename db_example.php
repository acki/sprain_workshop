<?php

$where = array(
	"owner_type" => array("i", $ownerType),
	"owner_id"   => array("i", $ownerId)
);

//DB-Abfrage
$data = $DBTools->select("tabellenname" $where, "order by contact_type asc, id asc");

foreach($data as $row){
	print_r($row);		
}//foreach
