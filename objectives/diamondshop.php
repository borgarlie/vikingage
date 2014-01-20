<html>
<head>
</head>
<body>
	<h1>Diamond Shop</h1>

	<?php
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$resourceshopping = $mysqli->prepare("SELECT shield, diamonds, shieldtime FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
	$resourceshopping->execute();
    $resourceshopping->bind_result($shield, $diamonds, $shieldtime);
    $resourceshopping->fetch();
    $resourceshopping->close();

    // When making account or buying a shield: Shieldtime = time() + days.
    // no "shield"... In pvp and on diamond shop, just check if time() > shieldtime.

    if ($shieldtime > time()) {
    	if ($shield > time() ) {
    		echo "You are currently shielded.<br>";
    	}
    	else {
    		echo "You still have a cooldown on your shield.";
    	}
    }

	if (isset($_POST['item'])) {

		$item = $_POST['item'];

		if ($item == "shield1") {
			$pricediamonds = 2;
		}
		else if ($item == "shield7") {
			$pricediamonds = 10;
		}

	    if ($diamonds >= $pricediamonds) {
	    	if ($item == 'shield1') {
	    		if ( time() > $shieldtime ) {
	    			$shield = time() + 86400;
	    			$shieldtime = time() + 172800;
	    			$diamonds = $diamonds - 2;
	    			$updateitems = $mysqli->prepare("UPDATE users SET shield = ?,
					   diamonds = ?,
					   shieldtime = ?
					   WHERE user_name = ?");
					$updateitems->bind_param('iiis',
						$shield,
						$diamonds,
						$shieldtime,
						$_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();
					echo "<br>Purchase successful!<br>You just received a 24 hour shield from PvP activity.";
	    		}
	    		else {
	    			$displaycd = date("d.m.Y - H:i:s", $shieldtime);
	    			echo "<br>You need to wait until your shield cooldown has weared out.<br> The CD wears out: $displaycd";
	    		}
	    	}
	    	if ($item == 'shield7') {
	    		if ( time() > $shieldtime ) {
	    			$shield = time() + 604800;
	    			$shieldtime = time() + 1209600;
	    			$diamonds = $diamonds - 10;
	    			$updateitems = $mysqli->prepare("UPDATE users SET shield = ?,
					   diamonds = ?,
					   shieldtime = ?
					   WHERE user_name = ?");
					$updateitems->bind_param('iiis',
						$shield,
						$diamonds,
						$shieldtime,
						$_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();
					echo "<br>Purchase successful!<br>You just received a 7 days shield from PvP activity.";
	    		}
	    		else {
	    			$displaycd = date("d.m.Y - H:i:s", $shieldtime);
	    			echo "<br>You need to wait until your shield cooldown has weared out.<br> The CD wears out: $displaycd";

	    		}
	    	}
		}
		else {
	    	echo "<br>You don't have enough diamonds.<br>";
	  	}
	}
	$mysqli->close();
	?>
	<center>
	<table  border="0" cellspacing="15">
		<tr style = "text-align: center;"> 
			<td>
				<b>Item</b>
			</td>
			<td>
				<b>Price</b>
			</td>
			<td>
				<b>Buy</b>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				PvP shield (1 day)
			</td>
			<td style = "text-align: center;">
				2 diamonds
			</td>
			<td style = "text-align: right;">
				<form action="?page=diamondshop" method="post">
					<button name='item' type='submit' value='shield1'>Buy</button><br>
				</form>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				PvP shield (7 days)
			</td>
			<td style = "text-align: center;">
				10 diamonds
			</td>
			<td style = "text-align: right;">
				<form action="?page=diamondshop" method="post">
					<button name='item' type='submit' value='shield7'>Buy</button><br>
				</form>
			</td>
		</tr>
	</table>
	</center>
	After a shield has weared out, you cannot buy another shield for the same amount of days you just had a shield on.
</body>
</html>