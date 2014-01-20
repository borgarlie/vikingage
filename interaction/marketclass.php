<?php
/**
*	Class for Market - Viking Age
*/
class marketclass
{
	private $item = "";

	function __construct()
	{
		if (isset($_POST['buy'])) {
			$this->item = $_POST['buy'];
			$this->buyItem();
		}
		else if (isset($_POST['sell'])) {
			$this->sellNew();
		}
		else {
			$this->viewAll();
		}
	}

	// create a database: Market with the values: (auto increment id), auto insert time, user_name, resource, stack, exchance and price.

	private function viewAll() {	
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}
		// Select username(user_name) of player selling, the resource the player is selling(resource) and stack(stack) of that resource.
		// What resource to trade for (exchance), and how much of that resource(price)
		if ($stmt = $mysqli->prepare("SELECT id, user_name, resource, stack, exchance, price FROM market ORDER BY time DESC")) {
		    $stmt->execute();
		    /* bind variables to prepared statement */
		    $stmt->bind_result($id, $col1, $col2, $col3, $col4, $col5);
		    /* fetch values */
		    echo "<table cellspacing ='15'><tr><td><u>Name</u>";
			echo "</td><td><u>Resource</u></td><td><u>Stack</u></td><td><u>Exchance</u></td><td><u>Price</u></td><td><u>Buy</u></td></tr>";
		    while ($stmt->fetch()) {
		        echo "<tr><td>$col1</td><td>$col2</td><td>$col3</td><td>$col4</td><td>$col5</td><td>Buy</td></tr>";
		    }
		    echo "</table>";
		    /* close statement */
		    $stmt->close();
		}
		/* close connection */
		$mysqli->close();
	}

	private function buyItem() {

	}

	private function sellNew() {

	}

} // end of class