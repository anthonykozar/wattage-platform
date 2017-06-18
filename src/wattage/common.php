<?php

// common functions and markup used by the WATTAGE games server

// use one static style sheet for all pages
function InsertStyles()
{
?>
  <style type="text/css">
    <!--
   	body {
   		color: #111;
		background-color: #fafafa;
		text-align: left;
		margin: 1cm;
		padding: 5pt;
	}

	h1, h2, h3, h4, h5, h6 {
		font-weight: 500;
 	}
    -->
  </style>
<?php
}

// apply a style to a span of text
function ApplyStyle($stylename, $text)
{
	return "<span class=\"$stylename\">$text</span>";
}

// apply a style to a paragraph
function ParaStyle($stylename, $text)
{
	return "<p class=\"$stylename\">$text</p>";
}

// print the common html header statements
function PageHeader($title)
{
?>
<!DOCTYPE HTML>
<html>
 <head>
  <title><?php echo $title; ?></title>
  <?php InsertStyles(); ?>
</head>
<body>
<?php
}
 
// print the common html statements at the end of the document
function PageFooter()
{
?>

</body>
</html>
<?php
}

$CLASS_PATHS = [
	'Game' => 'games/core/Game.php',
	'Team' => 'games/core/Team.php',
	'GameAction' => 'games/core/GameAction.php',
	'GameLocation' => 'games/core/GameLocation.php',
	'GameObject' => 'games/core/GameObject.php',
	'GamePhase' => 'games/core/GamePhase.php',
	'Player' => 'games/core/Player.php',
	'Subtract12' => 'games/subtraction/Subtract12.php',
];

// Called by PHP to load class files on the fly
function __autoload($classname)
{
	global $CLASS_PATHS;
	
	echo "<p>__autoload() called for class $classname</p>";
	if (array_key_exists($classname, $CLASS_PATHS)) {
		require_once($CLASS_PATHS[$classname]);
	}
}

?>
