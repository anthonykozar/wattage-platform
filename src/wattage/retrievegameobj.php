<?php

require_once 'config.php';
require_once 'version.php';
require_once 'common.php';

PageHeader('WATTAGE game server');

echo "<p><b>Retrieving game object...</b></p>\n";

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
	
	// check if a data id was passed with the request
	if (isset($_GET) && array_key_exists('dataid', $_GET)) {
		$dataid = (int)($_GET['dataid']);
		if ($dataid > 0) {
			// retrieve the specified object from the gamedata table
			$sql = 'SELECT * FROM ' . DB_PREFIX . "gamedata WHERE id = '$dataid'";
			$result = mysqli_query($dbconn, $sql);
			if ($result) {
				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						if ($row['id'] == $dataid) {
							// unserialize the object
							$obj = unserialize($row['obj_data']);
							if ($obj instanceof Game) {
								// display info about the game
								printf("<p>Game #%d: class %s.</p>", $row['game_id'], get_class($obj));
								echo "<pre>";
								print_r($row['obj_data']);
								echo "</pre>";
							}
							break;
						}
					}
				}
				else {
					echo "<p>No matching object record for dataid=$dataid</p>";
				}
				mysqli_free_result($result);
			}
			else {
				printf("<p>Could not retrieve object record: %s</p>", mysqli_error($dbconn));
				echo "<p>Query was:<br>$sql</p>";
			}

		}
	}
	else {
		echo "<p>No dataid passed to script!</p>";
	}
}

InitDBConnection();
main();
CloseDBConnection();

PageFooter();
?>
