<?php
	session_start();

	// Set player "Not Lost" by default
	if(!isset($_SESSION['playerLost']))
		$_SESSION['playerLost'] = FALSE;

	// Check if bet is less than or equal to total balance, else its invalid bet
	$validBet = TRUE;

	// If its a valid bet and player has still not lost the game
	if (isset($_POST['bet']) && $_POST['bet'] > $_SESSION['count'] && $_SESSION['playerLost'] == FALSE){
		$status = 'i';
		$validBet = FALSE;
	}

	// Set default balance for player to 100
	if (!isset($_SESSION['count']))
		$_SESSION['count'] = 100;

	// If its valid bet, and player has not lost the game, generate a random number and match with player's input
	if (isset($_POST['submitBtn']) && $validBet == TRUE) {
		if($_SESSION['playerLost'] == FALSE){
			unset($_SESSION['num']);
			$_SESSION['num'] = rand(0, 1);
	
			if ($_SESSION['num'] == $_POST['guess']) {
				$_SESSION['count'] += $_POST['bet'];
				$status = 'w';
			}
			else {
				$_SESSION['count'] -= $_POST['bet'];
				$status = 'l';
			}
	
			if($_SESSION['count'] == 0) {
				$status = 'n';
				$_SESSION['playerLost'] = TRUE;
			}
		}
		else
			$status = 'No more Credits left. You Lost.';
	}
	// Reset button action
	elseif (isset($_POST['destroy']) && $_POST['destroy'] == 'Reset') {
		session_unset();
		unset($status);
		$_SESSION['count'] = 100;
	}
	
?>
 

<!DOCTYPE html> 
<html>
    <head>
        <title>Slot Machine</title>
    </head>
<body style="background-image: url('styles/body_bg.png')">

	<style>
		@font-face{
			font-family: Bitter;
			src: url('styles/Bitter.otf');
		}
		
		@font-face{
			font-family: Ubuntu;
			src: url('styles/Ubuntu.ttf');
		}
		
		@font-face{
			font-family: Zwodrei;
			src: url('styles/Zwodrei.ttf');
		}
	</style>
	
	<div style="margin: auto; text-align: center; background-color: white; width: 40%; padding: 1px 0px 20px 0px; border-radius: 15px; box-shadow: rgba(0,0,0,0.6) 0px 2px 3px, inset rgba(0,0,0,0.6) 0px -1px 2px; font-family: Ubuntu; overflow: hidden">

		<h1 style="margin-top: -1px; background-color: rgba(157, 188, 94, 0.4); width:99.5%; margin-bottom: 0; border: 1px solid black; border-top-left-radius: 15px; border-top-right-radius: 15px; font-family: Bitter">Slot Machine</h1>
		
		<div style="background-color: rgba(0,0,0, 0.1);">
			<div style="text-align: center; background-color: rgba(0,0,0, 0.1); width: 100%; padding-top: 5px; padding-bottom: 5px">Choose a number between <em>0</em> or <em>1</em>.</div><br>
			
			<form method="post" action="index.php" style="padding-top: 0px; padding-bottom: 15px; width: 55%; margin:auto; text-align: left;">
				<div style="font-family: 'Zwodrei'; display: inline-block; clear: both;">Make your guess here: </div>
				<div style="position: relative; float: right;">
					<input type="number" name="guess"  style="width: 60px; font-family: Zwodrei; font-size :14px" min="0" max="1" style="width: 60px" autofocus required/>
				</div>
				
				<!-- Just to create some space between two text fields-->
				<hr style="height:2px; visibility:hidden;" />
				
				<div style="display: inline-block; clear: both;">Enter your bet: </div>
				<div style="position: relative; float: right;">
					<input type="number" name="bet" style="width: 60px; font-family: Zwodrei; font-size :14px" required>
				</div><br><br>

				<div style="text-align: center; margin-top: 5px">
					<input type="submit" value="Submit" name="submitBtn"  style="padding:5px 10px 5px 10px; background-color: #286090; border-color: #204d74; color: white; border-radius: 5px" />
					<input type="reset" value="Clear"  style="padding:5px 10px 5px 10px; background-color: #ec971f; border-color: #d58512; color: white; border-radius: 5px" />
					<input type="submit" value="Reset" name="destroy"  style="padding:5px 10px 5px 10px; background-color: #d9534f; border-color: #d43f3a; color: white; border-radius: 5px" />
				</div>
			</form>
			
			<?php
				// Generate game output-section
				if(isset($status)){
					$output = '<div style="text-align: center; background-color: rgba(0,0,0, 0.1); width: 100%; padding-top: 5px; padding-bottom: 5px">';
						if($status == 'w')
							$output .= 'You Won';
						elseif($status == 'l')
							$output .= 'You Lost';
						elseif($status == 'i')
							$output .= 'Bet cannot be greater than Credits available.';
						else
							$output .= 'You Lost. No more Credits left. Reset Game to play again.';
			
					$output .= '</div>';
					echo $output;
				}
			?>
		</div>
	
		<div style="background-color: rgba(178, 198, 228, 0.31); margin-bottom: -20px; border-top: 1px solid black; padding-top: 5px; padding-bottom: 5px">
			<h2 style="margin-top: 0; margin-bottom: 0; font-family: Zwodrei">
				<?php
					$creditsOut = 'Credits Remaining: <span style="color: rgb(';
					if($_SESSION['count'] == 0)
						$creditsOut .= '157, 20, 0';
					else
						$creditsOut .= '1, 46, 2';
					echo $creditsOut . ')">' . $_SESSION['count'] . '</span>';
				?>
			</h2>
		</div>
		
	</div>
</body>
</html>