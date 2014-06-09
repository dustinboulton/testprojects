<pre><?php 
error_reporting(E_ALL &~E_NOTICE);
/**
 * Attempt to solve SS Coding problem / Question #6
 * 
 * 
 * PHP version 5.3.5
 * 
 * Coding Problem:
 * -------------------------------
 * Our marketing department has just negotiated a deal with several of the top vendors in our network 
 * that will allow us to offer exclusive discounts on various products to our customers every day. 
 * The catch is that we may only offer each product to one customer and we may only make one offer to 
 * each customer.
 * 
 * Each day we will get the list of products that are eligible for these special discounts. 
 * We then have to decide which products to offer to which customers. Fortunately our team of highly 
 * trained statisticians has developed an amazing mathematical model for determining which customers 
 * are most likely to buy which products. With all of the hard work done for us, all we 
 * have to do now is implement a program that assigns each product to a given customer while maximizing 
 * the total suitability of all products to all customers. We are going to be busy writing up the patent 
 * application for this amazing new technology, so we need you to implement the matching program
 * 
 * The top-secret algorithm is: 
 * 
 * If the length of the product name is even, the base suitability score (SS) 
 * is the number of vowels in the customer’s name multiplied by 1.5.
 * 
 * If the length of the product name is odd, the base SS is the number 
 * of consonants in the customer’s name multiplied by 1. 
 * 
 * If the length of the product name shares any common factors (besides 1) with the 
 * length of the customer’s name, the SS is increased by 50% above the base SS.
 * 
 * Write a program in PHP that assigns products to customers in a way that maximizes the total SS 
 * over the set of customers. Each customer can only have one product and each product can 
 * only be offered to one customer. Your program should run on the command line and take as 
 * input two newline separated files, the first containing the names of the products and the 
 * second containing the names of the customers. The output should be the total SS and a matching 
 * between customers and products. You do not need to worry about malformed input, 
 * but you should certainly handle both upper and lower case names. Please provide an OOP solution.
 * 
 * 
 * @author  Dustin Boulton
 * @copyright 2014 
 * @todo Refactor list
 * 		-  Assign method 
 * 		-  Procedural block 
 */

 // Determine logic for OS 
$returnChar = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? "\r\n" : "\n"; 

// {{{ constants
define('DELIMITER', $returnChar);
 
 
class Suitability_Score {
	
	
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
		
		// Take input files, explide and flip them so we are working with the names vs keys
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
			
				// Complile all scores for all customer and product combos
				$scores[$ckey][$pkey]=$ss;
			}
		}

	// Just for example link 
	$this->calculatedResults=$scores;
	
	// Now assing products to customer's based on SS score
	$ss =$this->assign($scores);
	foreach ($ss as $ckey => $pkey) {
		$results[$customers[$ckey]][$products[key($pkey)]] = $ss[$ckey][key($pkey)];
	}
	$this->matches =$results;
	
	}

	private function assign($table) {
	$m = $this->pad_table($table);
	$max = $this->multimax($m);
	$numRows = count($m);
	$numCols = count($m[0]);

	// augment for maximize
	for ($i = 0; $i < $numRows; $i++) {
		for ($j = 0; $j < $numCols; $j++) {
			$m[$i][$j] = $max - $m[$i][$j];
		}
	}
	$bignum = 100000; // arbitrarily big number
	$u = array_pad(array(), $numRows, 0);
	$v = array_pad(array(), $numCols, 0);
	$ind = array_pad(array(), $numCols, -1);
	for ($i = 0; $i < $numRows; $i++) {
		$links = array_pad(array(), $numCols, -1);
		$mins = array_pad(array(), $numCols, $bignum);
		$visited = array_pad(array(), $numCols, 0);
		$markedI = $i;
		$markedJ = -1;
		$j = 0;
		$done = false;
		while (!$done) {
			$j = -1;
			for ($k = 0; $k < $numRows; $k++) {
				if ($visited[$k] == 0) {
					$cur = $m[$markedI][$k] - $u[$markedI] - $v[$k];
					if ($cur < $mins[$k]) {
						$mins[$k] = $cur;
						$links[$k] = $markedJ;
					}
					if ($j == -1 || $mins[$k] < $mins[$j]) {
						$j = $k;
					}
				}
			}
			$delta = $mins[$j];
			for ($k = 0; $k < $numCols; $k++) {
				if ($visited[$k] == 1) {
					$u[$ind[$k]] += $delta;
					$v[$k] -= $delta;
				} else {
					$mins[$k] -= $delta;
				}
			}
			$u[$i] += $delta;
			$visited[$j] = 1;
			$markedJ = $j;
			$markedI = $ind[$j];
			if ($markedI == -1) {
				$done = true;
			}
		}
		$done = false;
		while (!$done) {
			if ($links[$j] != -1) {
				$ind[$j] = $ind[$links[$j]];
				$j = $links[$j];
			} else {
				$done = true;
			}
		}
		$ind[$j] = $i;
	}
	$ss = 0;
	$sum=0;
	for ($j = 0; $j < $numCols; $j++) {
		// uncomment to see matrix coordinates
 		$mySS[$ind[$j]][$j]=$table[$ind[$j]][$j];
		$sum+=$table[$ind[$j]][$j];
		$ss += $table[$ind[$j]][$j];
	}
	echo "Total SS: " .$sum;
	//print_r($mySS);
	return $mySS;
}

// pad matrix if not square
function pad_table($table) {
	$m = $table;
	$numRows = count($m);
	$numCols = count($m[0]);
	if ($numRows > $numCols) { // if tall
		for ($i = 0; $i < $numRows; $i++) {
			$m[$i] = array_pad($m[$i], $numRows, 0);
		}
	} elseif ($numCols > $numRows) { // if wide
		for ($i = count($m); $i < $numCols; $i++) {
			$m[$i] = array_pad(array(), $numCols, 0);
		}
	}
	return $m;
}

// returns maximum value in multidimensional array
function multimax($table) {
	$numRows = count($table);
	$max = array();
	for ($i = 0; $i < $numRows; $i++) {
		$max[$i] = max($table[$i]);
	}
	$max = max($max); // max value in entire array
	return $max;
}
	
}

/* 
 * Procedural Block and some HTML 
 * 
 */

	
	if (php_sapi_name() === 'cli') 
	{
		$products		= file_get_contents($argv[1]);
		$customers		= file_get_contents($argv[2]);
	} else {
	
		$products		= file_get_contents('./products.txt');
		$customers		= file_get_contents('./customers.txt');
	}
	$currentMatch	= new Suitability_Score($products,$customers);
	
	if (php_sapi_name() === 'cli') 
	{
		print_r($currentMatch->matches);
	} 
	else {
?>
	<pre><h2>SS Match Results</h1>
	<?php $sum=0;
	foreach ($currentMatch->matches as $product => $customer ) {
		foreach ($customer as $key => $value ) {
		$sum +=$currentMatch->matches[$product][$key];
		}
	}
	echo $sum;
		print_r($currentMatch->matches);
	echo count($currentMatch->matches);
	array_sum($currentMatch->matches);?>
	</pre>
<?php } ?>
	<pre>
	<h2>Products Input</h1>
<?php //print_r($products);
	echo count($products);?>
	</pre>
	<pre><h2>Customer Input</h1>
<?php //print_r($customers);?>
	</pre>
	<pre><h2>All Calc Data before results</h1>
<?php //print_r($currentMatch->calculatedResults);?>
	</pre>
	





