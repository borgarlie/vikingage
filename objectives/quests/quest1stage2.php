<?php
$showresources = $mysqli->prepare("SELECT level, exp, food, warrior1, warrior1level, warrior2, warrior2level, lastquest FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
$showresources->execute();
$showresources->bind_result($level, $exp, $food, $warrior1, $warrior1level, $warrior2, $warrior2level, $lastquest);
$showresources->fetch();
$showresources->close();

$damage = $warrior1 * ( $warrior1level + 5) + $warrior2 * ($warrior2level + 4);

// if (isset($_POST['option']) && time() < $lastquest + 600) {
if (!isset($_SESSION['motherbearkill'])) {
	// remove if or something else missing?

	$_SESSION['motherbearkill'] = 0;

	$motherbeardamage = 12;

	$chance = rand(1,100);
	if ($chance < 10) {
		$damage = $damage - 10;
	}
	else if ($chance >= 10 && $chance < 20) {
		$damage = $damage - 3;
	}
	else if ($chance >= 20 && $chance < 30) {
		$damage = $damage - 1;
	}
	// ...
	if ($damage > $motherbeardamage + 5) {
		$_SESSION['motherbearkill'] = 3;
	}
	else if ($damage > $motherbeardamage + 2 && $damage <= $motherbeardamage + 5) {
		$_SESSION['motherbearkill'] = 2;
	}
	else if ($damage >= $motherbeardamage && $damage <= $motherbeardamage + 2) {
		$_SESSION['motherbearkill'] = 1;
	}
	else if ($warrior1 == 0 && $warrior2 == 0) {
		$_SESSION['motherbearkill'] = -1;
	}
	else {
		$_SESSION['motherbearkill'] = 0;
	}

	echo "
	<br><br>Continuing inside the cave you find another bear.
	<br>Apparently the first one was just the baby-bear.
	<br>Do you wish to fight?
	<form action='?page=quest1' method='post'>
		<button name='optionmotherbear' type='submit' value='1'>Yes</button>
		<button name='optionmotherbear' type='submit' value='0'>No</button>
	</form>
	";
}
else {
	echo "
	<br><br>Continuing inside the cave you find another bear.
	<br>Apparently the first one was just the baby-bear.
	<br>Do you wish to fight?
	<form action='?page=quest1' method='post'>
		<button name='optionmotherbear' type='submit' value='1'>Yes</button>
		<button name='optionmotherbear' type='submit' value='0'>No</button>
	</form>
	";
}
?>