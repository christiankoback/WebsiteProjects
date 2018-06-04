<?php
/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: viewingFunctions.php
file description: houses specific functions for displaying templates and characters
*/

/*
	get functions to access database
	get functions to get list of various properties
*/
include "databaseFunctions.php";

/*
	create unique title for the displaying template/character page
*/
function customTitle($title){
    ?><section id="header">
    <h1 id="topLeft"><?php echo $title;?></h1><section id="topRight"></section>
    </section>
    <?php

}

/*
	saving character/template functions
	START
*/
function saveCharacterName($userID, $characterName, $newName, $isGuest){
    $query = "";
    if ( isCharacterCreated($userID, $newName, $isGuest) !== true){
        if( $isGuest === true){
            $conn = connectViaMysql();
            $querySetup = "UPDATE genesisGuest SET name='".$newName."' WHERE guestLogin='".$userID."' AND name='".$characterName."' AND type='c'";
            $query = mysqli_query($conn,$querySetup);
            mysqli_close($conn);
        }else{
            $conn = connectViaMysql();
            $querySetup = "UPDATE CharList SET CharName='".$newName."' WHERE UserID='".$userID."' AND CharName='".$characterName."'";
            $query = mysqli_query($conn,$querySetup);
            mysqli_close($conn);
        }
        if ($query){
            $tableOld = $characterName . $userID;
            $tableNew = $newName . $userID;
            $conn = connectViaMysql();
            $querySetup = "ALTER TABLE ".$tableOld." RENAME TO ".$tableNew;
            $query = mysqli_query($conn,$querySetup);
            mysqli_close($conn);
            if ($query){}
            else{
                echo "<p>Character could not be renamed.</p>";
            }
        }else{
            echo "<p>Character could not be renamed.</p>";
        }

    }else{
        echo "<p>Character cannot be renamed. Character already exists with that name.</p>";
    }
}
function saveTemplateName($userID, $templateName, $newName, $isGuest){
    $query = "";
    if ( isTemplateCreated($userID, $newName, $isGuest) !== true){
        if( $isGuest === true){
            $conn = connectViaMysql();
            $querySetup = "UPDATE genesisGuest SET name='".$newName."' WHERE guestLogin='".$userID."' AND name='".$templateName."' AND type='t'";
            $query = mysqli_query($conn,$querySetup);
            mysqli_close($conn);
        }else{
            $conn = connectViaMysql();
            $querySetup = "UPDATE TemList SET TemName='".$newName."' WHERE UserID='".$userID."' AND TemName='".$templateName."'";
            $query = mysqli_query($conn,$querySetup);
            mysqli_close($conn);
        }
        if ($query){
            $tableOld = $templateName . $userID;
            $tableNew = $newName . $userID;
            $conn = connectViaMysql();
            $querySetup = "ALTER TABLE ".$tableOld." RENAME TO ".$tableNew;
            $query = mysqli_query($conn,$querySetup);
            mysqli_close($conn);
            if ($query){}
            else{
                return false;
            }
        }else{
            return false;
        }
    }else{
        return false;
    }
    return true;
}

/*
	saving character/template functions
	END
*/
/* 
	functions for displaying template/characters
	START
*/

