<?php 

/** 
 * 
 * http://www.hungarianalgorithm.com/examplehungarianalgorithm.php
 * https://github.com/amirbawab/Hungarian-Algorithm/blob/master/Hungarian.java
 * 
 */
 
class Assign {
	
	private $grid;
	private $clonedGrid;
	private $colMin;
	private $lines;
	private $numLines;
	private $rows;
		
	public function assign($grid) 
	{
		
		// Copy the origonal - just in case, not sure it's needed.
		$this->grid	= $grid;
		//$this->display_grid($grid);
		
		// Initalize new array for processing
		$this->clonedGrid	= array();
		
		// Set array to track the Column Min
		$this->colMin = array();
		
		// Get the higest SS score possible
		$maxValue	= max(array_map("max", $grid));
		// Negate all numbers then add back Max "Because the objective is to maximize the total cost we negate all elements"
		$this->preprocess($maxValue);
		
		// Make the Grid Square so we have the same number of rows and columns
		$this->pad_grid($this->clonedGrid);
		
		//$this->display_grid($this->clonedGrid);
		// Subtract the min numbers from Rows
		$this->subtract_min_rows();
		
		// Subtract the min numbers from Cols
		$this->subtract_min_cols();
		
		// Add lines to cover all zeros
		$this->cover_zeros();
		//$this->display_grid($this->clonedGrid);
		
		// TODO: Figure out why it's count + 1 in this loop
		// Until we are at more lines (+ one for some reason keep adding lines and covering zeros with lines
		while ( $this->numLines < count($this->clonedGrid) + 1) {
			$this->create_additional_zeros();
			$this->cover_zeros();
			//$this->display_grid($this->clonedGrid);
			//$this->display_grid($this->lines) ;
			
		}
		
		// Return Vars
		$reservedColumns =array();
		$this->rows =array();
		
		$this->build_output(0, $reservedColumns); 
		
		return array($this->rows, count($this->rows));
		
	
		
	}

	private function preprocess($maxValue)
	{ 
	// Negate all numbers then make positive by adding the max
		for ($i = 0; $i < count($this->grid); $i++) {
			for ($j = 0; $j < count($this->grid[0]); $j++) {
				
				// Negate all numbers 
				$num = -1 * abs($this->grid[$i][$j]);
				
				// Then add the Max number
				$this->clonedGrid[$i][$j] =  $maxValue + $num;
			}
		}
	}


	private function subtract_min_rows() 
	{
		foreach ($this->clonedGrid as $row =>$columns) 
		{
			$minValue =  min($columns);	
			foreach ($columns as $key =>$value) 
			{
				
				if ( $value == $minValue && $value > 0 ) {
					//$newArray[$key] = $minValue;
					$this->clonedGrid[$row][$key] = 0;
				} else {
				  
					if( !isset($this->colMin[$key])) {
						$this->colMin[$key] = $value ;
					} elseif( $this->colMin[$key] > $value ){
						$this->colMin[$key] = $value  ;
					} 
				}
			}
		}
	}
	
	private function subtract_min_cols() 
	{
		foreach ($this->clonedGrid as $row =>$columns) {
			foreach ($columns as $key =>$value) {
				if (  $value > 0 ) {
					$this->clonedGrid[$row][$key] -=$this->colMin[$key];
				}
			}
		}
	}
	
	
	/**
	 *  Increases rows or columns to make Grid Square.
	 * 
	 *  Pass by reference - to keep arraqy and vars updated
	 *  @params
	 *  @returns
	 */
	private function pad_grid() 
	{
		// If more rows than cols we add columns to all rows
		// TODO: Has to be a better way to do this
		$numRows = count($this->clonedGrid);
		$numCols = count($this->clonedGrid[0]);
		
		if ($numRows > $numCols) 
		{ 
			for ($i = 0; $i < $numRows; $i++) 
			{
				$this->clonedGrid[$i] = array_pad($this->clonedGrid[$i], $numRows, 0);
				
			}
		}  
		elseif ($numCols > $numRows) {  
			// If not check if COLS are > then rows, if so then add new rows.
			for ($i = count($this->clonedGrid); $i < $numCols; $i++) 
			{
				$this->clonedGrid[$i] = array_pad(array(), $numCols, 0);
			}
		}
	}
	
