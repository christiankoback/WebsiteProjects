<?php 
/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: charTemplateCreate.php
file description: template creation page
	---> highly dynamic - large emphasis on javascript
	---> allows user to create a template of various types and save it to the database
*/

/*
	get header/footer styles
*/
include "basicSetup.php";

/*
	get user check function
	get various save to database functions
*/
include "databaseFunctions.php";

/*  
	ensure the user is a valid user 
	---> if user is not logged in as anyone, redirect them to login page
*/
session_start();
if ( ! isset($_SESSION["loginPage"] ) ){	//new session
	$userID = "";
	$user = "";
	$pass = "";
}
else{	//already created session
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
	}
	else{
		$guestID=  "";
	}
}
//query if the person is a valid person
$validateUser = isPersonInDBase($user, $pass);

/*set a temp variable to the userID, 
	value of userIdentity will change dependent on if user is a guest or long-term user
*/
$userIdentity = $userID;
if( ($validateUser[0] == $userID) || ($userID == 1) ){
	$userIdentity = $userID;	
	if ($userID == 1 ){
		//person is a guest	,	save data for guest
		$userIdentity = $_SESSION['guestID'];
	}
	if (  ! empty(  $_POST['templateName'])  && isset($_POST['templateName']) ){
		//get the template name
		$template =   $_POST['templateName'] ;
		$errorOccurred = "";
		if ($userID == 1 ){
			createCharacterTemplateGuest($userIdentity,$template );
		}
		else{
			createCharacterTemplateRegUser($userIdentity,$template );
		}
		
		$sectionNameBase = "sectionName";
		$sectionTypeBase = "sectionType";
		$propertiesBase = "propContainer";
		$propertiesProperty1 = "uprop";
		$propertiesProperty2 = "ulowR";
		$propertiesProperty3 = "uhigh";
		$propertiesProperty4 = "udesc";
		$description = "udesctext";
		
		$sectionAmount = $_POST['sectionAmount'] ;
		
		for ( $sectionIndex = 0; $sectionIndex < $sectionAmount;  $sectionIndex++){
			$sectionNameIndex = $sectionNameBase . $sectionIndex;
			$sectionType = $sectionTypeBase . $sectionIndex;
			$propertyAmountIndex = "propertyNum"  . $sectionIndex ;
			
			$sectionName = $_POST[$sectionNameIndex] ;
			$propertyAmount = $_POST[$propertyAmountIndex];
			$sectType = $_POST[$sectionType];
			
			$propertyBase = $propertiesBase . $sectionIndex;
			if ($sectType === "basic"){
				for( $propertyIndex = 0; $propertyIndex < $propertyAmount; $propertyIndex++){
					// "propContainer" + i ;  +  "property" + i ;
					$tempPropertyIndex = $propertyBase . $propertiesProperty1 . $propertyIndex;
					$tempLowRangeIndex = $propertyBase . $propertiesProperty2 . $propertyIndex;
					$tempHighRangeIndex = $propertyBase . $propertiesProperty3 . $propertyIndex;
					$propertyName = $_POST[$tempPropertyIndex];
					$lowValue = $_POST[$tempLowRangeIndex];
					$highValue = $_POST[$tempHighRangeIndex];
					$result = addPropertiesToTemplate($userIdentity, $template, $propertyName, $lowValue, $highValue, $sectionName, "","property");
					if ($result === false){
						$errorOccurred = "<p>There was an error when creating the template.</p>";
						break;
					}
				}
				if($errorOccurred != ""){
					break;
				}
			}
			else if ($sectType === "description"){
				for( $propertyIndex = 0; $propertyIndex < $propertyAmount; $propertyIndex++){
					$tempPropertyIndex = $propertyBase . $propertiesProperty4 . $propertyIndex;
					$tempSectDescription = $propertyBase. $description . $propertyIndex;
					
					$descriptionName = $_POST[$tempPropertyIndex];
					$descriptionValue = $_POST[$tempSectDescription ];
					
					$result = addPropertiesToTemplate($userIdentity, $template, $descriptionName, "", "", $sectionName,$descriptionValue ,"description");
					if ($result === false){
						$errorOccurred = "<p>There was an error when creating the template.</p>";
						break;
					}
				}
			}
			else{}
		}
		if($errorOccurred != ""){
			echo $errorOccurred;
		}
		else{
			echo "<p>Character Template has been created.  Please go to character generation to generate NPC characters.</p>";
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
	<link rel="stylesheet" href="css/charTemplateDesign.css" type="text/css" />
	<!-- add javascript page -->
	<script type = "text/javascript" src = "js/cTemplateVerify.js"></script>
	<title>Genesis NPC Generator</title>
</head>

<body>
<?php createHeader("Character Template Creation", $user, $guestID); ?>
	
	<br><br>
	<section id="leftSide"> <button id="CreateCharacter" class="menuButtons" onclick="window.location='characterGen.php'">Create Character</button><br>
	<button id="mainPage" class="menuButtons" onclick="window.location='characterDisplay.php'">Back to Main</button><br>
	</section>
	
	<form id="newTemplate" action="charTemplateCreate.php" onsubmit="return verifyForm()" method="post">
		<section id="centerSide"> 
			<section id="rightData">
				<!-- <div class="data">Template Selection: <select name='TemplateSelect' class='correctTextbox'>
				  <option value='general'>General</option>
				  <option value='dungeons&dragons'>Dungeons & Dragons</option>
				  <option value='pathfinder'>Pathfinder</option>
				</select>  -->
				</div>
				<div class="data">
				Template Name:<input type="text" id="templateName" size="50" name="templateName" class="correctTextbox" required>
				</div>
				<br>
				<div class="data">
				Number of Sections:<input type="text" id="sectionAmount" size="5" name="sectionAmount" class="correctTextbox" onkeyup="showSections(this.value,'leftColTemplate', '0'), showSections(this.value,'rightColTemplate', '1')" onkeydown="clearContainer('leftColTemplate'), clearContainer('rightColTemplate')" value="" required>
				</div>
			</section>
		</section>
		<section id="templateTopError"> </section>
		<br>
		<section id="leftColTemplate" ></section>
		<section id="rightColTemplate" ></section>
		
		
		<br>
		
		<!--  button action in javascript -->
		<input id="templateSubmit" name="templateSubmit" type="submit" value="Create the Template">
	</form>
</body>
<?php createFooter(); ?>
<script type = "text/javascript" src = "js/cTemplateListeners.js"></script>
</html>