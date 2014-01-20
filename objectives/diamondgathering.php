<html>
<head>
</head>
<body>
	<h1>Diamond Search</h1>
	<br>
	<?php
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$diamondsearch1 = $mysqli->prepare("SELECT exp, diamonds, lastdiamondsearch FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
	$diamondsearch1->execute();
    $diamondsearch1->bind_result($exp, $diamonds, $lastdiamondsearch);
    $diamondsearch1->fetch();
    $diamondsearch1->close();
	
	$timespent = time() - $lastdiamondsearch;
	$timeleft = 900 - $timespent;

	if (isset($_POST['searchfordiamonds'])) {
		if ($timespent >= 900) {

			$newexp = $exp + 45;
			$chance = rand(1, 100);

			if ($chance > 95) { // 5% chance
				$updatetime = time();
				$newdiamonds = $diamonds + 1;
				$updateitems = $mysqli->prepare("UPDATE users SET exp = ?, diamonds = ?, lastdiamondsearch = ? WHERE user_name = ?");
				$updateitems->bind_param('iiis', $newexp, $newdiamonds, $updatetime, $_SESSION['user_name']);
				$updateitems->execute();
				$updateitems->close();
				echo "You were fortunate enough to find a diamond! Use it with care.<br>";
				$timeleft = 900;
				$timespent = 0;
			}
			else {
				$updatetime = time();
				$updateitems = $mysqli->prepare("UPDATE users SET exp = ?, lastdiamondsearch = ? WHERE user_name = ?");
				$updateitems->bind_param('iis', $newexp, $updatetime, $_SESSION['user_name']);
				$updateitems->execute();
				$updateitems->close();
				echo "No diamond.<br>"; // change this text
				$timeleft = 900;
				$timespent = 0;
			}
	    }
	    else {
	    	echo "You need to wait 15 minutes between each diamond search!<br>";
	    }
	}

	$mysqli->close();

	if ($timespent >= 900) {
		echo "<br>Click to search for diamonds!";
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
		        	document.getElementById('countdown').innerHTML = "Click to search for diamonds!";
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
	<br><br>
	<form action="?page=diamondgathering" method="post">
		<button name='searchfordiamonds' type='submit' value='diamonds'>Search For Diamonds</button>
	</form>
</body>
</html>