function showTemplateEditable($user, $template, $isGuest, $formSubmit,$isEditable){
    $isValid = isTemplateCreated($user, $template, $isGuest);
    if ($isValid === true){
        //get # of sections
        //set all even sections to show on left, odd on right
        $sections = getSectionList($user, $template);
        $sectionTypes = getSectionTypeList($user,$template);
        $sectionAmount = count($sections);


        if ($isEditable === true){  ?>
            <form id="modifyChar" action=<?php if ($isEditable === true){ echo $formSubmit;}else{echo "";}?> onsubmit="return verifyForm()" method="post">
            Template Name : <input type="text" id="temName" name="temName" value=<?php echo $template;?> ><br><br>

            <section id="errorMessages"></section>
            <section id="leftColTemplate" >
                <?php
                if ($sectionAmount  > 0){
                    //display all even sections in left column
                    for($i = 0; $i < $sectionAmount; $i += 2){
                        $sectionID = "sectionName".$i;
                        $sectionType = "sectionType".$i;

                        ?>Section: <input type="text" id=<?php echo $sectionID;?> name=<?php echo $sectionID;?> value=<?php echo $sections[$i]; ?> onkeyup="checkName(this)"><br><?php

                        if ($sectionTypes[$i] === "property"){
                            $propertyNameList = getDataPerSection($user,$template,$sections[$i],"propertyName");
                            $lowValueList = getDataPerSection($user,$template,$sections[$i],"lowValue");
                            $highValueList = getDataPerSection($user,$template,$sections[$i],"highValue");

                            for($count2 = 0; $count2 < count($propertyNameList);$count2++){
                                ?>
                                Property Name: <input type="text" id=<?php echo $sectionID."propName".$count2;?> name=<?php echo $sectionID."propName".$count2;?> value=<?php echo $propertyNameList[$count2]; ?> onkeyup="checkName(this)">
                                <br>Range:<br>
                                Low Value: <input type="text" id=<?php echo $sectionID."lowVal".$count2;?> name=<?php echo $sectionID."lowVal".$count2;?> value=<?php echo $lowValueList[$count2];?> onkeyup="checkNumber(this)"><br>
                                High Value: <input type="text" id=<?php echo $sectionID."highVal".$count2;?> name=<?php echo $sectionID."highVal".$count2;?> value=<?php echo $highValueList [$count2];?> onkeyup="checkNumber(this)"><br>
                                <?php
                            }
                        }else if ($sectionTypes[$i] === "description"){
                            $descriptionNameList = getDataPerSection($user,$template,$sections[$i],"propertyName");
                            $descrList = getDataPerSection($user,$template,$sections[$i],"description");

                            for($count2 = 0; $count2 < count($descriptionNameList);$count2++){
                                ?>
                                Description Name: <input type="text" id=<?php echo $sectionID."desc".$count2;?> name=<?php echo $sectionID."desc".$count2;?> value=<?php echo $descriptionNameList[$count2]; ?>  onkeyup="checkName(this)">
                                <br>
                                <textarea id=<?php echo $sectionID."desctext". $count2;?> rows="4" cols="50">
						<?php echo $descrList[$count2]; ?>
						</textarea>
                                <br>
                            <?php	}
                        }else{
                            echo "<p> Section Type is not valid</p>";
                        }
                        echo "<br>";
                    }
                }else{echo "no sections in template";}
                ?>
            </section>
            <section id="rightColTemplate" >
                <?php
                if ($sectionAmount  > 0){
                    //display all even sections in left column
                    for($i = 1; $i < $sectionAmount; $i +=2){
                        $sectionID = "sectionName".$i;
                        $sectionType = $sectionTypes[$i];

                        ?>Section: <input type="text" id=<?php echo $sectionID;?> name=<?php echo $sectionID;?> value=<?php echo $sections[$i]; ?>  onkeyup="checkName(this)"><br><?php

                        if ($sectionType  === "property"){
                            $propertyNameList = getDataPerSection($user,$template,$sections[$i],"propertyName");
                            $lowValueList = getDataPerSection($user,$template,$sections[$i],"lowValue");
                            $highValueList = getDataPerSection($user,$template,$sections[$i],"highValue");

                            for($count2 = 0; $count2 < count($propertyNameList);$count2++){
                                $propNameID = $sectionID."propName".$count2;
                                $propLowID = $sectionID."lowVal".$count2;
                                $propHighID = $sectionID."highVal".$count2;
                                ?>
                                Property Name: <input type="text" id=<?php echo $propNameID;?> name=<?php echo $propNameID;?> value=<?php echo $propertyNameList[$count2]; ?> onkeyup="checkName(this)">
                                <br>Range:<br>
                                Low Value: <input type="text" id=<?php echo $propLowID;?> name=<?php echo $propLowID;?> value=<?php echo $lowValueList[$count2];?> onkeyup="checkNumber(this)"><br>
                                High Value: <input type="text" id=<?php echo $propHighID;?> name=<?php echo $propHighID;?> value=<?php echo $highValueList[$count2];?> onkeyup="checkNumber(this)"><br>
                                <?php
                            }
                        }else if ($sectionType  === "description"){
                            $descriptionNameList = getDataPerSection($user,$template,$sections[$i],"propertyName");
                            $descrList = getDataPerSection($user,$template,$sections[$i],"description");

                            for($count2 = 0; $count2 < count($descriptionNameList);$count2++){
                                $descID = $sectionID."desc".$count2;
                                $descTextID = $sectionID."desctext". $count2;
                                ?>
                                Description Name: <input type="text" id=<?php echo $descID;?> name=<?php echo $descID;?> value=<?php echo $descriptionNameList[$count2]; ?> onkeyup="checkName(this)">
                                <br>
                                <textarea id=<?php echo $descTextID;?> name=<?php echo $descTextID;?> rows="4" cols="50">
						<?php echo $descrList[$count2]; ?>
						</textarea>
                                <br>
                            <?php	}
                        }else{
                            echo "<p> Section Type is not valid</p>";
                        }
                        echo "<br>";
                    }
                }else{echo "no sections in template";}
                ?>
            </section>
            <br>
            <input id ='changeTemValues' name='changeTemValues' type='submit' value='Save Changes'>
            </form>
            <?php
        }
        else{
            ?>
            <section id="leftColTemplate" >
                <?php
                if ($sectionAmount  > 0){
                    //display all even sections in left column
                    for($i = 0; $i < $sectionAmount; $i += 2){
                        $sectionType= $sectionTypes[$i];

                        echo "<p class='sectionName'>".$sections[$i]." </p>";

                        if ( $sectionType=== "property"){
                            $propertyNameList = getDataPerSection($user,$template,$sections[$i],"propertyName");
                            $lowValueList = getDataPerSection($user,$template,$sections[$i],"lowValue");
                            $highValueList = getDataPerSection($user,$template,$sections[$i],"highValue");


                            for($count2 = 0; $count2 < count($propertyNameList);$count2++){
                                echo "<p class='properties'>".$propertyNameList[$count2] . "</p>";
                                echo "Range: ".$lowValueList[$count2]. " &#8594; ". $highValueList[$count2] . "<br>";
                            }
                            echo "<br>";
                        }else if ($sectionType === "description"){
                            $descNameList = getDataPerSection($user,$template,$sections[$i],"propertyName");
                            $descrList = getDataPerSection($user,$template,$sections[$i],"description");
                            for($count2 = 0; $count2 < count($descNameList );$count2++){
                                echo "<p class='properties'>".$descNameList[$count2] . "</p>";
                                echo "<p>" . $descrList[$count2] . "</p>";
                                echo "<br/>";
                            }
                        }else{
                            echo "<p> Section Type is not valid</p>";
                        }
                        echo "<br/>";
                    }
                }else{echo "no sections in template";}
                ?>
            </section>
            <section id="rightColTemplate" >
                <?php
                if ($sectionAmount  > 0){
                    //display all even sections in left column
                    for($i = 1; $i < $sectionAmount; $i +=2){
                        $sectionType= $sectionTypes[$i];

                        echo "<p class='sectionName'>".$sections[$i]." </p>";

                        if ($sectionType === "property"){
                            $propertyNameList = getDataPerSection($user,$template,$sections[$i],"propertyName");
                            $lowValueList = getDataPerSection($user,$template,$sections[$i],"lowValue");
                            $highValueList = getDataPerSection($user,$template,$sections[$i],"highValue");

                            for($count2 = 0; $count2 < count($propertyNameList);$count2++){
                                echo "<p class='properties'>".$propertyNameList[$count2] . "</p>";
                                echo "Range: ".$lowValueList[$count2]. " &#8594; ". $highValueList[$count2] . "<br>";
                            }
                            echo "<br>";
                        }else if ($sectionType === "description"){
                            $descNameList = getDataPerSection($user,$template,$sections[$i],"propertyName");
                            $descrList = getDataPerSection($user,$template,$sections[$i],"description");
                            for($count2 = 0; $count2 < count($descNameList );$count2++){
                                echo "<p class='properties'>".$descNameList[$count2] . "</p>";
                                echo "<p>" . $descrList[$count2] . "</p>";
                                echo "<br/>";
                            }
                        }else{
                            echo "<p> Section Type is not valid</p>";
                        }
                        echo "<br/>";
                    }
                }else{echo "no sections in template";}
                ?>
            </section>
            <br>
            <?php
        }
    }
}
function showCharacterEditable($user, $character, $isGuest, $formSubmit,$isEditable){
    $isValid = isCharacterCreated($user, $character, $isGuest);
    if ($isValid === true){
        if ($isEditable === true){?>
            <form id="modifyChar" action=<?php if ($isEditable === true){ echo $formSubmit;}else{echo "";}?> onsubmit="return verifyForm()" method="post">
            Character Name : <input type="text" id="charName" name="charName" value=<?php echo $character;?> ><br><br>
            <?php
            //get # of sections
            //set all even sections to show on left, odd on right
            $sections = getSectionList($user, $character);
            $sectionTypes = getSectionTypeList($user,$character);
            $sectionAmount = count($sections);

            ?>
            <section id="errorMessages"></section>
            <section id="leftColTemplate" >
                <?php
                if ($sectionAmount  > 0){
                    //display all even sections in left column
                    for($i = 0; $i < $sectionAmount; $i += 2){
                        $sectionID = "sectionName".$i;
                        $sectionType = "sectionType".$i;

                        ?>Section: <input type="text" id=<?php echo $sectionID;?> name=<?php echo $sectionID;?> value=<?php echo $sections[$i]; ?> onkeyup="checkName(this)"><br><?php

                        if ($sectionTypes[$i] === "property"){
                            $propertyNameList = getDataPerSection($user,$character,$sections[$i],"propertyName");
                            $valueList = getDataPerSection($user,$character,$sections[$i],"value");

                            for($count2 = 0; $count2 < count($propertyNameList);$count2++){
                                ?>
                                Property Name: <input type="text" id=<?php echo $sectionID."propName".$count2;?> name=<?php echo $sectionID."propName".$count2;?> value=<?php echo $propertyNameList[$count2]; ?> onkeyup="checkName(this)">
                                Value: <input type="text" id=<?php echo $sectionID."propValue".$count2;?> name=<?php echo $sectionID."propValue".$count2;?> value=<?php echo $valueList[$count2];?> onkeyup="checkNumber(this)"><br>
                                <?php
                            }
                        }else if ($sectionTypes[$i] === "description"){
                            $descriptionNameList = getDataPerSection($user,$character,$sections[$i],"propertyName");
                            $descrList = getDataPerSection($user,$character,$sections[$i],"description");
                            for($count2 = 0; $count2 < count($descriptionNameList);$count2++){
                                ?>
                                Description Name: <input type="text" id=<?php echo $sectionID."desc".$count2;?> name=<?php echo $sectionID."desc".$count2;?> value=<?php echo $descriptionNameList [$count2]; ?> onkeyup="checkName(this)">
                                <br>
                                <textarea id=<?php echo "desctext". $count2;?> name=<?php echo $sectionID."desctext". $count2;?> rows="4" cols="50">
							<?php echo $descrList[$count2]; ?>
							</textarea>
                                <br>
                            <?php   }
                        }else{}
                    }
                }
                ?>
            </section>
            <section id="rightColTemplate" >
                <?php
                if ($sectionAmount  > 0){
                    //display all even sections in left column
                    for($i = 1; $i < $sectionAmount; $i +=2){
                        $sectionID = "sectionName".$i;
                        $sectionType = "sectionType".$i;

                        ?>Section: <input type="text" id=<?php echo $sectionID;?> name=<?php echo $sectionID;?> value=<?php echo $sections[$i]; ?> onkeyup="checkName(this)"><br><?php

                        if ($sectionTypes[$i] === "property"){
                            $propertyNameList = getDataPerSection($user,$character,$sections[$i],"propertyName");
                            $valueList = getDataPerSection($user,$character,$sections[$i],"value");

                            for($count2 = 0; $count2 < count($propertyNameList);$count2++){
                                ?>
                                Property Name: <input type="text" id=<?php echo $sectionID."propName".$count2;?> name=<?php echo $sectionID."propName".$count2;?> value=<?php echo $propertyNameList[$count2]; ?> onkeyup="checkName(this)">
                                Value: <input type="text" id=<?php echo $sectionID."propValue".$count2;?> name=<?php echo $sectionID."propValue".$count2;?> value=<?php echo $valueList[$count2];?> onkeyup="checkNumber(this)"><br>
                                <?php
                            }
                        }else if ($sectionTypes[$i] === "description"){
                            $descriptionNameList = getDataPerSection($user,$character,$sections[$i],"propertyName");
                            $descrList = getDataPerSection($user,$character,$sections[$i],"description");
                            for($count2 = 0; $count2 < count($descriptionNameList);$count2++){
                                ?>
                                Description Name: <input type="text" id=<?php echo $sectionID."desc".$count2;?> name=<?php echo $sectionID."desc".$count2;?> value=<?php echo $descriptionNameList [$count2]; ?> onkeyup="checkName(this)">
                                <textarea id=<?php echo $sectionID."desctext". $count2;?> name=<?php echo $sectionID."desctext". $count2;?> rows="4" cols="50">
							<?php echo $descrList[$count2]; ?>
							</textarea>
                                <br/>
                            <?php  }
                        }else{}
                    }
                }
                ?>
            </section>
            <br>
            <input id ='changeCharValues' name='changeCharValues' type='submit' value='Save Changes'>
            </form>
            <?php
        }
        else{
            $sections = getSectionList($user, $character);
            $sectionTypes = getSectionTypeList($user,$character);
            $sectionAmount = count($sections);
            ?>
            <section id="leftColTemplate" >
                <?php
                if ($sectionAmount  > 0){
                    //display all even sections in left column
                    for($i = 0; $i < $sectionAmount; $i += 2){
                        $sectionID = "sectionName".$i;

                        echo "<p class='sectionName'>".$sections[$i]." </p>";


                        if ($sectionTypes[$i] === "property"){
                            $propertyNameList = getDataPerSection($user,$character,$sections[$i],"propertyName");
                            $valueList = getDataPerSection($user,$character,$sections[$i],"value");
                            for($count2 = 0; $count2 < count($propertyNameList);$count2++){
                                echo "<p class='properties'>".$propertyNameList[$count2] . "</p>";
                                echo "Value: ".$valueList[$count2]. "<br>";
                            }
                            echo "<br>";
                        }else if ($sectionTypes[$i] === "description"){
                            $descNameList = getDataPerSection($user,$character,$sections[$i],"propertyName");
                            $descrList = getDataPerSection($user,$character,$sections[$i],"description");
                            for($count2 = 0; $count2 < count($descNameList);$count2++){
                                echo $descNameList[$count2] . " : ".$descrList[$count2];
                                echo "<br/>";
                            }
                        }else{}
                        echo "<br>";
                    }
                }
                ?>
            </section>
            <section id="rightColTemplate" >
                <?php
                if ($sectionAmount  > 0){
                    //display all even sections in left column
                    for($i = 1; $i < $sectionAmount; $i +=2){
                        $sectionID = "sectionName".$i;

                        echo "<p class='sectionName'>".$sections[$i]." </p>";


                        if ($sectionTypes[$i] === "property"){
                            $propertyNameList = getDataPerSection($user,$character,$sections[$i],"propertyName");
                            $valueList = getDataPerSection($user,$character,$sections[$i],"value");
                            for($count2 = 0; $count2 < count($propertyNameList);$count2++){
                                echo "<p class='properties'>".$propertyNameList[$count2] . "</p>";
                                echo "Value: ".$valueList[$count2]. "<br>";
                            }
                            echo "<br>";
                        }else if ($sectionTypes[$i] === "description"){
                            $descNameList = getDataPerSection($user,$character,$sections[$i],"propertyName");
                            $descrList = getDataPerSection($user,$character,$sections[$i],"description");
                            for($count2 = 0; $count2 < count($descNameList);$count2++){
                                echo $descNameList[$count2] . " : ".$descrList[$count2];
                                echo "<br/>";
                            }
                        }else{}
                        echo "<br>";
                    }
                }
                ?>
            </section>
            <br>
            <?php
        }
    }
}

