<?php 
include('./Classes/Assign.php');

/**
 * 
 * 
 * http://www.hungarianalgorithm.com/examplehungarianalgorithm.php
 * https://github.com/amirbawab/Hungarian-Algorithm/blob/master/Hungarian.java
 * 
 * 
 */

class ProductsAssign extends Assign {
	
	
	public $customers	= array();
	public $products	= array();
	public $matches		= array();
	public $scores		= array();
	static $vowels		= array('a','e','i','o','u');
	
	
	/**
     * Runs the Class on instantiation
     * @todo: 
	 * 		- error catching
	 *      -
     */
	public function __construct($products,$customers)
	{
		$this->run($customers, $products);
	}
	
	/**
     *  Run through all customers and products to calculate scores
	 * 
     */
	private function run($customers, $products)
	{
		
		// Take input files, explode 
		$customers 	= explode(DELIMITER, $customers);
		$products	= explode(DELIMITER,$products);
		
		$scores = array();
		
		// Loop through customers - then products
		foreach ($customers as $ckey =>$customer)
		{
			// loop through products 
			foreach ($products as $pkey => $product)
			{
				
				$cStrLen	= strlen($customer); // Customer len
				$pStrLen	= strlen($product); // Product len 
				
				// Take Product name length and determine if odd or even
				if($pStrLen % 2 == 0) 
				{
					// EVEN:  we increase by 50% or 1.5
					str_ireplace(self::$vowels,'',$customer,$vowels);
					$ss	= $vowels * 1.5;
				}  
				else {
					// ODD: SS Score is now equal to the number of constonants (1 *)
					$ss = strlen( str_ireplace(self::$vowels,'',$customer));
				} 
				
				/*
				 * If the length of the product name shares any common factors (besides 1) with the  * length of the customer’s name, 
				 * the SS is increased by 50% above the base SS.
				 */
				while($pStrLen > 0) {
					$factor	= $pStrLen;
					$pStrLen = $cStrLen % $pStrLen;
					$cStrLen = $factor;
				}
				
				if ($factor != 1) {
					$ss = $ss * 1.50;
				}
			
				// Complile all scores for all customer and product combos - Adding keys only
				$grid[$ckey][$pkey]=$ss;
			}
		}

	// Just for example to show all possibly scores 
	$this->calculatedResults=$grid;
	
	// Assing products to customer's using Assign Class's assign method
	list($assignments,$totalSS)	= $this->assign($grid);
	
	// TODO: Find better way to merge these arrays, possibly passing in all values to assign method
	foreach ($assignments as $ckey => $pkey) {
		$sum +=$grid[$ckey][$pkey];
		$results[$customers[$ckey]][$products[$pkey]] = $grid[$ckey][$pkey];
		$resultsString .= $grid[$ckey][$pkey]. ",".$customers[$ckey] .",". $products[$pkey]. DELIMITER;
	}
	
	// Set property to equal results
	$this->totalSS			= $sum;
	$this->matches 			= $results;
	$this->matchesString	= $resultsString;
	}	
}

