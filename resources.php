<html>
<head>
</head>
<body>
	<!-- Add maximum 16? characters in username -->
	
	<?php
	
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$showresources = $mysqli->prepare("SELECT user_name, title, level, exp, wood, food, stone, gold, iron, diamonds, house, tower, citizen, warrior1, warrior2, warrior1level, warrior2level, hunters, dogs, shield, shieldtime, townlevel FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
	$showresources->execute();
    $showresources->bind_result($username, $title, $level, $exp, $wood, $food, $stone, $gold, $iron, $diamonds, $house, $tower, $citizen, $warrior1, $warrior2, $warrior1level, $warrior2level, $hunters, $dogs, $shield, $shieldtime, $townlevel);
    $showresources->fetch();
    $showresources->close();

    // need to start with level 1 and title 1
	$expneeded = pow($level, 3) + $level * 100 + 100;
	$zero = 0;

	if ($exp >= $expneeded)
	{
		$level = $level + 1;
		$exp = $exp - $expneeded;
		$expneeded = pow($level, 3) + $level * 100 + 100;
		
		if ($level == 1) {
			$title = "Beginner";
		}
		if ($level == 5) { // activate pvp.
			$activatepvp = $mysqli->prepare("UPDATE users SET shield = ?,
			   shieldtime = ?
			   WHERE user_name = ?");
			$activatepvp->bind_param('iis',
				$zero,
				$zero,
				$_SESSION['user_name']);
			$activatepvp->execute();
			$activatepvp->close();
			
		}
		else if ($level == 10) {
			$title = "Climber";
		}
		else if ($level == 20) {
			$title = "Pro";
		}
		else if ($level == 60) {
			$title = "King";
		}

		// governour
		// chief
		// high-chief
		// soldier
		// elite-soldier

		$levelup = $mysqli->prepare("UPDATE users SET level = ?,
		   exp = ?,
		   title = ?
		   WHERE user_name = ?");
		$levelup->bind_param('iiss',
		   $level,
		   $exp,
		   $title,
		   $_SESSION['user_name']);
		$levelup->execute();
		$levelup->close();

		echo "<b>Congratulations!<br>You just leveled up!</b><br>";
	}

	$mysqli->close();

	$attack = $warrior1 * (5+$warrior1level) + $warrior2 * (4+$warrior2level);
	$population = $house * 5;
	$defence = $warrior1 * (1+$warrior1level) + $warrior2 * (4+$warrior2level) + ($tower * 10);
	$currentpop = $citizen + $warrior1 + $warrior2 + $hunters + $dogs;
	$townlevel = $townlevel + 1;
	$bunkerspace = $townlevel * 5;

 	?>

 	<!-- show a "Level up" page? displaying what u can do new in that level -->

 	<?php
 		echo "<a href='index.php?logout'>Logout</a>";
 		echo "<br><h2><u>$username</u></h2>";
 		echo "$title<br>";
 		echo "	
 				Level:&nbsp&nbsp $level<br><br>
 				<b><u>Exp</u></b><br> $exp / $expneeded<br><br>
				<b><u>Resources</u></b><br>
				Wood:&nbsp&nbsp&nbsp $wood<br>
			    Food:&nbsp&nbsp&nbsp $food<br>
			    Stone:&nbsp&nbsp&nbsp $stone<br>
			    Gold:&nbsp&nbsp&nbsp $gold<br>
			    Iron:&nbsp&nbsp&nbsp $iron<br>
			    Diamonds:&nbsp&nbsp&nbsp $diamonds<br><br>
			    <b><u>City</u></b><br>
			    Pop:&nbsp&nbsp&nbsp $currentpop / $population<br>
			    Citizens:&nbsp&nbsp&nbsp $citizen<br>
			    Attack:&nbsp&nbsp&nbsp $attack<br>
			    Defence:&nbsp&nbsp&nbsp $defence<br>
			    Bunker:&nbsp&nbsp&nbsp 0 / $bunkerspace
			";
 	?>
</body>
</html>