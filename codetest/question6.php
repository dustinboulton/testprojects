<?php 

/*
 * @author  Dustin Boulton
 * @copyright 2014 
 * $todo Alot :) 
 * 
 *  Attempt to solve SS Coding problem / Question #6
 * 
 */


class Suitability_Score {
	
	public $customers					= array();
	public $products					= array();
	public $ssMatches					= array();
	public $vowels						= array('a','e','i','o','u');
	public $calculatedResults			= array();
	
	
	/*
	 * 
	 */
	public function __construct($products,$customers)
	{
		$this->products				= $this->setProductValues($products);
		$this->customers			= $this->setCustomerValues($customers);
		$this->calculate_ss();
		
	}
	
	/*
	 * @params String
	 * @return Array Returns the number of elements complied from string.
	 * 
	 */
	private function setCustomerValues($input)
	{
		$customers = explode("\r\n",$input);
		$returnArray = array();
		
		foreach($customers as $key => $value) {
			$strLen	= strlen($value);
			$vowels = $strLen - ($consts = strlen( str_ireplace($this->vowels,'',$value)));
			
			$returnArray[$value]['even'] 	= $vowels;
			$returnArray[$value]['odd']		= $consts;
			$returnArray[$value]['length'] 	= $strLen;
		}
		return $returnArray;
	}
	
	private function setProductValues($input){
		$products = explode("\r\n",$input);
		$returnArray = array();
		foreach( $products as $key => $product) {
			$returnArray[$product]['length']	= strlen($product);
			$mod =$returnArray[$product]['length']%2;
			$returnArray[$product]['multiplier'] 	= $mod == 0 ? 1.5:1;
			$returnArray[$product]['type'] 			= $mod == 0 ? 'even':'odd';
		}
		return $returnArray;
	}
	
	
	private function calculate_ss()
	{
		$scores = array();
		foreach ($this->customers as $customer => $cdata){
			foreach ($this->products as $product => $data) {
			
			$ss = $cdata[$data['type']] * $data['multiplier'];
			
				if ($cdata['length'] == $data['length'] && $data['length'] > 1) {
					$ss = $ss * 1.50;
				}
				$scores[$customer][$product]=$ss;
				arsort($scores[$customer]);
			}
			
		}
	// Just for example link 
	$this->calculatedResults=$scores;
	
	// Now assing products to customer's based on SS score
	$this->make_assignments($scores);
		
	}
	
	private function make_assignments($scores,$assignments=array()) {
		$reply=array();
		foreach ($scores as $customer =>$products){
			$nextCustomer =false;
			foreach ($products as $product =>$ss){
				
				$customerMax = max($scores[$customer]);
				
				if ($ss == $customerMax) 
				{
					
					if (!array_key_exists($product,$assignments))
					{
						$assignments[$product][$customer]=$ss;
					} 
					else {
						
						foreach($assignments[$product] as $checkCustomer => $checkSS)
						{
							if ($checkSS < $ss && !array_key_exists($customer,$assignments)) 
							{
								unset($assignments[$product]);
								$reply[$checkCustomer] = $scores[$checkCustomer];
								unset($reply[$checkCustomer][$product]);
								$assignments[$product][$customer]=$ss;
							} 
							else {
								$reply[$customer] = $scores[$customer];
								unset($reply[$customer][$product]);
							}
					  	}
					}	
				break;
				} 
			}
		} 
	
		if (count($reply)) 
		{
			$this->make_assignments($reply,$assignments);
		} 
		else {
			$this->ssMatches =$assignments;
		}
	}
}


/* Producral Block and some HTML 
 * 
 */

	//include('./question6.php');
	
	if (php_sapi_name() === 'cli') {
		$products		= file_get_contents($argv[1]);
		$customers		= file_get_contents($argv[2]);
	} else {
	
		$products		= file_get_contents('./products.txt');
		$customers		= file_get_contents('./customers.txt');
	}
	$currentMatch	= new Suitability_Score($products,$customers);
	
	if (php_sapi_name() === 'cli') {
		print_r($currentMatch->ssMatches);
	} else {
?>
	<pre>
	<h2>Products Input</h1>
<?php print_r($products);?>
	</pre>
	<pre><h2>Customer Input</h1>
<?php print_r($customers);?>
	</pre>
	
	<pre><h2>All Product Data before Calc</h1>
<?php print_r($currentMatch->products);?>
	</pre>
	
	<pre><h2>All Customer Data before results</h1>
<?php print_r($currentMatch->customers);?>
	</pre>
	
	<pre><h2>All Calc Data before results</h1>
<?php print_r($currentMatch->calculatedResults);?>
	</pre>
	
	<pre><h2>SS Match Results</h1>
<?php print_r($currentMatch->ssMatches);?>
	</pre>
<?php } ?>




