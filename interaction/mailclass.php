<?php
/**
*	Class for Mails - Viking Age
*/
class mailclass
{
	private $option = "receiver";
	private $secondOption = "sender";
	private $currentMail = "";

	private $newReceiver = "";
	private $newTopic = "";
	private $newText = "";

	private $mailid = array();
	private $timesent = array();
	private $player2 = array();
	private $head = array();
	private $text = array();
	private $isRead = array();
	private $senderdel = array();
	private $receiverdel = array();

	function __construct()
	{
		if (isset($_POST['option'])) {
			$this->option = $_POST['option'];
			if ($this->option == "receiver") { // inbox
				$this->secondOption = "sender";
				$this->updateMail();
				$this->viewMail();
			}
			else if ($this->option == "sender") { // outbox (sent)
				$this->secondOption = "receiver";
				$this->updateMail();
				$this->viewSent();
			}
			else if ($this->option == 3) { // send a new mail
				$this->sendMail();
			}
			else {
				$this->option = "receiver";
				$this->secondOption = "sender";
				$this->updateMail();
				$this->viewMail();
			}
        }
        else if (isset($_POST['mail'])) {
        	$this->currentMail = $_POST['mail'];
        	$this->openMail();
        }
        else if (isset($_POST['sendTo'])) {
        	$this->sendMail();
        }
        else if (isset($_POST['send'])) {
        	$this->insertMail();
        }
        else if (isset($_POST['delete'])) {
        	$this->currentMail = $_POST['id'];
        	$this->areYouSure();
        }
        else if (isset($_POST['isSure'])) {
        	$this->currentMail = $_POST['id'];
        	$this->deleteMail();
        }
        else {
        	$this->updateMail();
			$this->viewMail();
        }
	}