	/**
	 *  Increases rows or columns to make Grid Square.
	 * 
	 *  Pass by reference - to keep arraqy and vars updated
	 *  @params
	 *  @returns
	 */
	
	
	private function cover_zeros(){
		$this->numLines = 0;
		$this->lines = array();
		
		
		
		foreach ($this->clonedGrid as $row => $cols)
		{
			foreach ( $cols as $col => $value){
				$this->lines[$row][$col] = 0;
			}
		}
		
		foreach ($this->clonedGrid as $row => $cols)
		{
			foreach ( $cols as $col => $value){
				
				if($value == 0) {
					
					$this->strike_nieghbors($row, $col, $this->max_vh($row, $col));
				}
			}
		}
	}
	
	/**
	 *  Finds neighbors zeros Horizontally or veritcal from current position
	 * 
	 *  @params int $row in $col
	 *  @returns int  $results
	 */
	private function max_vh($row, $col){
		$result = 0;
		for($i=0; $i < count($this->clonedGrid); $i++){
			if($this->clonedGrid[$i][$col] == 0)
				$result++;
			if($this->clonedGrid[$row][$i] == 0)
				$result--;
		}
		return $result;
	}
	
	/**
	 *  Strikes -/ draws a line through unique rows and columns with zeros 
	 *
	 */
	
	private function strike_nieghbors($row, $col, $maxVH)
	{
		if($this->lines[$row][$col] == 2)
		{
			// We've marked this one 2x before 
			return;
		}
		if($maxVH >= 0 && ($this->lines[$row][$col] == 1))
		{
			// If max is vertical and we've been here before	 
			return;
		}
		if($maxVH <= 0 && $this->lines[$row][$col] == -1)
		{ 
			// MAx is horizontal and we've been here before 
			return;
		}
		for($i=0; $i < count($this->clonedGrid);$i++)
		{ 
			if($maxVH > 0)
			{	// if value of max is vertical then strike
				$this->lines[$i][$col] = ($this->lines[$i][$col] == -1 || $this->lines[$i][$col] == 2 )? 2 : 1; 
			}
			else{
				$this->lines[$row][$i] = ($this->lines[$row][$i] == 1 || $this->lines[$row][$i] == 2) ? 2 : -1; 
			}
		}
		// increment line number
		$this->numLines++;
	}
	
	
	private function create_additional_zeros() {
			
		$minUncovered = 0; 
		
		// Find the min in the uncovered numbers
		foreach ($this->clonedGrid as $row =>$columns) 
		{
			foreach ($columns as $col =>$value) 
			{
					
				if(($this->lines[$row][$col] == 0 ) && ($this->clonedGrid[$row][$col] <= $minUncovered ||  $minUncovered ==0)) 
				{
					//echo "<br/>$row - $col -  $value -  $minUncoveredValue  <br/>";
					//echo "next row value". $this->lines[$row][$col +1];
					$minUncovered = $this->clonedGrid[$row][$col];
				}
			}
		}
		
		
		// Subtract min form all uncovered elements, and add it to all elements covered twice
		for($row=0; $row < count($this->clonedGrid);$row++){
			for($col=0; $col < count($this->clonedGrid); $col++){
				if($this->lines[$row][$col] == 0 ) 
				{
					// if uncovered subtract
					$this->clonedGrid[$row][$col] -= $minUncovered;
				}
				else if($this->lines[$row][$col] == 2) 
				{
				// If stiked  twice, add
				$this->clonedGrid[$row][$col] += $minUncovered;
				}
			}
		}
	} 

	// DEBUGING or display progress of grid 
	private function display_grid($grid) 
	{
		echo "<table border=1>";
		foreach ($grid as $row =>$columns) {
				echo "<tr><td></td>";
			foreach ($columns as $key =>$value) {
				
				echo "<td>$value</td>";
				
			}
			echo "</tr>";
		}
		echo "</table>";
		
		
	}
	

	private function build_output($row,$reservedColumns) 
	{
		if($row == count($this->clonedGrid))
		{
			return true;
		}
	
		for($col=0; $col < count($this->clonedGrid); $col++)
		{
		  	 
		  	if($this->clonedGrid[$row][$col] == 0 && $reservedColumns[$col] == 0 )
			{ 
				$this->rows[$row] = $col; 
				$reservedColumns[$col] = 1; 
				
				if ($this->build_output($row+1,$reservedColumns))
				{
					return true;
				}
				else {
					$reservedColumns[$col] = 0; 
				}
			}
		}
		return false;
	}
	
	
	
}