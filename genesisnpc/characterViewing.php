<?php
/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: characterViewing.php
file description:  main page for displaying template/characterViewing	
	---> this page is launched/reached when user clicks on a template/character name
*/

/*
	get all pre-made functions for displaying the character 
*/

session_start();


include "viewingFunctions.php";
include "basicSetup.php";
/*
	checks if user is logged in, otherwise redirect to loginPage
	
	set all necessary variables from session 
*/
$user = "";
$pass = "";
$userID="";
$isGuest = false;
$editing = true;
$viewingUser = "";
$template = "";
$typeOfData="";
$notUpdated = "";

if ( isset($_SESSION["loginPage"] ) ){	//new session
    $userID = $_SESSION["userID"] ;
    $user = $_SESSION["user"] ;
    $pass = $_SESSION["pass"] ;
}
if (isset( $_SESSION["guestID"]) &&  !empty( $_SESSION["guestID"]) ){
    if ($_SESSION["guestID"] != "") {
        $isGuest = true;
        $guestID = $_SESSION["guestID"];
    }
    else{
        $isGuest = false;
    }
}
//set character variable if character, otherwise check and set template variable
if ( isset($_SESSION['charId']) && ! empty($_SESSION['charId']) ){
    $template = $_SESSION['charId'];
    $typeOfData = "c";
}else if ( isset($_SESSION['temId']) && ! empty($_SESSION['temId']) ){
    $template = $_SESSION['temId'];
    $typeOfData = "t";
}else{}

if ( isset($_SESSION["displayUser"] ) && ! empty($_SESSION["displayUser"] ) ){
    $viewingUser = $_SESSION["displayUser"] ;
}
if (isset( $_POST["editData"]) && ! empty( $_POST["editData"]) ){
    $editing = true;
}
if (isset( $_POST["cancelEdit"]) && ! empty($_POST["cancelEdit"]) ){
    $editing = false;
}

