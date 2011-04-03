<?php

class GoldMember extends Member{

	public function callButler(){
		print "Butler steht da und bring Zonk ".$this->getZonk();
	}
	
}