<?php
/**
*	Class for Quest 2 - Viking Age
*/
class quest2class
{
	private $stage = 0;
	private $stageWin = false;
	private $damage = 0;
	private $warrior1 = 0;
	private $warrior2 = 0;
	private $warrior1level = 0;
	private $warrior2level = 0;
	private $food = 0;
	private $exp = 0;
	
	function __construct()
	{
		$this->startQuest();
	}

	private function startQuest() {

		$this->getInfo();

		if (!empty($_SESSION['stage'])) {
            $this->doStage();
        }
		else if ($this->level < 10) {
			$this->tooLowLevel();
		}
		else if (time() <= $this->lastquest + 79200 && empty($_SESSION['stage'])) {
			$this->cooldown();
		}
		else {
			$_SESSION['stage'] = 1;
			$this->stage = 1;
			$this->doStage();
		}
	}

	private function tooLowLevel() {
		echo "<br><br>You need to be level 10 to be qualified for this quest.";
	}

	private function cooldown() {
		echo "<br><br>You need to wait 22 hours between quests!";
	}

	private function calcDamage() {
		$dmg = $this->warrior1 * ( $this->warrior1level + 5) + $this->warrior2 * ($this->warrior2level + 4);
		return $dmg;
	}

	private function getInfo() {
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$getinfo = $this->db_connection->prepare("SELECT level, exp, food, warrior1, warrior1level, warrior2, warrior2level, lastquest FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
		$getinfo->execute();
		$getinfo->bind_result($this->level, $this->exp, $this->food, $this->warrior1, $this->warrior1level, $this->warrior2, $this->warrior2level, $this->lastquest);
		$getinfo->fetch();
		$getinfo->close();
		$this->db_connection->close();
		if (!empty($_SESSION['stage'])) {
			$this->stage = $_SESSION['stage']; // get stage
		}
		$this->damage = $this->calcDamage(); // calculate and set damage
	}

	private function doStage() {
		if ($this->stage == 0) {
			echo "<br><br><u>Something wrong with stages.</u><br>"; // print error message if stage = 0 here.
			$this->getInfo();
			$this->doStage();
		}
		else if ($this->stage == 1) {
			$this->stage1();
		}
		else if ($this->stage == 2) {
			$this->stage2();
		}
	}

	private function nextStage() {
		$this->stageWin = false;
		$_SESSION['stage'] += 1;
		$this->stage = $_SESSION['stage'];
		$this->doStage();
	}

	private function endQuest() {
		$this->stage = 0;
		unset($_SESSION['stage']);
	}

	private function updatePlayer() {
		$updatetime = time();
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$updateitems = $this->db_connection->prepare("UPDATE users SET exp = ?, food = ?, warrior1 = ?, warrior2 = ?, lastquest = ? WHERE user_name = ?");
		$updateitems->bind_param('iiiiis', $this->exp, $this->food, $this->warrior1, $this->warrior2, $updatetime, $_SESSION['user_name']);
		$updateitems->execute();
		$updateitems->close();
		$this->db_connection->close();
	}

	private function stage1() {
		if (isset($_POST['optionstage1'])) {
			if ($_POST['optionstage1'] == 1) {
				$this->calculateWinLoss();
				if ($this->stageWin == true) {
					$this->exp += 200;
					$this->food += 300;
					$this->updatePlayer();
					echo "<br><br>You managed to bring down all the wolves and finished stage 1<br>You received: 200 Exp and 300 Food";
					$this->nextStage(); // start stage 2.
				}
				else {
					$this->exp += 50;
					$this->updatePlayer();
					echo "<br><br>You had no choice, but to flee the battlefield.<br>Your courage awarded you 50 Exp";
					$this->endQuest();
				}
			}
			else {
				echo "<br>You retreated.";
				$this->endQuest();
			}
		}
		else {
			echo "
				<br><br>You entered the dark forest, and inside it you found a group of angry looking wolves.
				<br>Do you wish to engage in battle, or flee the forest?
				<form action='?page=quest2' method='post'>
					<button name='optionstage1' type='submit' value='1'>Engage</button>
					<button name='optionstage1' type='submit' value='0'>Flee</button>
				</form>
			";
		}
	}

	private function stage2() {
		if (isset($_POST['optionstage2'])) {
			if ($_POST['optionstage2'] == 1) {
				$this->calculateWinLoss();
				if ($this->stageWin == true) {
					$this->exp += 300;
					$this->food += 400;
					$this->updatePlayer();
					echo "<br><br>You managed to finish off the creatures and finished stage 2<br>You received: 300 Exp and 400 Food";
					echo "<br><br>Quest complete!";
					$this->endQuest(); // quest complete - end it.
				}
				else {
					$this->exp += 50;
					$this->updatePlayer();
					echo "<br><br>You lost the fight, but gained 50 Exp";
					$this->endQuest();
				}
			}
			else {
				echo "<br>You retreated.";
				$this->endQuest();
			}
		}
		else {
			echo "
				<br><br>Proceeding inside the forest you engage some rare creatures.
				<br>Do you wish to fight, or leave?
				<form action='?page=quest2' method='post'>
					<button name='optionstage2' type='submit' value='1'>Fight</button>
					<button name='optionstage2' type='submit' value='0'>Leave</button>
				</form>
			";
		}
	}

	private function calculateWinLoss() {
		// Set enemy damage for each stage.
		if ($this->stage == 1) {
			$enemydamage = 20;
		}
		else {
			$enemydamage = 30;
		}

		// calculate your damage with a randomness
		$chance = rand(1,100);
		$yourdamage = $this->damage;

		if ($chance < 10) {
			$yourdamage = $yourdamage - 10;
		}
		else if ($chance >= 10 && $chance < 20) {
			$yourdamage = $yourdamage - 3;
		}
		else if ($chance >= 20 && $chance < 30) {
			$yourdamage = $yourdamage - 1;
		}

		// check damage difference
		if ($yourdamage > $enemydamage + 5) {
			$this->stageWin = true;
		}
		else if ($yourdamage > $enemydamage + 2 && $yourdamage <= $enemydamage + 5) {
			$this->stageWin = true;
		}
		else if ($yourdamage >= $enemydamage && $yourdamage <= $enemydamage + 2) {
			$loosechance = rand(1,100);
			if($loosechance < 15) { // 15% chance to loose a soldier
				if ($this->warrior1 >= $this->warrior2) {
					$this->warrior1 -= 1;
				}
				else {
					$this->warrior2 -=1;
				}
				echo "<br><br>R.I.P<br>One of your soldiers died in battle.";
			}
			$this->stageWin = true;
		}
		else if ($this->warrior1 == 0 && $this->warrior2 == 0) {
			$this->stageWin = false;
			echo "<br><br>You cannot fight without any soldiers";
		}
		else {
			$loosechance = rand(1,100);
			if($loosechance < 25) { // 25% chance to loose a soldier
				if ($this->warrior1 >= $this->warrior2) {
					$this->warrior1 -= 1;
				}
				else {
					$this->warrior2 -=1;
				}
				echo "<br><br>R.I.P<br>One of your soldiers died in battle.";
			}
			$this->stageWin = false;
		}
	}

} // end of class
?>