	private function updateMail() {
		// fetch all new mails to this person
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}
		$stmt = $mysqli->prepare("SELECT mailid, timesent, $this->secondOption, head, maintext, isread, senderdel, receiverdel FROM mails WHERE $this->option = '" . $_SESSION['user_name'] . "'ORDER BY timesent DESC");
	    $stmt->execute();
	    $stmt->bind_result($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8);
	    while ($stmt->fetch()) {
	    	$this->mailid[] = $col1;
	    	$this->timesent[] = $col2;
	    	$this->player2[] = $col3;
	    	$this->head[] = $col4;
	    	$this->maintext[] = $col5;
	    	$this->isRead[] = $col6;
	    	$this->senderdel[] = $col7;
	    	$this->receiverdel[] = $col8;
	    }
	    $stmt->close();
		$mysqli->close();
	}

	private function viewMail() {
		// show mails (after updateMail), and display ordered by date.
		echo "<table cellspacing='10' id='maxwidth'>";
		echo "<tr><td id='timesent'>Time Sent</td><td id='sender'>Sender</td><td id='topic'>Topic</td></tr>";
		for ($i=0; $i < sizeof($this->timesent); $i++) {
			if ($this->receiverdel[$i] == 0) {
				echo "<tr><td colspan='3'>";
				echo "<form action='?page=mail' method='post'>";
				echo "<button name='mail' type='submit' id='r{$this->isRead[$i]}' value='{$this->mailid[$i]}'>";
				echo "<table id='maxwidth'><tr><td id='timesent'>";
				print_r($this->timesent[$i]);
				echo "</td><td id='sender'>";
				print_r($this->player2[$i]);
				echo "</td><td id='topic'>";
				print_r($this->head[$i]);
				echo "<form action='?page=mail' method='post'><input type='submit' style='float: right' name='delete' value='Delete' /><input type='hidden' name='id' value='{$this->mailid[$i]}' /></form>";
				echo "</td>";
				echo "</tr></table>";
				echo "</button></form>";
				echo "</td></tr>";
			}
		}
		echo "</table>";
	}

	private function viewSent() {
		// show mails that are sent from the player, order by date.
		echo "<table cellspacing='10' id='maxwidth'>";
		echo "<tr><td id='timesent'>Time Sent</td><td id='sender'>Receiver</td><td id='topic'>Topic</td></tr>";
		for ($i=0; $i < sizeof($this->timesent); $i++) {
			if ($this->senderdel[$i] == 0) {
				echo "<tr><td colspan='3'>";
				echo "<form action='?page=mail' method='post'>";
				echo "<button name='mail' type='submit' id='r{$this->isRead[$i]}' value='{$this->mailid[$i]}'>";
				echo "<table id='maxwidth'><tr><td id='timesent'>";
				print_r($this->timesent[$i]);
				echo "</td><td id='sender'>";
				print_r($this->player2[$i]);
				echo "</td><td id='topic'>";
				print_r($this->head[$i]);
				// delete button.
				echo "<form action='?page=mail' method='post'><input type='submit' style='float: right' name='delete' value='Delete' /><input type='hidden' name='id' value='{$this->mailid[$i]}' /></form>";
				echo "</td>";
				echo "</tr></table>";
				echo "</button></form>";
				echo "</td></tr>";
			}
		}
		echo "</table>";
	}

	private function openMail() {
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$stmt = $mysqli->prepare("SELECT timesent, sender, receiver, head, maintext, isread FROM mails WHERE mailid = '" . $this->currentMail . "';");
	    $stmt->execute();
	    $stmt->bind_result($col1, $col2, $col3, $col4, $col5, $col6);
		$stmt->fetch();
	    $stmt->close();
	    // check to see if user is allowed to view the mail
		if (strcasecmp($col3, $_SESSION['user_name']) == 0) { // check if user is receiver, binary non case sensitive comparison
			// display and update isread if not read. 
			if ($col6 == 0) {
				$setRead = $mysqli->prepare("UPDATE mails SET isread = 1 WHERE mailid = ?");
				$setRead->bind_param('i', $this->currentMail);
				$setRead->execute(); // i or s ???? int or string..
				$setRead->close();
			}
			echo "<table cellspacing='10' align='left'>";
			echo "<tr><td>Time Sent: $col1</td></tr>";
			echo "<tr><td>Sender: $col2</td></tr>";
			echo "<tr><td>Receiver: $col3</td></tr>";
			echo "<tr><td></td></tr>";
			echo "<tr><td><u>$col4</u></td></tr>";
			echo "<tr><td>$col5</td></tr>";
			echo "</table>";
		}
		else if (strcasecmp($col2, $_SESSION['user_name']) == 0) { // check if user is sender
			echo "<table cellspacing='10' align='left'>";
			echo "<tr><td>Time Sent: $col1</td></tr>";
			echo "<tr><td>Sender: $col2</td></tr>";
			echo "<tr><td>Receiver: $col3</td></tr>";
			echo "<tr><td></td></tr>";
			echo "<tr><td><u>$col4</u></td></tr>";
			echo "<tr><td>$col5</td></tr>";
			echo "</table>";
		}
		else { // display if the user has edited the "id" in the html button tag.
			echo "<br>Trying to hack?<br>";
		}
		$mysqli->close();
	}
	
	private function sendMail() { // make public? or no need cuz of post sendTo?
		// function to send mail from one player to another player.
		echo "<table id='sendmail'><form action='?page=mail' method='post'>";
		echo "<tr><td id='label1'><label for='sreceiver'>Send to:</label></td>";
		if (isset($_POST['sendTo'])) {
			$receiver = $_POST['sendTo'];
			echo "<td id='label2'><input id='sreceiver' type='text' name='receiver' value='$receiver' required /></td></tr>";
		}
		else {
			echo "<td id='label2'><input id='sreceiver' type='text' name='receiver' required /></td></tr>";
		}
		echo "<tr><td id='label1'><label for='stopic'>Topic:</label></td>";
		echo "<td id='label2'><input id='stopic' type='text' name='topic' required /></td></tr>";
		echo "<tr><td colspan='2'><textarea id='mtext' name='mtext'></textarea></td></tr>";
		echo "<tr><td colspan='2'><input type='submit' id='maxwidth' name='send' value='Send' /></td></tr>";
		echo "</form></table>";
	}

	private function insertMail() {
		$this->newReceiver = $_POST['receiver'];
		$this->newTopic = $_POST['topic'];
		$this->newText = $_POST['mtext'];

		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$stmt = $mysqli->prepare("SELECT user_name FROM users WHERE user_name = '" . $this->newReceiver . "';");
	    $stmt->execute();
	    $stmt->bind_result($exists);
		$stmt->fetch();
	    $stmt->close();
	    $mysqli->close();

	    // checks if user exist
	    if (empty($exists)) {
	    	echo "User does not exist.";
	    }
	    else {
	    	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			$insert_new_mail = $mysqli->query("INSERT INTO mails (sender, receiver, head, maintext) VALUES('" . $_SESSION['user_name'] . "', '" . $this->newReceiver . "', '" . $this->newTopic . "', '" . $this->newText . "');");
			$mysqli->close();
			echo "<br>Your mail is sent!";
	    }
	}

	private function areYouSure() {
		// check if user is sure..
		echo "Are you sure you want to delete this mail?";
		echo "<form action='?page=mail' method='post'>";
		echo "<input type='submit' name='isSure' value='Yes' />";
		echo "<input type='submit' name='option' value='No' />";
		echo "<input type='hidden' name='id' value='$this->currentMail' />"; // send the ID with a hidden input, check if allowed on deleteMail.
		echo "</form>";
		// delete mail ID is posted here... post to next "deleteMail" with hidden input
	}

	private function deleteMail() {
		// -> from constructor to here on delete button click. check if user is allowed to delete it, and change the "deleted" value of the player to 1.
		// check if user is allowed to delete it.
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$stmt = $mysqli->prepare("SELECT sender, receiver, senderdel, receiverdel FROM mails WHERE mailid = '" . $this->currentMail . "';");
	    $stmt->execute();
	    $stmt->bind_result($col1, $col2, $col3, $col4);
		$stmt->fetch();
	    $stmt->close();
	    // check to see if user is allowed to view the mail
	    // check if user is receiver, binary non case sensitive comparison
	    // also check if its not already deleted.
		if (strcasecmp($col1, $_SESSION['user_name']) == 0 && $col3 == 0) {
			// update senderdel
			$setDelete = $mysqli->prepare("UPDATE mails SET senderdel = 1 WHERE mailid = ?");
			$setDelete->bind_param('i', $this->currentMail);
			$setDelete->execute(); // int or string?
			$setDelete->close();
			$col3 = 1; // set deleted.
			// if both users has deleted it, delete it from database.
			if ($col3 == 1 && $col4 == 1) {
				$stmt = $mysqli->prepare("DELETE FROM mails WHERE mailid = ?");
				$stmt->bind_param('i', $this->currentMail);
				$stmt->execute(); 
				$stmt->close();
			}
			echo "Mail deleted.";
		}
		else if (strcasecmp($col2, $_SESSION['user_name']) == 0 && $col4 == 0) { // check if user is sender
			$setDelete = $mysqli->prepare("UPDATE mails SET receiverdel = 1 WHERE mailid = ?");
			$setDelete->bind_param('i', $this->currentMail);
			$setDelete->execute(); // int or string?
			$setDelete->close();
			$col4 = 1; // set deleted.
			// if both users has deleted it, delete it from database.
			if ($col3 == 1 && $col4 == 1) {
				$stmt = $mysqli->prepare("DELETE FROM mails WHERE mailid = ?");
				$stmt->bind_param('i', $this->currentMail);
				$stmt->execute(); 
				$stmt->close();
			}
			echo "Mail deleted.";
		}
		else { // display if the user has edited the "id" in the html button tag.
			echo "<br>Trying to hack?<br>";
		}
		$mysqli->close();
	}

} // end of class

// also make the "receiver and sender" clickable to send mails... to test the value function on sendmail... "sendTo"
// make safe from mysql injections. html entities? 

// use a scrollable view for the mails so you dont need to scroll the whole page? 
// same for FAQ?

// receiver and sender click will send to "player" page where you can click on "send mail" to that player.
// will also be a reply button on "received" mails.
// implement after player page.

// You can even see if the one you sent to has opened the mail :D:D:D:D:D

?>