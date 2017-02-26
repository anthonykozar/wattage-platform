<?php

require_once 'config.php';
require_once 'version.php';
require_once 'common.php';

PageHeader('WATTAGE game server');

echo "<h1>WATTAGE game server</h1>\n";
echo "<h2>Version " . WATTAGE_VERSION . "</h2>\n";

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
	
	// count the games in the games table
	$sql = 'SELECT count(*) FROM ' . DB_PREFIX . 'games';
	$result = mysqli_query($dbconn, $sql);
	if ($result && mysqli_num_rows($result) > 0) {
		// get the value of the first column of first row of the result
		$row = mysqli_fetch_row($result);
		$gamecount = $row[0];
		mysqli_free_result($result);
	}
	else {
		printf("<p>Could not count games: %s</p>", mysqli_error($dbconn));
		echo "<p>Query was:<br>$sql</p>";
	}

	// count the players in the players table
	$sql = 'SELECT count(*) FROM ' . DB_PREFIX . 'players';
	$result = mysqli_query($dbconn, $sql);
	if ($result && mysqli_num_rows($result) > 0) {
		// get the value of the first column of first row of the result
		$row = mysqli_fetch_row($result);
		$playercount = $row[0];
		mysqli_free_result($result);
	}
	else {
		printf("<p>Could not count players: %s</p>", mysqli_error($dbconn));
		echo "<p>Query was:<br>$sql</p>";
	}
	
	// report the counts
	printf("<p>Games: %d, Players: %d</p>", $gamecount, $playercount);
	
	/* Process any GET parameters */
	
	// check if a game id was passed with the request
	if (isset($_GET) && array_key_exists('gid', $_GET)) {
		$gameid = (int)($_GET['gid']);
		if ($gameid > 0) {
			// retrieve the specified game from the games table
			$sql = 'SELECT * FROM ' . DB_PREFIX . "games WHERE id = '$gameid'";
			$result = mysqli_query($dbconn, $sql);
			if ($result) {
				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						if ($row['id'] == $gameid) {
							// display info about the game
							printf("<p>Game #%d: %s, %d players, started on %s.</p>", $gameid,
								$row['type_slug'], $row['num_players'], $row['date_started']);
							break;
						}
					}
				}
				else {
					echo "<p>No matching game record for gid=$gameid</p>";
				}
				mysqli_free_result($result);
			}
			else {
				printf("<p>Could not retrieve game record: %s</p>", mysqli_error($dbconn));
				echo "<p>Query was:<br>$sql</p>";
			}

		}
	}

	// check if a player id was passed with the request
	if (isset($_GET) && array_key_exists('pid', $_GET)) {
		$playerid = (int)($_GET['pid']);
		if ($playerid > 0) {
			// retrieve the specified player from the players table
			$sql = 'SELECT * FROM ' . DB_PREFIX . "players WHERE id = '$playerid'";
			$result = mysqli_query($dbconn, $sql);
			if ($result) {
				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						if ($row['id'] == $playerid) {
							// display info about the player
							printf("<p>Player #%d: %s (%s), last active on %s.</p>", $playerid,
								 ($row['anonymous'] ? 'anonymous' : $row['name']),
								 ($row['anonymous'] ? $row['anon_token'] : $row['username']),
								 $row['date_last_active']);
							break;
						}
					}
				}
				else {
					echo "<p>No matching player record for pid=$playerid</p>";
				}
				mysqli_free_result($result);
			}
			else {
				printf("<p>Could not retrieve player record: %s</p>", mysqli_error($dbconn));
				echo "<p>Query was:<br>$sql</p>";
			}

		}
	}

}

InitDBConnection();
main();
CloseDBConnection();

// show GET parameters
echo "<h3>\$_GET:</h3>\n<pre>";
print_r($_GET);
echo "</pre>";

PageFooter();
?>
