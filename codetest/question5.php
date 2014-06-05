<?php 
error_reporting(E_ALL & ~E_NOTICE);
function find_addends($n, $m, $returnAll = true)
{
	
	// First copy the $set of numbers
	// To preserve the origonal keys 
	// If we just wanted values this would be much easier. Or if they keys were assoc
	$list=$n;
	
	// Next Loop through the list and unset any values greater than $m
	foreach ($list as $key => $value)
	{
		if ($value > $m){ unset($list[$key]);}
	}
	
	// Reorder list so we can loop through it without warnings or errors
	//sort($list);
	$answers = array();
	
	// raise the number of the list to the power of 2 and loop through 
	for( $i = 0; $i < pow(2, count($list)); $i++ ) 
	{
	   
	    $sum 		= 0; //set sum
	    $addends 	= array(); 
		$keys		= array();
		
	    for($j = count($list)-1; $j >= 0; $j--) 
	    {
	        if(pow(2, $j) & $i) 
	        {
	        	
	        	// Get sum of this combo
	            $sum += $list[$j]; 
	           
	            // Addends
	            $addends[] = $list[$j]; 
				
				// origonal key
	            $keys[] = $j;
				
	        }
			
	    }
		
		// Either grab all possible answers or just the first one
		if ($sum == $m) 
		{
	    	 $answers[] = array('sum'=>$sum, 'addends'=>$addends,'keys'=>$keys);
			 if (!$returnAll){ break;}
		}
		
	}

return $answers;
}

for ($i=0;$i < rand(10,20);$i++) {
	$n[] = rand(1,20);
}
$m = rand(10,99);
$return = find_addends($n,$m,false);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
		<title>Dustin code test question #5</title>
		<meta name="robots" content="noindex" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Add bootstrap - Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
   		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
   		<!--[if lt IE 9]>
      		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    	<![endif]-->
	
	</head>
	<body>
		<div class="container">
			<pre>
				<h4>Question</h4>
Write a function that takes a list of N integers and returns a list of indices that add up to M. Example input ([1,4,17,-9], M=5) output [[0,1]].

<?php 
				echo "Numbers passed as \$n: " . implode(',', $n);
				echo "<br/>";
				echo "Keys of numbers passed: " . implode(',', array_keys($n));
				echo "<br/>";
				echo "Number passed as \$m: " . $m;
	
	?>
			</pre>
			
		<pre>
			<h4>Answer(s)</h4>
<?php 

		foreach ($return as $key =>$value) {
			echo "<p>";
			echo "Addend(s):" . implode(',', $return[$key]['addends']);
			echo "<br/>";
			echo "Sum:" . $return[$key]['sum'];
			echo "<br/>";
			echo "Keys: " . implode(',', $return[$key]['keys']);
			echo "<br/><br/>";
			echo "This should be returned to satisfy question:";
			echo "<br/>";
			print_r( $return[$key]['keys']);
			echo "</p>";
		}
?>
		</pre>
		
	
		
		</div>
	</body>
</html>
	

