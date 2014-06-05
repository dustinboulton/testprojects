<?php 

/*
 * Quick demonstration of an abstract class implementing an interface
 * Then being inhertied by a concrete class
 * 
 */

interface iSellable
{
	public function isForSale($arg);
	public function sellPrice($arg);
}

abstract class Building implements iSellable
{
	public $forsale;
	public $propertyType;
	public $salePrice;
	
	abstract function address();
	
	public function __construct($address, $forSale = null, $salePrice = NULL) {
		$this->isForSale($forSale);
		$this->sellPrice($salePrice);
		$this->address	= $address;
	}
	
	public function isForSale($forSale){
		if ($forSale) {
			$this->forSale	= TRUE;
		}
	}
	public function sellPrice($salePrice){
		if ($salePrice) {
			// Do logic here to determine if it is a real amount and any other program logic 
			$this->salePrice = $salePrice;
		}
	}
	
	public function describeProperty(){
		$message	= "This " . $this->propertyType;
		$message	.= ($this->forSale) ? " is for sale" : " is not for sale.";
		$message	.= ($this->salePrice) ? " for " . $this->salePrice : '';
		$message	.= "\nLocated at:" . $this->address;
		return $message;
	}
}

class House extends Building 
{
	
	public $propertyType = 'House';
	
	public function describeHouse() {
		return $this->describeProperty();
	}
	
	// Format address properly
	public function address() {
		// do something cool here - even if it should go in building or some other interface
	}
	
}

// Procedulral Code to instaniate classes (to follow up on question from the other day)
$myHome = new House('123 Street, San Diego, Ca 92109', TRUE, '$123,000');
echo "<pre>".$myHome->describeHouse()."</pre>";
