<html>
<head>
</head>
<body>
	<h1>Ranking</h1>
	<br>
	How many do you want to display?
	<form action="?page=rank" method="post">
		<button name='nr' type='submit' value='1' style='width: 75px;'>1</button>
		<button name='nr' type='submit' value='2' style='width: 75px;'>2</button>
		<button name='nr' type='submit' value='3' style='width: 75px;'>3</button>
		<button name='nr' type='submit' value='4' style='width: 75px;'>4</button>
	</form>
	<br>
	<center>
		<table cellspacing = "15">
			<tr>
				<td>
					<u>Name</u>
				</td>
				<td>
					<u>Level</u>
				</td>
				<td>
					<u>Title</u>
				</td>
			</tr>
				<?php
				if (isset($_POST['nr'])) {
					$nr = $_POST['nr'];
				}
				else {
					$nr = 10;
				}

				$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				if (mysqli_connect_errno()) {
				    printf("Connect failed: %s\n", mysqli_connect_error());
				    exit();
				}
				/* prepare statement */
				if ($stmt = $mysqli->prepare("SELECT user_name, level, title FROM users ORDER BY level DESC LIMIT $nr")) {
				    $stmt->execute();
				    /* bind variables to prepared statement */
				    $stmt->bind_result($col1, $col2, $col3);
				    /* fetch values */
				    while ($stmt->fetch()) {
				        echo "<tr><td><a href='?page=player&player=$col1'>$col1</a></td><td align='center'>$col2</td><td>$col3</td></tr>";
				    }
				    /* close statement */
				    $stmt->close();
				}
				/* close connection */
				$mysqli->close();
				?>
		</table>
	</center>
</body>
</html>