$validateUser = isPersonInDBase($user, $pass);
//query if the person is a valid person
if( ($validateUser[0] == $userID) || ($isGuest === true) ){
    if ($isGuest === true) {
        $identity = $guestID;
    } else {
        $identity = $userID;
    }
    
    
    if ($viewingUser === $identity) {
        $isEditable = true;
    } else {
        $isEditable = false;
    }
    if (  !empty($_POST['changeTemValues']) && isset($_POST['changeTemValues']) ){
        /*
            if character values are modified, retrieve the changes and save them into the database
        */
        $results = false;
        $newTemplateName = $_POST['temName'];
        
        if (   strcmp($template, $newTemplateName) !== 0){
            $results = saveTemplateName($userID, $template, $newTemplateName, $isGuest);
            $template = $newTemplateName;
        }
       
        $sections = getSectionList($userID, $template);
        $sectionTypes = getSectionTypeList($userID,$template);
        $sectionNum = count($sections);
        
        for ($sectionIndex = 0; $sectionIndex < $sectionNum; $sectionIndex++) {
            $sectName = "sectionName" . $sectionIndex;

            //get amount of properties and check if any new Values are posted --> get old values to replace
            $propertyNameList = getDataPerSection($userID, $template, $sections[$sectionIndex ], "propertyName");
            $lowValueList = getDataPerSection($userID, $template , $sections[$sectionIndex], "lowValue");
            $highValueList = getDataPerSection($userID, $template , $sections[$sectionIndex], "highValue");
            $descrList = getDataPerSection($userID, $template, $sections[$sectionIndex ], "description");
            $propNumber = count($propertyNameList);    
            
            $propertyCount = 0;
            $descCount = 0;
	    
		for ($propIndex = 0; $propIndex < $propNumber; $propIndex++){
			if ($sectionTypes[$sectionIndex] === "property"){
				$name = $sectName . "propName" . $propIndex ;
        			$lowValue = $sectName . "lowVal" . $propIndex ;
        			$highValue = $sectName . "highVal" . $propIndex ;
				$oldPropertyName = $propertyNameList[$propIndex];
        			$oldLowPropValue = $lowValueList[$propIndex];
        			$oldHighPropValue = $highValueList[$propIndex];
        			$newValue = $_POST[$name];
        			
        			if ( strcmp($oldPropertyName ,$newValue ) !== 0 ){
	                            $tempBool = updateProperty($identity, $template, $oldPropertyName ,  $newValue, "propertyName", $sections[$sectionIndex]);
	                            if (  (!$notUpdated && $tempBool) === true){
	                                $notUpdated = false;
	                            }else{
	                            	$notUpdated = true;
	                            }
	                        }
	                        $newValue = $_POST[$lowValue];
        			if ( strcmp($oldLowPropValue ,$newValue ) !== 0 ){
	                            $tempBool = updateProperty($identity, $template, $oldLowPropValue ,  $newValue, "lowValue", $sections[$sectionIndex]);
	                            if (  (!$notUpdated && $tempBool) === true){
	                                $notUpdated = false;
	                            }else{
	                            	$notUpdated = true;
	                            }
	                        }
	                        
	                        $newValue = $_POST[$highValue];
        			if ( strcmp($oldHighPropValue ,$newValue ) !== 0 ){
	                            $tempBool = updateProperty($identity, $template, $oldHighPropValue ,  $newValue, "highValue", $sections[$sectionIndex]);
	                            if (  (!$notUpdated && $tempBool) === true){
	                                $notUpdated = false;
	                            }else{
	                            	$notUpdated = true;
	                            }
	                        }
        			
			}else if ($sectionTypes[$sectionIndex] === "description"){
				$name = $sectName . "desc" . $propIndex;
        			$description =  $sectName . "desctext" . $propIndex;
        			$oldPropertyName =  $propertyNameList[$propIndex];
        			$oldDescription = $descrList[$propIndex];
				$newValue = $_POST[$name];
				
				if ( strcmp($oldPropertyName ,$newValue ) !== 0 ){
	                            $tempBool = updateProperty($identity, $template, $oldPropertyName ,  $newValue, "propertyName", $sections[$sectionIndex]);
	                            if (  (!$notUpdated && $tempBool) === true){
	                                $notUpdated = false;
	                            }else{
	                            	$notUpdated = true;
	                            }
	                        }
	                        
				$newValue = $_POST[$description];
				if ( strcmp($oldDescription ,$newValue) !== 0 ){
	                            $tempBool = updateProperty($identity, $template, $oldDescription ,  $newValue, "description", $sections[$sectionIndex]);
	                            if (  (!$notUpdated && $tempBool) === true){
	                                $notUpdated = false;
	                            }else{
	                            	$notUpdated = true;
	                            }
	                        }
			}else{}
		}	
        }
        ?><!DOCTYPE html>
        <html lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link rel="stylesheet" href="css/viewingDesign.css" type="text/css">
            </link>
            <!--
            add javascript page-->
            <script type="text/javascript" src="js/viewingFunctions.js"></script>
            <title>Viewing: <?php echo $template; ?></title>
        </head>
        <body>
        <?php
        if( ($notUpdated === true) || ($notUpdated === "") ){
            echo "There was an error saving the template.";
        }
        else{
            //successfull updated properties
            echo "Template was successfully changed.";
        }
        ?>
        </body>
        <?php createFooter(); ?>
        </html>
        <?php
        
    }
    else if (  !empty($_POST['changeCharValues']) && isset($_POST['changeCharValues']) ){
        /*
            if template values are modified, retrieve the changes and save them into the database
        */
        $results = false;
        $newTemplateName = $_POST['charName'];
        
        if (   strcmp($template, $newTemplateName) !== 0){
            $results = saveCharacterName($userID, $template, $newTemplateName, $isGuest);
            $template = $newTemplateName;
        }
       
        $sections = getSectionList($userID, $template);
        $sectionTypes = getSectionTypeList($userID,$template);
        $sectionNum = count($sections);
        
        for ($sectionIndex = 0; $sectionIndex < $sectionNum; $sectionIndex++) {
            $sectName = "sectionName" . $sectionIndex;

            //get amount of properties and check if any new Values are posted --> get old values to replace
            $propertyNameList = getDataPerSection($userID, $template, $sections[$sectionIndex ], "propertyName");
            $valueList = getDataPerSection($userID, $template, $sections[$sectionIndex ], "value");
            $descrList = getDataPerSection($userID, $template, $sections[$sectionIndex ], "description");
            $propNumber = count($propertyNameList);    
            
            $propertyCount = 0;
            $descCount = 0;
	    
		for ($propIndex = 0; $propIndex < $propNumber; $propIndex++){
			if ($sectionTypes[$sectionIndex] === "property"){
				$name = $sectName . "propName" . $propIndex ;
        			$value = $sectName . "propValue" . $propIndex ;
				$oldPropertyName = $propertyNameList[$propIndex];
        			$oldPropValue = $valueList[$propIndex];
        			$newValue = $_POST[$name];
        			
        			if ( strcmp($oldPropertyName ,$newValue ) !== 0 ){
	                            $tempBool = updateProperty($identity, $template, $oldPropertyName ,  $newValue, "propertyName", $sections[$sectionIndex]);
	                            if (  (!$notUpdated && $tempBool) === true){
	                                $notUpdated = false;
	                            }else{
	                            	$notUpdated = true;
	                            }
	                        }
	                        $newValue = $_POST[$value];
        			
        			if ( strcmp($oldPropertyName ,$newValue ) !== 0 ){
	                            $tempBool = updateProperty($identity, $template, $oldPropValue ,  $newValue, "value", $sections[$sectionIndex]);
	                            if (  (!$notUpdated && $tempBool) === true){
	                                $notUpdated = false;
	                            }else{
	                            	$notUpdated = true;
	                            }
	                        }
        			
			}else if ($sectionTypes[$sectionIndex] === "description"){
				$name = $sectName . "desc" . $propIndex;
        			$description =  $sectName . "desctext" . $propIndex;
        			$oldPropertyName =  $propertyNameList[$propIndex];
        			$oldDescription = $descrList[$propIndex];
				$newValue = $_POST[$name];
				
				if ( strcmp($oldPropertyName ,$newValue ) !== 0 ){
	                            $tempBool = updateProperty($identity, $template, $oldPropertyName ,  $newValue, "propertyName", $sections[$sectionIndex]);
	                            if (  (!$notUpdated && $tempBool) === true){
	                                $notUpdated = false;
	                            }else{
	                            	$notUpdated = true;
	                            }
	                        }
	                        
				$newValue = $_POST[$description];
				if ( strcmp($oldDescription ,$newValue) !== 0 ){
	                            $tempBool = updateProperty($identity, $template, $oldDescription ,  $newValue, "description", $sections[$sectionIndex]);
	                            if (  (!$notUpdated && $tempBool) === true){
	                                $notUpdated = false;
	                            }else{
	                            	$notUpdated = true;
	                            }
	                        }
			}else{}
		}
		
        }
        ?><!DOCTYPE html>
        <html lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link rel="stylesheet" href="css/viewingDesign.css" type="text/css">
            </link>
            <!--
            add javascript page-->
            <script type="text/javascript" src="js/viewingFunctions.js"></script>
            <title>Viewing: <?php echo $template; ?></title>
        </head>
        <body>
        <?php
        if( ($notUpdated === true) || ($notUpdated === "") ){
            echo "There was an error saving the character.";
        }
        else{
            //successfull updated properties
            echo "Character was successfully changed.";
        }
        ?>
        </body>
        <?php createFooter(); ?>
        </html>
        <?php
    }
    else{
        ?><!DOCTYPE html>
        <html lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link rel="stylesheet" href="css/viewingDesign.css" type="text/css" >
            </link>
            <!--
            add javascript page-->
            <script type = "text/javascript" src = "js/viewingFunctions.js"></script>
            <title>Viewing: <?php echo $template; ?></title>
        </head>
        <body>
        <?php
        $title = "Viewing: ".$template;
        customTitle($title);?>
        <br>
        
        <?php
        if ($isEditable === true){
            echo "<form id='emptyForm' method='post' action='characterViewing.php'>";
            if ($editing === false){
                echo "<input type='submit' id='editData' name='editData' value='Edit this Data'>";
            }else{
                echo "<input type='submit' id='cancelEdit' name='cancelEdit' value='Cancel Edit'>";
            }
            echo "</form>";
        
        }
        else{
            $editing = false;
        }
        
        //user is either a valid user or a guest
        
        if ( $typeOfData === "c"){
            //show the chosen character 
            showCharacterEditable($identity, $template, $isGuest, "characterViewing.php",$editing);
        }
        else if ( $typeOfData === "t"){
            //show the chosen template
            showTemplateEditable($identity, $template, $isGuest, "characterViewing.php",$editing);
        }
        else{}
        ?>
        </body>
        <?php createFooter(); ?>
        </html>
        <?php
    }
}else{
    //not a valid user
    header("Location: logout.php");
    exit;
}
?>