<html>
<head>
</head>
<body>
	<h1>Hunting Training</h1>
	<br>

	<?php
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$train = $mysqli->prepare("SELECT gold, iron, hunterslevel, dogslevel, huntersexp, dogsexp, lasthuntingtrain FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
	$train->execute();
    $train->bind_result($gold, $iron, $hunterslevel, $dogslevel, $huntersexp, $dogsexp, $lasthuntingtrain);
    $train->fetch();
    $train->close();
	
	$timespent = time() - $lasthuntingtrain;
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

			if ($choosetrain == "huntersexp") {
				if ($gold >= 500) {
					$newgold = $gold - 500;
					$updatetime = time();
					$newhuntersexp = $huntersexp + $randomexp;

					$updateitems = $mysqli->prepare("UPDATE users SET gold = ?, huntersexp = ?, lasthuntingtrain = ? WHERE user_name = ?");
					$updateitems->bind_param('iiis', $newgold, $newhuntersexp, $updatetime, $_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();
					echo "You trained your hunters for 500 gold and they recieved $randomexp % exp.<br>";
					$timeleft = 7200;
					$timespent = 0;
					$huntersexp = $newhuntersexp;

					if ($huntersexp >= $expneeded) {
						$hunterslevel = $hunterslevel + 1;
						$huntersexp = $huntersexp - $expneeded;

						$levelup = $mysqli->prepare("UPDATE users SET hunterslevel = ?,
						   huntersexp = ?
						   WHERE user_name = ?");
						$levelup->bind_param('iis',
						   $hunterslevel,
						   $huntersexp,
						   $_SESSION['user_name']);
						$levelup->execute();
						$levelup->close();

						echo "<b>Congratulations!<br>Your hunters just received 1 more attack upon leveling up!</b><br>";
					}
				}
				else {
					echo "Not enough gold.<br>";
				}
			}
			else {
				if ($iron >= 400) {
					$newiron = $iron - 400;
					$updatetime = time();
					$dogsexp = $dogsexp + $randomexp;

					$updateitems = $mysqli->prepare("UPDATE users SET iron = ?, dogsexp = ?, lasthuntingtrain = ? WHERE user_name = ?");
					$updateitems->bind_param('iiis', $newiron, $dogsexp, $updatetime, $_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();
					echo "You trained your dogs for 400 iron and they recieved $randomexp % exp.<br>";
					$timeleft = 7200;
					$timespent = 0;

					if ($dogsexp >= $expneeded) {
						$dogslevel = $dogslevel + 1;
						$dogsexp = $dogsexp - $expneeded;

						$levelup = $mysqli->prepare("UPDATE users SET dogslevel = ?,
						   dogsexp = ?
						   WHERE user_name = ?");
						$levelup->bind_param('iis',
						   $dogslevel,
						   $dogsexp,
						   $_SESSION['user_name']);
						$levelup->execute();
						$levelup->close();

						echo "<b>Congratulations!<br>Your dogs just received 1 more tracking skill upon leveling up!</b><br>";
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
	<table  border="0" cellspacing="14">
		<tr>
			<td style = "text-align: left;">
				<?php
				echo "Hunter Level: $hunterslevel";
				?>
			</td>
			<td style = "text-align: center;">
				<?php
				echo "Hunter Exp: $huntersexp %";
				?>
			</td>
			<td style = "text-align: center;">
				<form action="?page=huntingtraining" method="post">
					<button name='train' type='submit' value='huntersexp'>Train your hunters</button>
				</form>
			</td>
			<td style = "text-align: center;">
				Price: 500 gold
			</td>
		</tr>
		<tr>
			<td style = "text-align: center;">
				<?php
				echo "Dog Level: $dogslevel";
				?>
			</td>
			<td style = "text-align: center;">
				<?php
				echo "Dog Exp: $dogsexp %";
				?>
			</td>
			<td style = "text-align: center;">
				<form action="?page=huntingtraining" method="post">
					<button name='train' type='submit' value='dogsexp'>Train your dogs</button>
				</form>
			</td>
			<td style = "text-align: center;">
				Price: 400 iron
			</td>
		</tr>
	</table>
	</center>
	<br>
	Hunters receive 1 more damage each hunter upon leveling up.
	<br>
	Dogs receive 1 more tracking skill upon leveling up.
</body>
</html>