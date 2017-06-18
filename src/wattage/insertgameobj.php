<?php

require_once 'config.php';
require_once 'version.php';
require_once 'common.php';

PageHeader('WATTAGE game server');

echo "<p><b>Inserting new game object...</b></p>\n";

$dbconn = NULL;

function InitDBConnection() {
	global $dbconn;
	
	// connect to the database server and check for errors
	$dbconn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	
	if (mysqli_connect_errno()) {
		printf("Database connection failed: %s\n", mysqli_connect_errno());
		exit();
	}
}

function CloseDBConnection() {
	global $dbconn;
	mysqli_close($dbconn);
}

function main() {
	global $dbconn;
	
	$game = new Subtract12();
	$data = mysqli_real_escape_string($dbconn, serialize($game));
	echo $data;
	// insert the new game into the gamedata table
	$sql = 'INSERT into ' . DB_PREFIX . 'gamedata' . " (game_id, obj_data) values (0, '$data')";
	$result = mysqli_query($dbconn, $sql);
	if (!$result) {
		printf("<p>Could not insert game data: %s</p>", mysqli_error($dbconn));
		echo "<p>Query was:<br>$sql</p>";
	}
	else {
		echo "<p><b>Success!</b></p>\n";
	}
}

InitDBConnection();
main();
CloseDBConnection();

PageFooter();
?>
