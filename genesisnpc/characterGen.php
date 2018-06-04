<?php 
/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: characterGen.php
file description:  main page for generating a character from template(s)

*/
/*
	get header/footer style
*/
include "basicSetup.php";
include "databaseFunctions.php";

/* check if user is logged in, otherwise redirect to login */
session_start();
$validateUser = "";
$isGuest = false;

/* if user is logged in, set all used sessioin variables */
if ( ! isset($_SESSION["loginPage"] ) ){	//new session
	$userID = "";
	$user = "";
	$pass = "";
}
else{	//already created session
	if (isset( $_SESSION["userID"]) &&  !empty( $_SESSION["userID"]) ){
		$userID = $_SESSION["userID"];
	}
	else{
		$userID =  "";
	}
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
	$validateUser = isPersonInDBase($user, $pass);
}

//query if the person is a valid person
if( ($validateUser[0] == $userID) || ($isGuest === true) ){
	
	//set identity variable to either guest ID if guest or to userID for user
	$templateList="";
	$identity = "";
	if ( $isGuest === true) {
		$identity = $_SESSION['guestID'];
		$isGuest = true;
		$templateList = getCharacterTemplateList($identity,true);
	}
	else{
		$identity = $userID;
		$templateList = getCharacterTemplateList($userID,false);
	}
	
	//provide functionality to display templates
	if (isset( $_POST["viewTemplate"]) &&  !empty( $_POST["viewTemplate"]) && isset( $_POST["viewingUser"]) &&  !empty( $_POST["viewingUser"])){
		$_SESSION["displayUser"] = $_POST["viewingUser"];
		$_SESSION["temId"] = $_POST["viewTemplate"];
		
		echo "opening template: ".$_SESSION["temId"];
		$link = "<script>window.open('characterViewing.php')</script>";
		echo $link;
	}
	if (isset( $_POST["viewChar"]) &&  !empty( $_POST["viewChar"]) && isset( $_POST["viewingUser"]) &&  !empty( $_POST["viewingUser"])){
		
		$_SESSION["viewingUser"] = $_POST["viewingUser"];
		$_SESSION["charId"] = $_POST["viewChar"];
		
		echo "opening character: ".$_POST["viewChar"];
		$link = "<script>window.open('characterViewing.php')</script>";
		echo $link;
	}
		
		
	/* if there is a number of characters and a number of valid chosen templates,
		create specific amount of characters 
	*/
	$genCharacters = array();
	if ( isset($_POST['templateNum']) && !empty($_POST['templateNum'])&& isset($_POST['characterNum']) && !empty($_POST['characterNum']) ){
		$numOfTemplates = $_POST['templateNum'];
		$numOfCharacters = $_POST['characterNum'];
		$totalChance = 0;
		$percent = array();
		array_push($percent, $totalChance);
		
		for($i = 0; $i < $numOfTemplates ;$i++){
			// for each template, get the chance and set range 
			$templateNameBase = "temName" .  $i;
			$templateName = $_POST[$templateNameBase];
			
			
			if (  isTemplateCreated($identity, $templateName, $isGuest ) == true ){
				$chance = 0;
				$templateChanceBase = "temChance" . $i ;
				$percentChance = $_POST[$templateChanceBase];
				
				if( intval( $_POST["totalChance"] ) == 1 ){
					$chance = $percentChance * 100;
				}
				else{
					$chance = $percentChance;
				}
				array_push($percent, $totalChance + $chance );
			}
		}
		for($i = 0; $i < $numOfCharacters ;$i++){
			// for each character, generate a random number 
			$tempCharacterID = rand(0, 100) ;
			for ($j = 0; $j < count($percent); $j ++){
				if ( $tempCharacterID > $percent[$j] ){
					$templateNameBase = "temName" .  $j;
					$templateName = $_POST[$templateNameBase];
					$tempCharacter = generateCharacter($identity,$templateName,$isGuest, $i);
					if ($tempCharacter !=false){
						array_push($genCharacters, $tempCharacter );
					}
					break;
				}
			}
		}
	}
	

}else{
	//not a valid user
	header("Location: logout.php");
	exit;

}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="css/basicDesign.css" type="text/css" />
	<!--
	add javascript page
	-->
	<script type = "text/javascript" src = "js/charGenVerify.js">
	</script>

	<title>Genesis NPC Generator</title>
</head>

<body>
<?php createHeader("Character Generator", $user, $guestID); ?>
<!--<section id="pageContent"> -->
	 <h4>This page generates random characters based on what templates are given, and a percent chance of creation with the assocciated template.</h4>
	 
	<section id="leftSide">
	<button id="mainPage" class="menuButtons" onclick="window.location='characterDisplay.php'">Back to Main</button><br>
	<button id="CreateTemplate" class="menuButtons" onclick="window.location='charTemplateCreate.php'">Create a Character Template</button><br>
	<!-- show list of user templates so user knows what templates they can choose from-->
	<?php
		
		displayTemplatesAccordion($identity, $isGuest);
	?>
	</section>
	<section id="centerSide">
	
	 <?php
	 /* show form elements to get number of templates, number of characters to generate, and template names for base  */
	 if( (count($templateList) > 0)&& !empty($templateList)  ) {	//if template exists
	 ?>
		 <form id="genCharForm" action="characterGen.php" onsubmit="return verifyForm()" method="post">
			Amount of Characters to Create:<input type="text" id="characterNum" size="5" name="characterNum" class="correctTextbox" required><br>
			Amount of Templates:<input type="text" id="templateNum" size="5" name="templateNum" class="correctTextbox" required><br>
			
			<section id = "formDyna"></section>
			<section id ="charGenMainError"></section>
			
			<input id ="formSubmit" type="submit" value="Generate Characters">
		</form>
	 <?php 
	 }else{
		 //no templates are created --> no characters can be generated since no templates are made
	 	?>
	 	<p>Please create character templates in order to generate a character.</p>
	 	<?php
	 }
	 
	 ?>
	
	
	<!-- if characters are generated, show character list for editing -->
	</section>
	<section id="rightSide">
	<?php if (!empty($genCharacters) ){
		echo "<p>Generated Characters: ";
			foreach($genCharacters as $character){
				echo "<br>" . $character ;
			
			}  
		} 
		echo "</p>";
	?>
	</section>
	
<!--</section> -->
</body>
<?php createFooter(); ?>
<script type = "text/javascript" src = "js/charGenVerifyListeners.js">
</html>