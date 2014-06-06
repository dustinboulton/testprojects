<pre><?php 

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
	public $countofREplaced =0;
	
	
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
		$customers 	= array_unique(array_flip(explode(DELIMITER, $customers)));
		$products	= array_unique(array_flip(explode(DELIMITER,$products)));
		
		$scores = array();
		
		// Loop through customers - then products
		foreach ($customers as $customer =>$oldKey)
		{
			// loop through products 
			foreach ($products as $product => $oldKey)
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
				
				// If the length of the product == length of customer AND > 1
				if ($cStrLen == $pStrLen && $pStrLen > 1)
				{
					//increase current SS by 50%
					$ss = $ss * 1.50;
				}
				
				// Complile all scores for all customer and product combos
				$scores[$customer][$product]=$ss;
			}
			// sorts scores so we can match faster based on highest number
			arsort($scores[$customer]);
		}

	// Just for example link 
	$this->calculatedResults=$scores;
	
	// Now assing products to customer's based on SS score
	$this->assign($scores);
		
	}
	
	/**
	 *  Loops through all complied scores and find best match based on SS 
	 * 
	 *  One product per customers 
	 *  Looking for the higest possible score per match
	 *  
	 *  @Todo:
	 *     - develop or find a better algorithm (http://www.hungarianalgorithm.com/solve.php) perhaps
	 *     - Or refactor to have less loops and be more absctract  
	 * 	
	 */
	private function assign($scores,&$assignments=array(),$countofReplaced=0)
	{
		$replay	= array();
		foreach ($scores as $customer =>$products)
		{
			foreach ($products as $product =>$ss)
			{
				
				// Calculate the max SS for current customer
				$customerMax = max($scores[$customer]);
				
				if ($ss == $customerMax) 
				{
					// If the product does not already have an assignment
					if (!array_key_exists($product,$assignments))
					{
						$assignments[$product][$customer]=$ss;
					} 
					else {
					// Otherwise, let's find it.	
						
						//There will only be one - but quick way to get other customer and SS
						foreach($assignments[$product] as $compareCustomer => $compareSS)
						{
							//echo "$compareCustomer/$compareSS $product=$ss$customer<br/>";
							if ($compareSS < $ss && !array_key_exists($customer,$assignments[$product])) 
							{
								
								// Remove assigned product
								unset($assignments[$product]);
								
								// Assign new customer to this product
								$assignments[$product][$customer]=$ss;
								
								// Take the replaced customer and set of scores to rerun through assign method
								$replay[$compareCustomer] = $this->calculatedResults[$compareCustomer];
								//$replay[$compareCustomer] = $scores[$customer];
								
								// Remove this product from the replaced customer to avoid processing again
								unset($replay[$compareCustomer][$product]);
							} 
							else {
								
								// Not a good match so replay this customer minus current product and break loop
								$replay[$customer] = $scores[$customer];
								unset($replay[$customer][$product]);
							}
					  	}
					}
				// Minimize the loops by breaking loop when we find the Max for customer 
				break;
				} 
			}
		} 
		
		// If we had to shuffle anyone let's process those again now, passing $replay as $scores
		if (count($replay)) 
		{
			$this->assign($replay,$assignments,$countofReplaced);
		} 
		else {
			$this->matches =$assignments;
		}
		//echo $countofReplaced;
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
		print_r($currentMatch->ssMatches);
	} 
	else {
?>
	<pre><h2>SS Match Results</h1>
	<?php print_r($currentMatch->matches);
	echo count($currentMatch->matches);?>
	</pre>
<?php } ?>
	<pre>
	<h2>Products Input</h1>
<?php print_r($products);
	echo count($products);?>
	</pre>
	<pre><h2>Customer Input</h1>
<?php print_r($customers);?>
	</pre>
	<pre><h2>All Calc Data before results</h1>
<?php print_r($currentMatch->calculatedResults);?>
	</pre>
	





