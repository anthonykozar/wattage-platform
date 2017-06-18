<?php

/* An implementation of subtraction {1,2} with a single pile. */

class Subtract12 extends Game
{
	private $pilecount = 0;
	
	function __construct() {
		parent::__construct();
		
		// choose a random number of "beads" for the pile
		$this->pilecount = rand(5, 20);
		echo "<p>New Subtract12 game has $this->pilecount beads.</p>";
	}

}

?>