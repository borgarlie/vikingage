<html>
<head>
</head>
<body>
	<h1>Your City</h1>

	<?php
	if (isset($_POST['item'])) {
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$item = $_POST['item'];

		$resourceshopping = $mysqli->prepare("SELECT townlevel, towerlevel, warrior1level, warrior2level, house, tower, citizen, warrior1, warrior2, wood, food, stone, gold, iron FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
		$resourceshopping->execute();
	    $resourceshopping->bind_result($townlevel, $towerlevel, $warrior1level, $warrior2level, $house, $tower, $citizen, $warrior1, $warrior2, $wood, $food, $stone, $gold, $iron);
	    $resourceshopping->fetch();
	    $resourceshopping->close();

	    $towndisplaylevel = $townlevel + 1;
	    $towerdamage = 10 + $towerlevel;
	    $warrior1damage = 5 + $warrior1level;
	    $warrior2damage = 4 + $warrior2level;
	    $warrior1defence = 1 + $warrior1level;
	    $warrior2defence = 4 + $warrior2level;
	    $townupgradeprice = $towndisplaylevel * 1000;

	    if ($item == "townlevel") {
			$pricewood = $towndisplaylevel * 1000;
			$pricefood = $towndisplaylevel * 1000;
			$pricestone = $towndisplaylevel * 1000;
			$pricegold = $towndisplaylevel * 1000;
			$priceiron = $towndisplaylevel * 1000;
		}
		else if ($item == "towerlevel") {
			$pricewood = 10000;
			$pricefood = 0;
			$pricestone = 5000;
			$pricegold = 0;
			$priceiron = 0;
		}

	    if ($wood >= $pricewood && $food >= $pricefood && $stone >= $pricestone && $gold >= $pricegold && $iron >= $priceiron) {
	    	$wood = $wood - $pricewood;
			$food = $food - $pricefood;
			$stone = $stone - $pricestone;
			$gold = $gold - $pricegold;
			$iron = $iron - $priceiron;

			$amountitem = $$item + 1;

			$updateitems = $mysqli->prepare("UPDATE users SET $item = ?,
			   wood = ?,
			   food = ?,
			   stone = ?,
			   gold = ?,
			   iron = ?
			   WHERE user_name = ?");
			$updateitems->bind_param('iiiiiis',
				$amountitem,
				$wood,
				$food,
				$stone,
				$gold,
				$iron,
				$_SESSION['user_name']);
			$updateitems->execute();
			$updateitems->close();

			echo "Purchase successful!<br>You upgraded your $item!";
	    }
	    else {
	    	echo "You don't have enough resources.<br>";
	    }
		$mysqli->close();
		if ($item == "townlevel") {
			$towndisplaylevel += 1;
		}
		else if ($item == "towerlevel") {
			$towerlevel += 1;
			$towerdamage += 1;
		}
	}
	else {
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$resourceshopping = $mysqli->prepare("SELECT townlevel, towerlevel, warrior1level, warrior2level, house, tower, citizen, warrior1, warrior2 FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
		$resourceshopping->execute();
	    $resourceshopping->bind_result($townlevel, $towerlevel, $warrior1level, $warrior2level, $house, $tower, $citizen, $warrior1, $warrior2);
	    $resourceshopping->fetch();
	    $resourceshopping->close();
	    $mysqli->close();

	    $towndisplaylevel = $townlevel + 1;
	    $towerdamage = 10 + $towerlevel;
	    $warrior1damage = 5 + $warrior1level;
	    $warrior2damage = 4 + $warrior2level;
	    $warrior1defence = 1 + $warrior1level;
	    $warrior2defence = 4 + $warrior2level;
	    $townupgradeprice = $towndisplaylevel * 1000;
	}
	?>

	<center>
	<table  border="0" cellspacing="12">
		<tr style = "text-align: center;">
			<td>
				<b>Item</b>
			</td>
			<td>
				<b>Your stack</b>
			</td>
			<td>
				<b>Upgrade Price</b>
			</td>
			<td>
				<b>Upgrade</b>
			</td>
			<td>
				<b>Att / Def</b>
			</td>
			<td>
				<b>Level</b>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Town:
			</td>
			<td style = "text-align: center;">
			</td>
			<td style = "text-align: center;">
				<?php
				echo "$townupgradeprice of Each";
				?>
			</td>
			<td style = "text-align: right;">
				<form action="?page=city" method="post">
					<button name='item' type='submit' value='townlevel'>Upgrade</button><br>
				</form>
			</td>
			<td style = "text-align: right;">
			</td>
			<td style = "text-align: right;">
				<?php
				echo "$towndisplaylevel";
				?>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				House:
			</td>
			<td style = "text-align: center;">
				<?php
				echo "$house";
				?>
			</td>
			<td style = "text-align: center;">
			</td>
			<td style = "text-align: right;">
			</td>
			<td style = "text-align: right;">
			</td>
			<td style = "text-align: right;">
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Tower:
			</td>
			<td style = "text-align: center;">
				<?php
				echo "$tower";
				?>
			</td>
			<td style = "text-align: center;">
				10 000 Wood<br>5 000 Stone
			</td>
			<td style = "text-align: right;">
				<form action="?page=city" method="post">
					<button name='item' type='submit' value='towerlevel'>Upgrade</button><br>
				</form>
			</td>
			<td style = "text-align: right;">
				<?php
				echo "0 / $towerdamage";
				?>
			</td>
			<td style = "text-align: right;">
				<?php
				echo "$towerlevel";
				?>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Citizen:
			</td>
			<td style = "text-align: center;">
				<?php
				echo "$citizen";
				?>
			</td>
			<td style = "text-align: center;">
			</td>
			<td style = "text-align: right;">
			</td>
			<td style = "text-align: right;">
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Viking-Longsword:
			</td>
			<td style = "text-align: center;">
				<?php
				echo "$warrior1";
				?>
			</td>
			<td style = "text-align: center;">
			</td>
			<td style = "text-align: right;">
			</td>
			<td style = "text-align: right;">
				<?php
				echo "$warrior1damage / $warrior1defence";
				?>
			</td>
			<td style = "text-align: right;">
				<?php
				echo "$warrior1level";
				?>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Viking-Bowman:
			</td>
			<td style = "text-align: center;">
				<?php
				echo "$warrior2";
				?>
			</td>
			<td style = "text-align: center;">
			</td>
			<td style = "text-align: right;">
			</td>
			<td style = "text-align: right;">
				<?php
				echo "$warrior2damage / $warrior2defence";
				?>
			</td>
			<td style = "text-align: right;">
				<?php
				echo "$warrior2level";
				?>
			</td>
		</tr>
	</table>
	</center>
</body>
</html>