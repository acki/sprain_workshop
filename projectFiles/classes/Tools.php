<?php

class Tools{

	/**
	 * Prepares price for displaying
	 *
	 * @param string $amount
	 * @param bool $replace00CentsThroughDash
	 * @param bool $htmlEntities
	 * @param string $DecimalSeparator
	 * @param string $ThousandSeparator
	 * @return string
	 * @author Manuel Reinhard, 10.03.2010 > added $DecimalSeparator, $ThousandSeparator and $displayNoCentsAtAll - everything else was there before me :)
	 */
	public static function displayPrice($amount, $replace00CentsThroughDash=false, $htmlEntities=false, $DecimalSeparator=".", $ThousandSeparator="'", $displayNoCentsAtAll=false) {
		
			
		//splitten
		if(strstr(strval($amount), ".")){
			$amount = explode(".",strval($amount));
		}else{
			$amount = explode(",",strval($amount));
		}
		
		
		if(count($amount) == 1){
			$amount[1] = 0;
		}//if
		
		$amount[1] = str_pad($amount[1], 2, '0', STR_PAD_RIGHT);
		$amount = $amount[0].".".$amount[1];
		$amount = number_format($amount, 2, $DecimalSeparator, $ThousandSeparator);
				 
		if(!$displayNoCentsAtAll){
	
			// Replace trailing double-zeroes through dash?
			if ($replace00CentsThroughDash && substr($amount,-2) == '00') {
				$amount = substr($amount,0,-2);
				if ($htmlEntities) {
					$amount .= '&mdash;';
				} else {
					$amount .= '-';
				} // if htmlentities
			} // if replace trailing zeroes through dash
		
		}//if
		
		//chop off cents if necessary
		if($displayNoCentsAtAll){
			$amount = substr($amount, 0, -3);
		}//if
		
		return $amount;
	} // function


}