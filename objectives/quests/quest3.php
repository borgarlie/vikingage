<html>
<head>
	<title>test</title>
</head>
<body>
	<h1>Quest 3</h1>
	<?php

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$showresources = $mysqli->prepare("SELECT level FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
	$showresources->execute();
    $showresources->bind_result($level);
    $showresources->fetch();
    $showresources->close();
	$mysqli->close();

	if ($level < 20) {
		echo "You need to be level 20 to be qualified for this quest.";
	}
	else {
		// quest inc.
		echo "Questing!";
	}

	?>
</body>
</html>