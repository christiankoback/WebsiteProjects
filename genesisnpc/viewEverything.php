<?php
/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: viewEverything.php
file description:  ---> show all info on current user if there is
		  ---> allow user to view other's characters
*/

session_start();


//get universal header and footer style & php 
include "basicSetup.php"; 	
//get all basic database functions
include "databaseFunctions.php";
$isGuest = false;

if ( ! isset($_SESSION["loginPage"] ) ){	//new session
	$userID = "";
	$user = "";
	$pass = "";
	$guestID = "";
}
else{	//already created session
	if (isset( $_SESSION["userID"]) &&  !empty( $_SESSION["userID"]) ){
		$userID= $_SESSION["userID"];
	}
	else{
		$userID=  "";
	}
	$userID = $_SESSION["userID"] ;
	if (isset( $_SESSION["user"]) &&  !empty( $_SESSION["user"]) ){
		$user = $_SESSION["user"];
	}
	else{
		$user =  "";
	}
	if (isset( $_SESSION["pass"]) &&  !empty( $_SESSION["pass"]) ){
		$pass = $_SESSION["pass"];
	}
	else{
		$pass =  "";
	}
	if (isset( $_SESSION["guestID"]) &&  !empty( $_SESSION["guestID"]) ){
		$guestID= $_SESSION["guestID"];
		$isGuest = true;
	}
	else{
		$guestID=  "";
		$isGuest = false;
	}
}

$isGuest = false;
$defaultDisplayedChar = "";
$templateList = "";
$charList = "";

if($userID != ""){
	//query if the person is a valid person
	$validateUser = isPersonInDBase($user, $pass);
	if( ($validateUser[0] == $userID) || ($userID == 1) ){
		if ( $isGuest === true) {
			$identity = $_SESSION['guestID'];
		}
		else{
			$identity = $userID;
		}
		
		if (isset($_POST["ViewingSubmit"] ) ){
			if (isset( $_POST["usName"] ) && !empty( $_POST["usName"]) && isset( $_POST["temChar"] ) && !empty( $_POST["temChar"])){
				/*
				$templateType = isDataCreated($_POST["usName"], $_POST["temChar"]);
				$templateExists = false;
				if ($templateType == "c"){
					$_SESSION['charId'] = $_POST["temChar"];
					$templateExists = true;
				}else if ($templateType == "t"){
					$_SESSION['temId'] = $_POST["temChar"];
					$templateExists = true;
				}else{}
				
				if ($templateExists === true){
					$_SESSION["displayUser"] = $_POST["usName"];
					$link = "<script>window.open('characterViewing.php')</script>";
					echo $link;
					
				}
				else{
					unset($_SESSION['temId']);
					unset($_SESSION['charId']);
					unset($_SESSION["displayUser"]);
				}*/
				echo "not implemented";
			}else if (isset( $_POST["temCharID"] ) && !empty( $_POST["temCharID"]) ){
			/*
				//first part of ID is user, 2nd part is template/character
				$temCharID = $_POST["temCharID"];
				$delimiter = ".";
				$parts = explode($delimiter, $temCharID);
				
				$temChar = $parts[0];
				$user = $parts[1];
				
				$userID = getID_UNameTemplate($userID,$template);
				if ($userID !== false){
					$templateType = isDataCreated($userID, $temChar);
					$templateExists = false;
					if ($templateType == "c"){
						$_SESSION['charId'] = $_POST["temChar"];
						$templateExists = true;
					}else if ($templateType == "t"){
						$_SESSION['temId'] = $_POST["temChar"];
						$templateExists = true;
					}else{}
					
					if ($templateExists === true){
						$_SESSION["displayUser"] = $userID;
						$link = "<script>window.open('characterViewing.php')</script>";
						echo $link;
					}
					else{
						unset($_SESSION['temId']);
						unset($_SESSION['charId']);
						unset($_SESSION["displayUser"]);
					}
				}*/ 
				echo "not implemented";
			}else if (isset( $_POST["persTemChar"] ) && !empty( $_POST["persTemChar"]) ){
				$tempUser = $_SESSION["userID"];
				$temChar = $_POST["persTemChar"];
				$templateExists = false;
				
				$templateType = isDataCreated($tempUser, $temChar);
				
				if ($templateType == "c"){
					$_SESSION['charId'] = $temChar ;
					$templateExists = true;
				}else if ($templateType == "t"){
					$_SESSION['temId'] = $temChar ;
					echo "exists";
					$templateExists = true;
				}else{}
				
				if ($templateExists === true){
					$_SESSION["displayUser"] = $tempUser ;
					
					$link = "<script>window.open('characterViewing.php')</script>";
					echo $link;
				}
				else{
					unset($_SESSION['temId']);
					unset($_SESSION['charId']);
					unset($_SESSION["displayUser"]);
				}
			}
			else{}
		}	
	}
}	
	

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="css/characterDisplay.css" type="text/css" />
	<!-- add javascript page -->
	<script type = "text/javascript" src = "js/viewEveVerify.js"> </script>

	<title>Genesis NPC Generator</title>
</head>

<body>
<!-- provide a universal header to display user and title at top of page -->
<?php createHeader("View Characters & Templates", $user, $guestID); ?>
	<div class="container">
	<section id="leftSide">
		<button id="mainPage" class="menuButtons" onclick="window.location='characterDisplay.php'">Back to Main</button><br>
		<!-- show list of user templates so user knows what templates they can choose from-->
		<div id="templateAccordSect">
			<?php 
				displayTemplatesAccordion($identity, $isGuest);
			?>
			</div>
			<div id="characterAccordSect">
			<?php 
				displayCharacterAccordion($identity, $isGuest);
			?>
			</div>
	</section>
	
	
	<section id="centerSide"  >
		<form action="viewEverything.php" method="post" >
			<div class="data">Retrieve Template/Character by: <select name="temCharOptions" class="correctTextbox" id ="temCharOptions" onload="populateDataForOptions(this)" onchange="populateDataForOptions(this)">
			  <option value='userTemCharName'>User Name & Template/Character Name</option>
			  <option value='idName'>ID Name</option>
			  <option value='personalTemChar'>Personal Template/Character</option>
			</select>
			</div>
			<section id ="viewingData" name="viewingData">
			</section>
			<section id="errorsViewing" name="errorsViewing">
			</section>
	
			<input type="submit" id="ViewingSubmit" name="ViewingSubmit" value="View Template or Character">
		</form>
	</section>
	</div>
</body>
<!-- provide a universal footer to display user and title at bottom of page -->
	<?php createFooter(); ?>
</html>