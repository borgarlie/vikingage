<html>
<head>
	<style type="text/css">
	    #choice {
		    position: relative;
		    top: 0px;
		    left:0px;
		    right: 0px;
		    width: 100%;
		    height: 60px;
		}
		#mailcontent {
			position: relative;
		    top: 0px;
		    left:0px;
		    right: 0px;
		    width: 100%;
		}
		#maxwidth {
			position: relative;
		    width: 100%;
		    height: 35px;
		}

		#r1 { /*already read mails*/
			position: relative;
		    width: 100%;
		    height: 35px;
		    color: black;
		}

		#r0 { /*not read mails*/
			position: relative;
		    width: 100%;
		    height: 35px;
		    color: red;
		}

		#choice button {
			height: 100%;
			width: 30%;
			max-width: 30%;
			max-height: 100%;
			min-height: 100%;
		}
		#choice button:hover {
		  	cursor: pointer;
		}
		#mailcontent button {
			position: relative;
			width: 100%;
			max-width: 100%;
		}
		#mailcontent button:hover {
		  	cursor: pointer;
		}

		#timesent {
			position: relative;
			width: 20%;
			bottom: 5px;
			text-align: left;
		}

		#sender {
			position: relative;
			width: 20%;
			bottom: 5px;
			text-align: left;
		}

		#topic {
			position: relative;
			width: 60%;
			bottom: 5px;
			text-align: left;
		}

		#sendmail {
			position: relative;
		    width: 100%;
		}

		#label1 {
			position: relative;
			width: 20%;
			text-align: left;
		}

		#label2 {
			position: relative;
			width: 80%;
			text-align: left;
		}

		#sreceiver {
			position: relative;
			width: 80%;
			text-align: left;
		}

		#stopic {
			position: relative;
			width: 80%;
			text-align: left;
		}

		#mtext {
			position: relative;
			width: 100%;
			height: 200px;
			text-align: left;
		}

		/* Need to make the CSS a external file... */

 	</style>
</head>
<body>
	<h1>Mail</h1>
	<div id="choice">
		<form action="?page=mail" method="post">
			<button name='option' type='submit' value='receiver'>Inbox</button>
			<button name='option' type='submit' value='sender'>Outbox</button>
			<button name='option' type='submit' value='3'>Send</button>
		</form>
	</div>
	<div id="mailcontent">
		<center>
		<?php
		require_once("mailclass.php");
		$mail = new mailclass();
		?>
		</center>
	</div>
</body>
</html>

<!-- Remove mails automaticly after a given time? -->