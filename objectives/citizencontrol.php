<html>
<head>
	<title>test</title>
</head>
<body>
	<?php
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$citizennow = $mysqli->prepare("SELECT lastcollected, citizen, woodchopping, farming, stonemining, goldmining, ironmining FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
	$citizennow->execute();
    $citizennow->bind_result($last, $citizen, $woodchopping, $farming, $stonemining, $goldmining, $ironmining);
    $citizennow->fetch();
    $citizennow->close();

    $avible = $citizen - $woodchopping - $farming - $stonemining - $goldmining - $ironmining;

    $timespent = time() - $last;
    $inserttime = time();

    if (isset($_POST['add']) || isset($_POST['decrement'])) {
    	if ($timespent >= 3600) { // 3600
    		echo "Collect resources before changing citizen work!";
    	}
    	else {
    		if (isset($_POST['add'])){
		    	if ($avible > 0) {
					$source = $_POST['add'];
					$current = $$source;
					$newamount = $current + 1;
					// update db
					$updateitems = $mysqli->prepare("UPDATE users SET $source = ?
					   	WHERE user_name = ?");
					$updateitems->bind_param('is',
						$newamount,
						$_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();

					if ($timespent == time()) { // update and set timer for first time
						$settime = $mysqli->prepare("UPDATE users SET lastcollected = ?
					   	WHERE user_name = ?");
						$settime->bind_param('is',
							$inserttime,
							$_SESSION['user_name']);
						$settime->execute();
						$settime->close();
		    		}

					$$source = $newamount;
					$avible = $avible - 1;
				}
				else {
					echo "No citizens availible.";
				}
			}
			else if (isset($_POST['decrement'])) {
				$source = $_POST['decrement'];
				if ($$source > 0) {
					$current = $$source;
					$newamount = $current - 1;
					// update db
					$updateitems = $mysqli->prepare("UPDATE users SET $source = ?
					   	WHERE user_name = ?");
					$updateitems->bind_param('is',
						$newamount,
						$_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();

					$$source = $newamount;
					$avible = $avible + 1;
				}
				else {
					echo "Not enough to take off.";
				}
			}
    	}
    }

	if ($timespent >= 3600 && $timespent < 7200) { // 3600
		$woodnow = $woodchopping * 10;
		$foodnow = $farming * 10;
    	$stonenow = $stonemining * 5;
    	$goldnow = $goldmining * 5;
    	$ironnow = $ironmining * 5;
    	if (isset($_POST['collect'])) { // collect current and update time
    		$already = $mysqli->prepare("SELECT wood, food, stone, gold, iron FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
			$already->execute();
		    $already->bind_result($wood, $food, $stone, $gold, $iron);
		    $already->fetch();
		    $already->close();

		    $updatewood = $woodnow + $wood;
		    $updatefood = $foodnow + $food;
		    $updatestone = $stonenow + $stone;
		    $updategold = $goldnow + $gold;
		    $updateiron = $ironnow + $iron;

    		$updateall = $mysqli->prepare("UPDATE users SET lastcollected = ?,
    			wood = ?,
    			food = ?,
    			stone = ?,
    			gold = ?,
    			iron = ?
		   	WHERE user_name = ?");
			$updateall->bind_param('iiiiiis',
				$inserttime,
				$updatewood,
				$updatefood,
				$updatestone,
				$updategold,
				$updateiron,
				$_SESSION['user_name']);
			$updateall->execute();
			$updateall->close();

			echo "You just collected: Wood $woodnow, Food $foodnow, Stone $stonenow, Gold $goldnow, Iron $ironnow.";

			$woodnow = 0;
	    	$foodnow = 0;
	    	$stonenow = 0;
	    	$goldnow = 0;
	    	$ironnow = 0;
	    	$timespent = 0;
    	}
    }
    else if ($timespent >= 7200 && $timespent < 10800) { // 7200
		$woodnow = $woodchopping * 10;
		$woodnow *= 2;
		$foodnow = $farming * 10;
		$foodnow *= 2;
    	$stonenow = $stonemining * 5;
    	$stonenow *= 2;
    	$goldnow = $goldmining * 5;
    	$goldnow *= 2;
    	$ironnow = $ironmining * 5;
    	$ironnow *= 2;
    	if (isset($_POST['collect'])) { // collect current and update time
    		$already = $mysqli->prepare("SELECT wood, food, stone, gold, iron FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
			$already->execute();
		    $already->bind_result($wood, $food, $stone, $gold, $iron);
		    $already->fetch();
		    $already->close();

		    $updatewood = $woodnow + $wood;
		    $updatefood = $foodnow + $food;
		    $updatestone = $stonenow + $stone;
		    $updategold = $goldnow + $gold;
		    $updateiron = $ironnow + $iron;

    		$updateall = $mysqli->prepare("UPDATE users SET lastcollected = ?,
    			wood = ?,
    			food = ?,
    			stone = ?,
    			gold = ?,
    			iron = ?
		   	WHERE user_name = ?");
			$updateall->bind_param('iiiiiis',
				$inserttime,
				$updatewood,
				$updatefood,
				$updatestone,
				$updategold,
				$updateiron,
				$_SESSION['user_name']);
			$updateall->execute();
			$updateall->close();

			echo "You just collected: Wood $woodnow, Food $foodnow, Stone $stonenow, Gold $goldnow, Iron $ironnow.";

			$woodnow = 0;
	    	$foodnow = 0;
	    	$stonenow = 0;
	    	$goldnow = 0;
	    	$ironnow = 0;
	    	$timespent = 0;
    	}
    }
    else if ($timespent >= 10800) { // 10800
		$woodnow = $woodchopping * 10;
		$woodnow *= 3;
		$foodnow = $farming * 10;
		$foodnow *= 3;
    	$stonenow = $stonemining * 5;
    	$stonenow *= 3;
    	$goldnow = $goldmining * 5;
    	$goldnow *= 3;
    	$ironnow = $ironmining * 5;
    	$ironnow *= 3;
    	if (isset($_POST['collect'])) { // collect current and update time
    		$already = $mysqli->prepare("SELECT wood, food, stone, gold, iron FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
			$already->execute();
		    $already->bind_result($wood, $food, $stone, $gold, $iron);
		    $already->fetch();
		    $already->close();

		    $updatewood = $woodnow + $wood;
		    $updatefood = $foodnow + $food;
		    $updatestone = $stonenow + $stone;
		    $updategold = $goldnow + $gold;
		    $updateiron = $ironnow + $iron;

    		$updateall = $mysqli->prepare("UPDATE users SET lastcollected = ?,
    			wood = ?,
    			food = ?,
    			stone = ?,
    			gold = ?,
    			iron = ?
		   	WHERE user_name = ?");
			$updateall->bind_param('iiiiiis',
				$inserttime,
				$updatewood,
				$updatefood,
				$updatestone,
				$updategold,
				$updateiron,
				$_SESSION['user_name']);
			$updateall->execute();
			$updateall->close();

			echo "You just collected: Wood $woodnow, Food $foodnow, Stone $stonenow, Gold $goldnow, Iron $ironnow.";

			$woodnow = 0;
	    	$foodnow = 0;
	    	$stonenow = 0;
	    	$goldnow = 0;
	    	$ironnow = 0;
	    	$timespent = 0;
    	}
    }
    else {
    	$woodnow = 0;
    	$foodnow = 0;
    	$stonenow = 0;
    	$goldnow = 0;
    	$ironnow = 0;
    }


	$mysqli->close();
	?>

	<h1>Citizen Control</h1>
	Assign your citizens to different tasks and collect your resources
	<br>
	Available Citizens: <?php echo "$avible"; ?>
	<center>
	<table  border="0" cellspacing="15">
		<tr style = "text-align: center;"> 
			<td>
				<b>Work</b>
			</td>
			<td>
				<b>Decrement</b>
			</td>
			<td>
				<b>Add</b>
			</td>
			<td>
				<b>Current</b>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Woodchopping: 
			</td>
			<td style = "text-align: center;">
				<form action="?page=citizencontrol" method="post">
					<button name='decrement' type='submit' value='woodchopping'>-</button><br>
				</form>
			</td>
			<td style = "text-align: center;">
				<form action="?page=citizencontrol" method="post">
					<button name='add' type='submit' value='woodchopping'>+</button><br>
				</form>
			</td>
			<td style = "text-align: right;">
				<?php echo "$woodchopping"; ?>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Farming: 
			</td>
			<td style = "text-align: center;">
				<form action="?page=citizencontrol" method="post">
					<button name='decrement' type='submit' value='farming'>-</button><br>
				</form>
			</td>
			<td style = "text-align: center;">
				<form action="?page=citizencontrol" method="post">
					<button name='add' type='submit' value='farming'>+</button><br>
				</form>
			</td>
			<td style = "text-align: right;">
				<?php echo "$farming"; ?>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Stone-Mining: 
			</td>
			<td style = "text-align: center;">
				<form action="?page=citizencontrol" method="post">
					<button name='decrement' type='submit' value='stonemining'>-</button><br>
				</form>
			</td>
			<td style = "text-align: center;">
				<form action="?page=citizencontrol" method="post">
					<button name='add' type='submit' value='stonemining'>+</button><br>
				</form>
			</td>
			<td style = "text-align: right;">
				<?php echo "$stonemining"; ?>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Gold-Mining: 
			</td>
			<td style = "text-align: center;">
				<form action="?page=citizencontrol" method="post">
					<button name='decrement' type='submit' value='goldmining'>-</button><br>
				</form>
			</td>
			<td style = "text-align: center;">
				<form action="?page=citizencontrol" method="post">
					<button name='add' type='submit' value='goldmining'>+</button><br>
				</form>
			</td>
			<td style = "text-align: right;">
				<?php echo "$goldmining"; ?>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Iron-Mining: 
			</td>
			<td style = "text-align: center;">
				<form action="?page=citizencontrol" method="post">
					<button name='decrement' type='submit' value='ironmining'>-</button><br>
				</form>
			</td>
			<td style = "text-align: center;">
				<form action="?page=citizencontrol" method="post">
					<button name='add' type='submit' value='ironmining'>+</button><br>
				</form>
			</td>
			<td style = "text-align: right;">
				<?php echo "$ironmining"; ?>
			</td>
		</tr>
	</table>
	</center>
	Hourly rate for Woodchopping / Farming is 10/citizen per hour
	<br>
	Hourly rate for Mining is 5/citizen per hour
	<br><br>

	<span id="countdown" class="timer"></span>
	<br>
	<span id="currentcollect" class="timer"></span>
	<script>
		var wood1 = <?php echo "$woodchopping"; ?>;
    	var food1 = <?php echo "$farming"; ?>;
    	var stone1 = <?php echo "$stonemining"; ?>;
    	var gold1 = <?php echo "$goldmining"; ?>;
    	var iron1 = <?php echo "$ironmining"; ?>;

		var seconds = <?php echo "$timespent"; ?>;
		var hours = Math.round((seconds-1800)/3600);
		var secondsleft = seconds % 3600;
		var remainingMinutes = Math.round((secondsleft-30)/60);
		var remainingSeconds = secondsleft % 60;
		if (remainingSeconds < 10) {
	        remainingSeconds = "0" + remainingSeconds; 
	    }
	    if (remainingMinutes < 10) {
	        remainingMinutes = "0" + remainingMinutes; 
	    }
	    if (hours < 10) {
	    	hours = "0" + hours;
	    }
		document.getElementById('countdown').innerHTML = "Time since last collect: " + hours + ":" + remainingMinutes + ":" + remainingSeconds;
		// move down to "else" with startInterval countdown timer?
		if (seconds >= 10800) { // 3 hours
        	var wood2 = (wood1 * 10) * 3;
        	var food2 = (food1 * 10) * 3;
        	var stone2 = (stone1 * 5) * 3;
        	var gold2 = (gold1 * 5) * 3;
        	var iron2 = (iron1 * 5) * 3;
        	document.getElementById('currentcollect').innerHTML = "Current amount to collect: Wood " + wood2 + ", Food " + food2 + ", Stone " + stone2 + ", Gold " + gold2 + ", Iron " + iron2; 
    	}
    	else if (seconds >= 7200) { // 2 hours
        	var wood2 = (wood1 * 10) * 2;
        	var food2 = (food1 * 10) * 2;
        	var stone2 = (stone1 * 5) * 2;
        	var gold2 = (gold1 * 5) * 2;
        	var iron2 = (iron1 * 5) * 2;
        	document.getElementById('currentcollect').innerHTML = "Current amount to collect: Wood " + wood2 + ", Food " + food2 + ", Stone " + stone2 + ", Gold " + gold2 + ", Iron " + iron2; 
    	}
    	else if (seconds >= 3600) { // 1 hour
        	var wood2 = wood1 * 10;
        	var food2 = food1 * 10;
        	var stone2 = stone1 * 5;
        	var gold2 = gold1 * 5;
        	var iron2 = iron1 * 5;
        	document.getElementById('currentcollect').innerHTML = "Current amount to collect: Wood " + wood2 + ", Food " + food2 + ", Stone " + stone2 + ", Gold " + gold2 + ", Iron " + iron2; 
    	}
    	else {
    		document.getElementById('currentcollect').innerHTML = "Current amount to collect: Wood 0, Food 0, Stone 0, Gold 0, Iron 0"; 
    	}


		function secondPassed() {
	    	if (seconds >= 86400) { // 86400 - 1 day
	        	clearInterval(countdownTimer);
	        	document.getElementById('countdown').innerHTML = "Time since last collect: More then 1 day.";
	        }
	    	else if (seconds >= 10800) { // 3 hours
	        	var wood2 = (wood1 * 10) * 3;
	        	var food2 = (food1 * 10) * 3;
	        	var stone2 = (stone1 * 5) * 3;
	        	var gold2 = (gold1 * 5) * 3;
	        	var iron2 = (iron1 * 5) * 3;
	        	document.getElementById('currentcollect').innerHTML = "Current amount to collect: Wood " + wood2 + ", Food " + food2 + ", Stone " + stone2 + ", Gold " + gold2 + ", Iron " + iron2; 
	    		seconds++;
	    	}
	    	else if (seconds >= 7200) { // 2 hours
	        	var wood2 = (wood1 * 10) * 2;
	        	var food2 = (food1 * 10) * 2;
	        	var stone2 = (stone1 * 5) * 2;
	        	var gold2 = (gold1 * 5) * 2;
	        	var iron2 = (iron1 * 5) * 2;
	        	document.getElementById('currentcollect').innerHTML = "Current amount to collect: Wood " + wood2 + ", Food " + food2 + ", Stone " + stone2 + ", Gold " + gold2 + ", Iron " + iron2; 
	    		seconds++;
	    	}
	    	else if (seconds >= 3600) { // 1 hour
	        	var wood2 = wood1 * 10;
	        	var food2 = food1 * 10;
	        	var stone2 = stone1 * 5;
	        	var gold2 = gold1 * 5;
	        	var iron2 = iron1 * 5;
	        	document.getElementById('currentcollect').innerHTML = "Current amount to collect: Wood " + wood2 + ", Food " + food2 + ", Stone " + stone2 + ", Gold " + gold2 + ", Iron " + iron2; 
	    		seconds++;
	    	}
	    	else {
	        	seconds++;
	    	}

	    	var hours = Math.round((seconds - 1800)/ 3600);
			var secondsleft = seconds % 3600;
			var remainingMinutes = Math.round((secondsleft - 30)/ 60);
			var remainingSeconds = secondsleft % 60;
			if (remainingSeconds < 10) {
		        remainingSeconds = "0" + remainingSeconds; 
		    }
		    if (remainingMinutes < 10) {
		        remainingMinutes = "0" + remainingMinutes; 
		    }
		    if (hours < 10) {
		    	hours = "0" + hours;
		    }
			document.getElementById('countdown').innerHTML = "Time since last collect: " + hours + ":" + remainingMinutes + ":" + remainingSeconds;
	    	
		}
	if (seconds >= 86400) {
			document.getElementById('countdown').innerHTML = "Time since last collect: More then 1 day.";
		}
	else {
		var countdownTimer = setInterval('secondPassed()', 1000);
	}
	</script>

	<br>
	<form action="?page=citizencontrol" method="post">
		<button name='collect' type='submit' value='all'>Collect All</button><br>
	</form>

	<br>
	Remember to collect every 1, 2 or 3 hours. Otherwise you won't get more resources.

</body>
</html>