<?php
/**
*	Class for Player Profiles - Viking Age
*/
class playerclass
{
	private $playername = "";
	private $level = "";
	private $title = "";

	function __construct()
	{
		echo "<h1>Player Profiles</h1>";
		if (isset($_POST['player'])) { // search buttons
			$this->playername = $_POST['player'];
		}
		else if (isset($_GET['player'])) { // links from ranking
			$this->playername = $_GET['player'];
		}
		else {
			$this->playername = $_SESSION['user_name']; // self
		}
        $this->updatePlayer();
	}

	private function updatePlayer() {	
		// Add description to database and use it on display(update) aswell.
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}
		$stmt = $mysqli->prepare("SELECT user_name, level, title FROM users WHERE user_name = '" . $this->playername . "' ");
	    $stmt->execute();
	    $stmt->bind_result($col1, $col2, $col3);
	    $stmt->fetch();
	    $stmt->close();
		$mysqli->close();
		// assign values to object variables
		$this->playername = $col1;

	    if (empty($this->playername)) {
	    	$this->notExist();
	    }
	    else {
	    	// update rest of object variables
	    	$this->level = $col2;
	    	$this->title = $col3;
	    	$this->viewPlayer();
	    }
	}

	private function notExist() {
		$this->search();
		echo "<br>Player does not exist<br>";
		echo "Try to search for something different<br>";
	}

	private function search() {
		echo "<form action='?page=player' method='post'>";
		echo "<input type='text' name='player' required />";
		echo "<input type='submit' value='Search' />";
		echo "</form>";
	}

	private function sendMail() {
		echo "<form action='?page=mail' method='post'>";
		echo "<button name='sendTo' type='submit' style='width: 150px;' value='$this->playername'>Send Mail</button>";
		echo "</form>";
	}

	private function viewPlayer() {
		$this->search();
		echo "<br>Name: $this->playername<br>";
		echo "Title: $this->title<br>";
		echo "Level: $this->level<br><br>";
		echo "Description: hai...";
		$this->sendMail();
	}

} // end of class

// dont end php? ? >