<?php 
/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: characterDisplay.php
file description:  ---> Show list of created templates and characters
	---> provide a central location for user to access template and character creation

*/

session_start();


//get universal header and footer style & php 
include "basicSetup.php"; 	
//get all basic database functions
include "databaseFunctions.php";

/* check if the user logged into the website
	--->if the user has not logged in, redirect to login page
	---> if the user logged in, show their list of created templates and characters
*/

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
		if ($guestID === ""){
			$isGuest = false;
		}
		else{
			$isGuest = true;
		}
		
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
		/*
			handle redirecting to viewing page if template/character name is clicked
		*/
		if (isset( $_POST["viewTemplate"]) &&  !empty( $_POST["viewTemplate"]) && isset( $_POST["viewingUser"]) &&  !empty( $_POST["viewingUser"])){
			$_SESSION["displayUser"] = $_POST["viewingUser"];
			$_SESSION["temId"] = $_POST["viewTemplate"];
			$_SESSION["charId"] = "";
			
			echo "Opening Template: ".$_SESSION["temId"];
			echo "  viewing user: ".$_SESSION["displayUser"];
			$link = "<script>window.open('characterViewing.php')</script>";
			echo $link;
		}
		else if (isset( $_POST["viewChar"]) &&  !empty( $_POST["viewChar"]) && isset( $_POST["viewingUser"]) &&  !empty( $_POST["viewingUser"])){
			
			$_SESSION["displayUser"] = $_POST["viewingUser"];
			$_SESSION["charId"] = $_POST["viewChar"];
			$_SESSION["temId"] = "";
			
			echo "Opening Character: ".$_POST["viewChar"];
			$link = "<script>window.open('characterViewing.php')</script>";
			echo $link;
		}
		else{
			$_SESSION["charId"] ="";
			$_SESSION["temId"] ="";
		}
		/*
			get list of templates and characters from the database based on user's account info
				---> user - user name and pass
				---> guest - guest id
		*/
		$templateList="";
		$charList="";
		if ( $isGuest === true) {
			$identity = $_SESSION['guestID'];
		}
		else{
			$identity = $userID;
		}
	}
}else{
	//not a valid user
	header("Location: logout.php");
	exit;

}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="css/characterDisplay.css" type="text/css" />
	<!-- add javascript page -->
	<!-- <script type = "text/javascript" src = "js/characterDisplayJS.js"> </script> -->

	<title>Genesis NPC Generator</title>
</head>

<body>
<!-- provide a universal header to display user and title at top of page -->
<?php createHeader("Character Display", $user, $guestID); ?>
	<div class="container">
	<section id="centerSide"  >
		<h3>Instructions:</h3>
			<p>Please create a Character Template first. Then you can Create a Character based off of the values given in the template. </p>
			<p>If there are templates and/or characters created, Expand the 'Quick Templates' and click on the template name to view the template. </p>
			<br><br>
			<p>To provide the best User Experience, please enable the use of pop-ups on this website.</p><br>
			<!-- Provide buttons to: 
				1) create templates 
				2) create characters
				3) view other users templatescharacters
				
				NOTE: Order of buttons should be in popularity of use.
			-->
			<div> 	
				<button id="ViewCharacter" class="menuButtons" onclick="window.location='viewEverything.php'">View a Created Character or Template</button><br>
				<button id="CreateTemplate" class="menuButtons" onclick="window.location='charTemplateCreate.php'">Create a Character Template</button><br>
				<button id="CreateCharacter" class="menuButtons" onclick="window.location='characterGen.php'">Create Character</button>

			</div>
			<br><br>
			<!-- provide an accordion-style display to prevent information overload of users
				* main accordions display templates and characters (2 separate accordions)
				* within main accordions, there should be another accordion to allow users to view all templates ( 1 accordion per main)
			-->
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
	</div>
</body>
<!-- provide a universal footer to display user and title at bottom of page -->
	<?php createFooter(); ?>
</html>





