<html>
<head>
	<title>test</title>
</head>
<body>
	<h1>Pray to the gods</h1>

	<?php 

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}

	$updatetime = $mysqli->prepare("UPDATE users SET praying = now()
	   WHERE user_name = ?");
	$updatetime->bind_param('s',
	   $_SESSION['user_name']);
	$updatetime->execute();
	$updatetime->close();

	if ($checktimer = $mysqli->prepare("SELECT praying, praying2 FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';")) {
	    $checktimer->execute();
	    $checktimer->bind_result($timer1, $timer2);
	    $checktimer->fetch();
	    $checktimer->close();
	}

	$time1 = strtotime($timer1);
	$time2 = strtotime($timer2);
	$difference = $time2 - $time1;

	if ($difference > 0) {
		?>
		<span id="prayed"></span>
		<span id="countdown" class="timer"></span>
		<script>
			var seconds = <?php echo "$difference"; ?>;
			var prayedtext = "<p>You have already prayed the last 3 minutes.</p>Time remaining: ";
			var minutes = Math.round((seconds - 30)/60);
			var remainingSeconds = seconds % 60;
			if (remainingSeconds < 10) {
		        remainingSeconds = "0" + remainingSeconds; 
		    }
			document.getElementById('prayed').innerHTML = prayedtext;
			document.getElementById('countdown').innerHTML = minutes + ":" + remainingSeconds;
			function secondPassed() {
		    	if (seconds == 0) {
		        	clearInterval(countdownTimer);
		        	document.getElementById('prayed').innerHTML = "<p>You can now pray again!</p>";
		        	document.getElementById('countdown').innerHTML = "Please choose a god to pray to.";
		    	} else {
		        	seconds--;
		    	}
		    	var minutes = Math.round((seconds - 30)/60);
		    	var remainingSeconds = seconds % 60;
		    	if (remainingSeconds < 10) {
		        	remainingSeconds = "0" + remainingSeconds; 
		    	}
		    	if (seconds > 0) {
		        	document.getElementById('countdown').innerHTML = minutes + ":" + remainingSeconds;
		    	}
			}
		 
		var countdownTimer = setInterval('secondPassed()', 1000);
		</script>
		<?php
	}

	else {

		if (isset($_POST['resource'])) {

			$resource = $_POST['resource'];
			
			if ($resource == "wood" || $resource == "food") {
				$amount = rand(1, 110);
				if ($amount > 100) { // success rate 90%
					$amount = 0;
					echo "You prayed, but the gods didn't hear you.<br>";
				}
				else {
					echo "Praying successful!<br>";
				}
			}
			else {
				$amount = rand(1, 60);
				if ($amount > 50) { // successrate 80%
					$amount = 0;
					echo "You prayed, but the gods didn't hear you.<br>";
				}
				else {
					echo "Praying successful!<br>";
				}
			}

			if ($stmt = $mysqli->prepare("SELECT $resource, exp FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';")) {
			    $stmt->execute();
			    $stmt->bind_result($now, $checkexp);
			    $stmt->fetch();
			    $stmt->close();
			}

			$newamount = $now + $amount;

			$newexp = $checkexp + 30;

			$test11 = $mysqli->prepare("UPDATE users SET $resource = ?,
				exp = ?,
				praying = now(),
				praying2 = DATE_ADD(now(), INTERVAL 3 MINUTE) 
				WHERE user_name = ?");
			$test11->bind_param('iis',
				$newamount,
				$newexp,
				$_SESSION['user_name']);
			$test11->execute();
			$test11->close();

			echo "<br>You recieved $amount $resource!";
		}
	}

	$mysqli->close();

	?>

	<br><br>
	<form action="?page=praying" method="post">
	<?php
		echo "Choose a god to pray to<br>";
		$array = array("wood", "food", "stone", "gold", "iron");
		$arrayvalues = array("God1_Wood", "God2_Food", "God3_Stone", "God4_Gold", "God5_iron");
		for ($i=0; $i < sizeof($array); $i++) { 
			echo "<button name='resource' type='submit' style='width: 150px;' value='$array[$i]'>$arrayvalues[$i]</button><br>";
		}
	?>
	</form>
</body>
</html>