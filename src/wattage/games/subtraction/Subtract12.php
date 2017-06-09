<?php

/* An implementation of subtraction {1,2} with a single pile. */

class Subtract12 extends Game
{
	private pilecount = 0;
	
	function __construct() {
		parent::__construct();
		
		// choice a random number of "beads" for the pile
		pilecount = rand(5, 20);
	}

}

?>