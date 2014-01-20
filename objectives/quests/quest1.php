<html>
<head>
	<title>test</title>
</head>
<body>
	<h1>The Cave</h1>

	<?php

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$showresources = $mysqli->prepare("SELECT level, exp, food, warrior1, warrior1level, warrior2, warrior2level, lastquest FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
	$showresources->execute();
    $showresources->bind_result($level, $exp, $food, $warrior1, $warrior1level, $warrior2, $warrior2level, $lastquest);
    $showresources->fetch();
    $showresources->close();

	$damage = $warrior1 * ( $warrior1level + 5) + $warrior2 * ($warrior2level + 4);
	if (isset($_SESSION['motherbearkill'])) {
		if (isset($_POST['optionmotherbear'])) {
			$optionmotherbear = $_POST['optionmotherbear'];
			if ($optionmotherbear == 1) {
				if ($_SESSION['motherbearkill'] == 3) {
					echo "<br><br>You killed the motherbear with ease and gained 80 exp and 450 food!";
					$newexp = $exp + 80;
					$newfood = $food + 450;

					$updateitems = $mysqli->prepare("UPDATE users SET exp = ?, food = ? WHERE user_name = ?");
					$updateitems->bind_param('iis', $newexp, $newfood, $_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();
					echo "<br><br>Quest completed!";
				}
				else if ($_SESSION['motherbearkill'] == 2) {
					echo "<br><br>You killed the motherbear and gained 80 exp and 400 food!";
					$newexp = $exp + 80;
					$newfood = $food + 400;

					$updateitems = $mysqli->prepare("UPDATE users SET exp = ?, food = ? WHERE user_name = ?");
					$updateitems->bind_param('iis', $newexp, $newfood, $_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();
					echo "<br><br>Quest completed!";
				}
				else if ($_SESSION['motherbearkill'] == 1) {
					$newexp = $exp + 80;
					$newfood = $food + 400;

					$loosechance = rand(1,100);
					$loosetarget = $warrior2;

					echo "<br><br>You killed the motherbear, barely.";

					if ($warrior1 >= $warrior2) {
						$loosetarget = $warrior1;
					}

					if ($loosechance < 10) { // 10% chance to loose a warrior
						$$loosetarget = $$loosetarget - 1;
						echo "<br>Unfortunately you lost a soldier in the fight.";
					}

					$updateitems = $mysqli->prepare("UPDATE users SET exp = ?, food = ?, warrior1 = ?, warrior2 = ? WHERE user_name = ?");
					$updateitems->bind_param('iiiis', $newexp, $newfood, $warrior1, $warrior2, $_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();
					echo "<br><br>Quest completed!<br>You earned 80 exp and 400 food.";
				}
				else if ($_SESSION['motherbearkill'] == -1) {
					echo "<br><br>You need soldiers to fight!<br><br>Quest failed.";
				}
				else {
					echo "You were not strong enough to kill the motherbear.";
					$newexp = $exp + 20;

					$loosechance = rand(1,100);
					$loosetarget = $warrior2;

					if ($warrior1 >= $warrior2) {
						$loosetarget = $warrior1;
					}

					if ($loosechance < 25) { // 25% chance to loose
						$$loosetarget = $$loosetarget - 1;
						echo "<br>Unfortunately the mother bear killed one of your soldiers before you could retreat.";
					}

					$updateitems = $mysqli->prepare("UPDATE users SET exp = ?, warrior1 = ?, warrior2 = ? WHERE user_name = ?");
					$updateitems->bind_param('iiis', $newexp, $warrior1, $warrior2, $_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();

					echo "<br><br>Quest failed, but you earned 20 exp.";
				}
			}
			else {
				echo "You retreated and failed the quest.";
			}
			unset($_SESSION['motherbearkill']); // move down to isset session?
			// unregister?
		}
		else {
			include("quest1stage2.php");
		}
	}
	else if ($damage == 0) {
		echo "<br><br>You need some soldiers to do quests!";
	}
	else if (time() < $lastquest + 79200) {
		echo "You need to wait 22 hours between each quest!";
	}
	else {
		?>

		Your damage: <?php echo "$damage"; ?>
		<br>
		You entered a cave and at first sight you meet a bear.
		<br>Do you wish to engage in a fight?
		<form action="?page=quest1" method="post">
			<button name='option' type='submit' value='1'>Yes</button>
			<button name='option' type='submit' value='0'>No</button>
		</form>

		<?php
		if (isset($_POST['option'])) {

			$option = $_POST['option'];
			$fighting = false;
			
			if($option == "1") {

				$enemydamage = 4;
				$updatetime = time();

				$chance = rand(1,100);
				if ($chance < 10) {
					$damage = $damage - 10;
				}
				else if ($chance >= 10 && $chance < 20) {
					$daamge = $damage - 3;
				}
				else if ($chance >= 20 && $chance < 30) {
					$daamge = $damage - 1;
				}

				if ($damage > $enemydamage + 5) {

					$newexp = $exp + 50;
					$newfood = $food + 350;

					$updateitems = $mysqli->prepare("UPDATE users SET exp = ?, food = ?, lastquest = ? WHERE user_name = ?");
					$updateitems->bind_param('iiis', $newexp, $newfood, $updatetime, $_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();

					echo "<br><br>You killed the bear easily and clean<br> Afterwards you gathered all the meat you possibly could, giving u a total of 350 food.";

					include("quest1stage2.php");

				}
				else if ($damage > $enemydamage + 2 && $damage <= $enemydamage + 5) {

					$newexp = $exp + 50;
					$newfood = $food + 300;

					$updateitems = $mysqli->prepare("UPDATE users SET exp = ?, food = ?, lastquest = ? WHERE user_name = ?");
					$updateitems->bind_param('iiis', $newexp, $newfood, $updatetime, $_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();

					echo "<br><br>You killed the bear with some struggle!<br>You gathered up 300 food from the bear.";

					include("quest1stage2.php");

				}
				else if ($damage >= $enemydamage && $damage <= $enemydamage + 2) {

					$newexp = $exp + 50;
					$newfood = $food + 300;

					$loosechance = rand(1,100);
					$loosetarget = $warrior2;

					echo "<br><br>You barely made it, but you killed the bear.";

					if ($warrior1 >= $warrior2) {
						$loosetarget = $warrior1;
					}

					if ($loosechance < 5) {
						$$loosetarget = $$loosetarget - 1;
						echo "<br>While struggling to kill the bear, it managed to kill one of your soldiers.<br>You managed to get 300 food from the bear.";
					}

					$updateitems = $mysqli->prepare("UPDATE users SET exp = ?, food = ?, warrior1 = ?, warrior2 = ?, lastquest = ? WHERE user_name = ?");
					$updateitems->bind_param('iiiiis', $newexp, $newfood, $warrior1, $warrior2, $updatetime, $_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();

					include("quest1stage2.php");
				}
				else {

					$newexp = $exp + 30;

					echo "<br><br>The bear was stronger then you, and you had to retreat!";

					$loosechance = rand(1,100);
					$loosetarget = $warrior2;

					if ($warrior1 >= $warrior2) {
						$loosetarget = $warrior1;
					}

					if ($loosechance < 20) {
						$$loosetarget = $$loosetarget - 1;
						echo "<br>While retreating the bear managed to kill one of your soldiers.";
					}

					$updateitems = $mysqli->prepare("UPDATE users SET exp = ?, warrior1 = ?, warrior2 = ?, lastquest = ? WHERE user_name = ?");
					$updateitems->bind_param('iiiis', $newexp, $warrior1, $warrior2, $updatetime, $_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();
				}
			}
			else {
				echo "<br><br>You retreated. Perhaps that was a good choice.";
			}
		}
	}
	$mysqli->close();
	?>
</body>
</html>