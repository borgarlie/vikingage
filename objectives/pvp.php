<html>
<head>
</head>
<body>
	<h1>PvP</h1>

	<?php
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$resourceshopping = $mysqli->prepare("SELECT level, shield FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
	$resourceshopping->execute();
    $resourceshopping->bind_result($level, $shield);
    $resourceshopping->fetch();
    $resourceshopping->close();

    $pvp = true;

    if ($shield > time()) {
    	if ($level < 5) {
    		$pvp = false;
    		echo "You need to be level 5 or above to attend PvP activity.<br>";
    	}
    	else {
    		echo "You are currently shielded.<br>And can therefore not attend in any PvP activity.<br>Your cooldown is: ";
		    $pvp = false;
		    $timeleft = $shield - time();
		    ?>
		    <span id="countdown" class="timer"></span>
			<script>
				var seconds = <?php echo "$timeleft"; ?>;
				var totalseconds = seconds;
				var days = Math.round((seconds - 43200)/86400);
				seconds = seconds % 86400;
				var hours = Math.round((seconds - 1800)/3600);
				seconds = seconds % 3600;
				var minutes = Math.round((seconds - 30)/60);
				seconds = seconds % 60;
				document.getElementById('countdown').innerHTML = days + " days " + hours + " hours " + minutes + " minutes " + seconds + " seconds.";
				function secondPassed() {
			    	if (totalseconds == 0) {
			        	clearInterval(countdownTimer);
			        	document.getElementById('countdown').innerHTML = "Refresh the page to continue";
			    	} else {
			        	totalseconds--;
			        	var days = Math.round((seconds - 43200)/86400);
						seconds = totalseconds % 86400;
						var hours = Math.round((seconds - 1800)/3600);
						seconds = seconds % 3600;
						var minutes = Math.round((seconds - 30)/60);
						seconds = seconds % 60;
				    	document.getElementById('countdown').innerHTML = days + " days " + hours + " hours " + minutes + " minutes " + seconds + " seconds.";
			    	}
				}
				var countdownTimer = setInterval('secondPassed()', 1000);
			</script>
			<?php
    	}
	}

	$mysqli->close();

	if ($pvp) {
		?>
		<!-- HTML code start here! -->

		You are not shielded and can attend in PvP activity!




		<!-- HTML code END here! -->
	<?php
	}
	?>

</body>
</html>