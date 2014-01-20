<html>
<head>
</head>
<body>
	<h1>Shop</h1>

	<?php
	if (isset($_POST['item'])) {
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$item = $_POST['item'];

		if ($item == "house") {
			$pricewood = 250;
			$pricefood = 0;
			$pricestone = 0;
			$pricegold = 0;
			$priceiron = 0;
		}
		else if ($item == "tower") {
			$pricewood = 400;
			$pricefood = 0;
			$pricestone = 300;
			$pricegold = 0;
			$priceiron = 0;
		}
		else if ($item == "citizen") {
			$pricewood = 0;
			$pricefood = 300;
			$pricestone = 0;
			$pricegold = 0;
			$priceiron = 0;
		}
		else if ($item == "warrior1") {
			$pricewood = 0;
			$pricefood = 350;
			$pricestone = 0;
			$pricegold = 100;
			$priceiron = 0;
		}
		else if ($item == "warrior2") {
			$pricewood = 0;
			$pricefood = 450;
			$pricestone = 0;
			$pricegold = 200;
			$priceiron = 0;
		}
		else if ($item == "hunters") {
			$pricewood = 0;
			$pricefood = 500;
			$pricestone = 0;
			$pricegold = 100;
			$priceiron = 0;
		}
		else if ($item == "dogs") {
			$pricewood = 0;
			$pricefood = 300;
			$pricestone = 0;
			$pricegold = 0;
			$priceiron = 100;
		}

		$resourceshopping = $mysqli->prepare("SELECT $item, wood, food, stone, gold, iron, warrior1, warrior2, citizen, house, hunters, dogs, townlevel FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
		$resourceshopping->execute();
	    $resourceshopping->bind_result($amountitem, $wood, $food, $stone, $gold, $iron, $count1, $count2, $count3, $counthouse, $hunters, $dogs, $townlevel);
	    $resourceshopping->fetch();
	    $resourceshopping->close();

	    $popleft = ($counthouse * 5) - $count1 - $count2 - $count3 - $hunters - $dogs;

	    if ($wood >= $pricewood && $food >= $pricefood && $stone >= $pricestone && $gold >= $pricegold && $iron >= $priceiron) {
	    	// update with new item.
	    	$pop = true;
	    	if ($item == "warrior1" || $item == "warrior2" || $item == "citizen" || $item == "hunters" || $item == "dogs") {
	    		if ($popleft <= 0) {
	    			$pop = false;
	    		} // checks if population is max
	    	}
	    	if ($pop == true) {
	    		if ($counthouse >= ($townlevel + 1) * 20 && $item == "house") {
					echo "You need to upgrade your town before buying more houses!";
				}
				else {
			    	$wood = $wood - $pricewood;
					$food = $food - $pricefood;
					$stone = $stone - $pricestone;
					$gold = $gold - $pricegold;
					$iron = $iron - $priceiron;

					$amountitem = $amountitem + 1;

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

					echo "Purchase successful!<br>You recieved a new $item!";
				}
			}
			else {
				echo "You don't have enough space<br>Buy more houses to increase your maximum population<br>";
			}
	    }
	    else {
	    	echo "You don't have enough resources.<br>";
	    }
		$mysqli->close();
	}
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
				House:
			</td>
			<td style = "text-align: center;">
				250 Wood
			</td>
			<td style = "text-align: right;">
				<form action="?page=shop" method="post">
					<button name='item' type='submit' value='house'>Buy</button><br>
				</form>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Tower:
			</td>
			<td style = "text-align: center;">
				400 Wood / 300 stone
			</td>
			<td style = "text-align: right;">
				<form action="?page=shop" method="post">
					<button name='item' type='submit' value='tower'>Buy</button><br>
				</form>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Citizen:
			</td>
			<td style = "text-align: center;">
				300 Food
			</td>
			<td style = "text-align: right;">
				<form action="?page=shop" method="post">
					<button name='item' type='submit' value='citizen'>Buy</button><br>
				</form>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Viking-Longsword:
			</td>
			<td style = "text-align: center;">
				350 Food / 100 Gold
			</td>
			<td style = "text-align: right;">
				<form action="?page=shop" method="post">
					<button name='item' type='submit' value='warrior1'>Buy</button><br>
				</form>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Viking-Bowman:
			</td>
			<td style = "text-align: center;">
				450 Food / 200 Gold
			</td>
			<td style = "text-align: right;">
				<form action="?page=shop" method="post">
					<button name='item' type='submit' value='warrior2'>Buy</button><br>
				</form>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Hunter:
			</td>
			<td style = "text-align: center;">
				500 Food / 100 Gold
			</td>
			<td style = "text-align: right;">
				<form action="?page=shop" method="post">
					<button name='item' type='submit' value='hunters'>Buy</button><br>
				</form>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Dog:
			</td>
			<td style = "text-align: center;">
				300 Food / 100 Iron
			</td>
			<td style = "text-align: right;">
				<form action="?page=shop" method="post">
					<button name='item' type='submit' value='dogs'>Buy</button><br>
				</form>
			</td>
		</tr>
	</table>
	</center>
</body>
</html>