/* 
	functions for displaying template/characters
	END
*/
/* 
	functions to save modified data -- experimental
	START
*/
function saveProperty($userID, $template, $property, $propertyName, $newValue, $section){
    $table= $template.$userID;
    $conn = connectViaMysql();
    $querySetup = "UPDATE ".$table." SET ".$property."='".$newValue."' WHERE propertyName='".$propertyName."' AND section='".$section."'";
    $query = mysqli_query($conn,$querySetup);
    mysqli_close($conn);
    if ($query){
        return true;
    }
    else{
        return false;
    }
}


// $propertyNames, $oldValues, $newValues, $sections   : arrays of necessary values
function saveEditedTemplate($userID, $template, $newName, $isGuest, $propertyNames, $oldValues, $newValues, $sections){
    $errorExists = false;
    $errorExists |= saveTemplateName($userID, $template, $newName, $isGuest);
    $propertySavedSucceeded = true;
    for($i = 0; $i < count($propertyNames); $i++){
        if( ($propertySavedSucceeded === true) && ( saveProperty($userID, $template, $propertyNames[$i], $oldValues[$i], $newValues[$i], $sections[$i]) === true)){
            $propertySavedSucceeded = true;
        }
        else{
            $propertySavedSucceeded = false;
        }
    }
    if( ($errorExists === false)&& ($propertySavedSucceeded === true) ){
        echo "Template changes were saved successfully.";
    }
}
/*
function getSpecificValueofProperty($user,$characterTemplate,$sectionName,$property,$value, $dataWanted){
	$table = $characterTemplate.$user;
	
	$conn = connectViaMysql();
	$querySetup = "SELECT $dataWanted FROM $table where sectionName='$sectionName' AND $property='$value'";
	$data = mysqli_query($conn,$querySetup);
	mysqli_close($conn);
	 if ($data->num_rows === 1 ) {
	     return $data->fetch_row();
	 }
	 else{
	     return 0;
	 }    
}

// $propertyNames, $oldValues, $newValues, $sections   : arrays of necessary values
function saveEditedCharacter($userID, $template, $newName, $isGuest, $properties, $propertyNames, $newValues, $sections){
	$errorExists = false;
	if( ($newName !== "") && (isCharacterCreated($userID, $templateName, $isGuest) !== true)){
		$errorExists |= saveCharacterName($userID, $template, $newName, $isGuest);
		$propertySavedSucceeded = true;
	}
	for($i = 0; $i < count($propertyNames); $i++){
		//retrieve old value from character
		// trigger update sql with edited value
saveProperty($userID, $template, $property, $propertyName, $newValue, $section);

		if( ($propertySavedSucceeded === true) && ( saveProperty($userID, $template, $properties[$i], $propertyNames[$i], $newValues[$i], $sections[$i]) === true)){
			$propertySavedSucceeded = true;
		}
		else{
			$propertySavedSucceeded = false;
		}
	}
	if( ($errorExists === false)&& ($propertySavedSucceeded === true) ){
		echo "Template changes were saved successfully.";
	} 
}
*/

/* 
	functions to save modified data -- experimental
	END
*/

?>