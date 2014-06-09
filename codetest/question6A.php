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

// Include ProductAssign Class
include('./Classes/Productsassign.php');

/* 
 * Procedural Block and some HTML 
 * 
 */
	if (php_sapi_name() === 'cli') 
	{
		$products		= file_get_contents($argv[1]);
		$customers		= file_get_contents($argv[2]);
		
		$return		= new ProductsAssign($products,$customers);
		
		// IF CLI return just the total and Matches a s astring
		echo "Total SS: " . $return->totalSS . DELIMITER;
		echo $return->matchesString;
	
	} else {
	
		$products		= file_get_contents('./products.txt');
		$customers		= file_get_contents('./customers.txt');
		$return			= new ProductsAssign($products,$customers);
?>
	<pre><h2>SS Match Results</h1>
<?php 
	echo "<h3>TotalSS:" . $return->totalSS.DELIMITER."</h3>";
	echo $return->matchesString;
	print_r($return->matches);
	?>
	</pre>
<?php } ?>
	<pre>
	<h2>Products Input</h1>
<?php 
	echo print_r($products);?>
	</pre>
	<pre><h2>Customer Input</h1>
<?php print_r($customers);?>
	</pre>
	<pre><h2>All Calc Data before results</h1>
<?php print_r($return->calculatedResults);?>
	</pre>
	





