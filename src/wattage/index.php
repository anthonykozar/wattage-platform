<?php

require_once 'config.php';
require_once 'version.php';
require_once 'common.php';

PageHeader('WATTAGE game server');

echo "<h1>WATTAGE game server</h1>\n";
echo "<h2>Version " . WATTAGE_VERSION . "</h2>\n";


// connect to the database server and check for errors
$dbconn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (mysqli_connect_errno()) {
	printf("Connection attempt failed: %s\n", mysqli_connect_errno());
	exit();
}
else {
	// get the games table and report the total count
	$sql = 'SELECT * FROM ' . DB_PREFIX . 'games ORDER BY date_started';

	$result = mysqli_query($dbconn, $sql);
	if ($result) {
		$numrows = mysqli_num_rows($result);
		printf("<p>Games: %d</p>", $numrows);
		
		// check if a game id was passed with the request
		if (isset($_GET) && array_key_exists('gid', $_GET)) {
			$gameid = (int)($_GET['gid']);
			if ($gameid > 0) {
				// search for a row with a matching id
				while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					if ($row['id'] == $gameid) {
						// display info about the game
						printf("<p>Game #%d: %s, %d players, started on %s.</p>", $gameid,
							$row['type_slug'], $row['num_players'], $row['date_started']);
						break;
					}
				}
			}
		}
		
		mysqli_free_result($result);
	}
	else {
		printf("<p>Could not retrieve game records: %s</p>", mysqli_error($dbconn));
		echo "<p>Query was:<br>$sql</p>";
	}

	// get the players table and report the total count
	$sql = 'SELECT * FROM ' . DB_PREFIX . 'players ORDER BY username';

	$result = mysqli_query($dbconn, $sql);
	if ($result) {
		$numrows = mysqli_num_rows($result);
		printf("<p>Players: %d</p>", $numrows);
		
		// check if a player id was passed with the request
		if (isset($_GET) && array_key_exists('pid', $_GET)) {
			$playerid = (int)($_GET['pid']);
			if ($playerid > 0) {
				// search for a row with a matching id
				while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					if ($row['id'] == $playerid) {
						// display info about the game
						printf("<p>Player #%d: %s (%s), last active on %s.</p>", $playerid,
							 ($row['anonymous'] ? 'anonymous' : $row['name']),
							 ($row['anonymous'] ? $row['anon_token'] : $row['username']),
							 $row['date_last_active']);
						break;
					}
				}
			}
		}
		
		mysqli_free_result($result);
	}
	else {
		printf("<p>Could not retrieve player records: %s</p>", mysqli_error($dbconn));
		echo "<p>Query was:<br>$sql</p>";
	}

	mysqli_close($dbconn);
}

// show GET parameters
echo "<h3>\$_GET:</h3>\n<pre>";
print_r($_GET);
echo "</pre>";

PageFooter();
?>
