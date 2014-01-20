<html>
<head>
	<!-- 	Gain exp.
	 	Levels:
	 		Reduce time between pvp fights?
			Reduce chance of loosing soldiers in pvp?
			Increase damage by total damage by % ?
			Same as hunting training?
			
			 - add 100 exp on each level? 
	 -->
</head>
<body>
	<h1>Battle Training</h1>
	<br>

	<?php
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$train = $mysqli->prepare("SELECT gold, iron, warrior1level, warrior2level, warrior1exp, warrior2exp, lastbattletrain FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
	$train->execute();
    $train->bind_result($gold, $iron, $warrior1level, $warrior2level, $warrior1exp, $warrior2exp, $lastbattletrain);
    $train->fetch();
    $train->close();
	
	$timespent = time() - $lastbattletrain;
	$timeleft = 7200 - $timespent;

	// Set max level = 10?
	// 
	//  If level = 10. "You are at maximum level ? "
	
	$expneeded = 100;
	// exp by %...
	// No player exp.

	if (isset($_POST['train'])) {
		if ($timespent >= 7200) {

			$choosetrain = $_POST['train'];
			$randomexp = rand(2,15);

			if ($choosetrain == "warrior1exp") {
				if ($gold >= 700) {
					$newgold = $gold - 700;
					$updatetime = time();
					$warrior1exp = $warrior1exp + $randomexp;

					$updateitems = $mysqli->prepare("UPDATE users SET gold = ?, warrior1exp = ?, lastbattletrain = ? WHERE user_name = ?");
					$updateitems->bind_param('iiis', $newgold, $warrior1exp, $updatetime, $_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();
					echo "You trained your Longswords for 700 gold and they recieved $randomexp % exp.<br>";
					$timeleft = 7200;
					$timespent = 0;

					if ($warrior1exp >= $expneeded) {
						$warrior1level = $warrior1level + 1;
						$warrior1exp = $warrior1exp - $expneeded;

						$levelup = $mysqli->prepare("UPDATE users SET warrior1level = ?,
						   warrior1exp = ?
						   WHERE user_name = ?");
						$levelup->bind_param('iis',
						   $warrior1level,
						   $warrior1exp,
						   $_SESSION['user_name']);
						$levelup->execute();
						$levelup->close();

						echo "<b>Congratulations!<br>Your Longswords just received 1 more attack and defence upon leveling up!</b><br>";
					}
				}
				else {
					echo "Not enough gold.<br>";
				}
			}
			else {
				if ($iron >= 700) {
					$newiron = $iron - 700;
					$updatetime = time();
					$warrior2exp = $warrior2exp + $randomexp;

					$updateitems = $mysqli->prepare("UPDATE users SET iron = ?, warrior2exp = ?, lastbattletrain = ? WHERE user_name = ?");
					$updateitems->bind_param('iiis', $newiron, $warrior2exp, $updatetime, $_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();
					echo "You trained your Bowmans for 700 iron and they recieved $randomexp % exp.<br>";
					$timeleft = 7200;
					$timespent = 0;

					if ($warrior2exp >= $expneeded) {
						$warrior2level = $warrior2level + 1;
						$warrior2exp = $warrior2exp - $expneeded;

						$levelup = $mysqli->prepare("UPDATE users SET warrior2level = ?,
						   warrior2exp = ?
						   WHERE user_name = ?");
						$levelup->bind_param('iis',
						   $warrior2level,
						   $warrior2exp,
						   $_SESSION['user_name']);
						$levelup->execute();
						$levelup->close();

						echo "<b>Congratulations!<br>Your Bowmans just received 1 more attack and defence upon leveling up!</b><br>";
					}
				}
				else {
					echo "Not enough iron.<br>";
				}
			}
	    }
	    else {
	    	echo "You need to wait 2 hours between each training!<br>";
	    }
	}

	$mysqli->close();

	if ($timespent >= 7200) { // 2 hours
		echo "<br>Choose what you want to train!";
	}
	else {
		?>
		<br>
		<span id="countdown" class="timer"></span>
		<script>
			var seconds = <?php echo "$timeleft"; ?>;
			var minutes = Math.round((seconds - 30)/60);
			var remainingSeconds = seconds % 60;
			if (remainingSeconds < 10) {
		        remainingSeconds = "0" + remainingSeconds; 
		    }
			document.getElementById('countdown').innerHTML = "Cooldown: " + minutes + ":" + remainingSeconds;
			function secondPassed() {
		    	if (seconds == 0) {
		        	clearInterval(countdownTimer);
		        	document.getElementById('countdown').innerHTML = "Choose what you want to train!";
		    	} else {
		        	seconds--;
		        	var minutes = Math.round((seconds - 30)/60);
			    	var remainingSeconds = seconds % 60;
			    	if (remainingSeconds < 10) {
			        remainingSeconds = "0" + remainingSeconds; 
			    	}
			    	document.getElementById('countdown').innerHTML = "Cooldown: " + minutes + ":" + remainingSeconds;
		    	}
			}
		var countdownTimer = setInterval('secondPassed()', 1000);
		</script>
		<?php
	}

	?>
	<center>
	<table  border="0" cellspacing="12">
		<tr>
			<td style = "text-align: left;">
				<?php
				echo "Longswords level: $warrior1level";
				?>
			</td>
			<td style = "text-align: center;">
				<?php
				echo "Exp: $warrior1exp %";
				?>
			</td>
			<td style = "text-align: center;">
				<form action="?page=battletraining" method="post">
					<button name='train' type='submit' value='warrior1exp'>Train</button>
				</form>
			</td>
			<td style = "text-align: center;">
				Price: 700 gold
			</td>
		</tr>
		<tr>
			<td style = "text-align: center;">
				<?php
				echo "Bowmans level: $warrior2level";
				?>
			</td>
			<td style = "text-align: center;">
				<?php
				echo "Exp: $warrior2exp %";
				?>
			</td>
			<td style = "text-align: center;">
				<form action="?page=battletraining" method="post">
					<button name='train' type='submit' value='warrior2exp'>Train</button>
				</form>
			</td>
			<td style = "text-align: center;">
				Price: 700 iron
			</td>
		</tr>
	</table>
	</center>
	<br>
	Warriors receive 1 more attack and defence each unit upon leveling up.
	<br>
</body>
</html>