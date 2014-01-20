<html>
<head>
	<title>test</title>
</head>
<body>
	<h1>Choose a Quest</h1>
	<center>
	<?php

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$showresources = $mysqli->prepare("SELECT level FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
	$showresources->execute();
    $showresources->bind_result($level);
    $showresources->fetch();
    $showresources->close();
	$mysqli->close();

	if ($level < 10) {
		?>
		<a href="?page=quest1">Quest 1 - The Cave</a>
		<?php
	}
	else if ($level >= 10 && $level < 20) {
		?>
		<a href="?page=quest1">Quest 1 - The Cave</a>
		<br><br><a href="?page=quest2">Quest 2 - The Dark Forest</a>
		<?php
	}
	else if ($level >= 20) {
		?>
		<a href="?page=quest1">Quest 1 - The Cave</a>
		<br><br><a href="?page=quest2">Quest 2 - The Dark Forest</a>
		<br><br><a href="?page=quest3">Quest 3</a>
		<?php
	}

	?>
	<br><br>
	When questing it is important to not leave the page or redirect somewhere else.
	<br>
	If you do, the cooldown of the quest might already be triggered.
	</center>
</body>
</html>