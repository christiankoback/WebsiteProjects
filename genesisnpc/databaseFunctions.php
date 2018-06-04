<?php
/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: databaseFunctions.php
file description:  contains all functions for use with database

*/
/*   
	connections to database functions
	START
*/
/*connects to the database using PDO methods*/
function connectToDBase(){
    //enter database info
	$server = "";
    $user = "";	//user --database
    $pass = "";		//user's password
    $dbase = "";			// database within mySQL
    $dbaseConnection = "mysql:host=$server;dbname=$dbase";
    try
    {
        $conn = new PDO($dbaseConnection, $user , $pass);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    catch(PDOException $e)
    {
        echo "<p>Connection failed. Please talk to the website administrator.</p>";
        $conn = "";
    }
    return $conn;
}
function connectViaMysql(){
    $server = "";
    $user = "";		//user --database
    $pass = "";		//user's password
    $dbase = "";			// database within mySQL

    $conn = new mysqli($server, $user, $pass, $dbase);
    if($conn->connect_errno > 0) {
        die('Connection failed [' . $conn->connect_error . ']');
    }
    return $conn;
}
/*  close PDO connection to database  */
function closeDBase($conn){	
    $conn = null;
}


/*   
	connections to database functions
	END
*/
/*
	functions for verifying user's account
	START
*/
/*  old function to add a user to database -- no verifying account */
function saveUser($user,$hash,$salt,$email){
    try{
        $conn = connectToDBase();
        $saveUser = $conn->prepare('INSERT INTO genesisUser (user,userpwd,security,email) VALUES (:user, :hash, :salt, :email)');
        $saveUser->bindParam(':user', $user);
        $saveUser->bindParam(':hash', $hash);
        $saveUser->bindParam(':salt', $salt);
        $saveUser->bindParam(':email', $email);
        $saveUser->execute();
        closeDBase($conn);
    }
    catch(PDOException $e){
        echo 'Error: ' . $e->getMessage();
    }
}
/* new function to add a user to database -- verifies user account  */
function saveNewUser($user,$email,$pass,$salt, $temp){
	try{
        $conn = connectViaMysql();
		
		mysqli_query($conn, "INSERT INTO genesisUser (user, userpwd, security, hash, email) VALUES(
		'". mysqli_real_escape_string($conn,$user) ."', 
		'". mysqli_real_escape_string($conn,$pass) ."', 
		'". mysqli_real_escape_string($conn,$salt) ."', 
		'". mysqli_real_escape_string($conn,$temp) ."',  
		'". mysqli_real_escape_string($conn,$email) ."') ") or die(mysqli_error($conn));
	}
    catch(PDOException $e){
        echo 'Error: ' . $e->getMessage();
    }
	mysqli_close($conn);
}
/* sends user a verification email   */ 
function sendEmail($user, $email, $loginInfo){
	$to      = $email; // Send email to our user
	$subject = 'Signup | Verification'; // Give the email a subject 
	$message = '
	 
	Thanks for signing up!
	Your account has been created, please verify your account in order to set up your password for your login.
	 
	------------------------
	Username: '.$user.'
	Password: To be added
	------------------------
	 
	Please click this link to activate your account:
	https://www.kobackproducts.ca/genesisnpc/verify.php?email='.$email.'&hash='.$loginInfo.''; // Our message above including the link
	
	$headers = 'From:noreply@kobackproducts.ca' . "\r\n"; // Set from headers
	mail($to, $subject, $message, $headers); // Send our email
	
}
/* checks if the values are correct for validation */
function verifyUser($email, $hash){
	$conn = connectViaMysql();
	$email = mysqli_real_escape_string($conn,$email);
	$loginInfo = mysqli_real_escape_string($conn,$hash);

	$temp = $loginInfo;
	$success = false;
	try{

		$checkQuery = "SELECT hash FROM genesisUser WHERE email='".$email."' AND hash='".$loginInfo."' AND active='0'";
		$search = mysqli_query($conn, $checkQuery) or die(mysqli_error($conn)); 
		$match  = mysqli_num_rows($search);

		if($match > 0){
			// We have a match, activate the account
			$success = true;	
		}else{
			$success = "Invalid approach, please use the link that has been send to your email.";// No match -> invalid url or account has already been activated.
		}
	}
    catch(PDOException $e){
        echo 'Error: ' . $e->getMessage();
    }
	mysqli_close($conn);
	return $success;
}
/* changes user's account to active, changes the user's password, sets the verification info to 0 for no re-use */
function setupUser($email, $hash, $pass, $passSalt){
	$conn = connectViaMysql();
	$email= mysqli_real_escape_string($conn, $email);
	$hash= mysqli_real_escape_string($conn, $hash);
	$pass= mysqli_real_escape_string($conn, $pass);
	$passSalt= mysqli_real_escape_string($conn, $passSalt);
	try{
		//set active to 1 
		$activateQuery = "UPDATE genesisUser SET active='1' WHERE email='".$email."' AND hash='".$hash."' AND active='0'";
		mysqli_query($conn,$activateQuery) or die(mysqli_error($conn));
		
		//save password into database
		$activateQuery = "UPDATE genesisUser SET userpwd='".$pass. "' WHERE email='".$email."' AND hash='".$hash."' AND active='1'";
		mysqli_query($conn,$activateQuery) or die(mysqli_error($conn));
		$activateQuery = "UPDATE genesisUser SET security='".$passSalt. "' WHERE email='".$email."' AND hash='".$hash."' AND active='1'";
		mysqli_query($conn,$activateQuery) or die(mysqli_error($conn));
		
		// set temp stuff to 00000
		$activateQuery = "UPDATE genesisUser SET hash='0000' WHERE email='".$email."' AND userpwd='".$pass."' AND active='1'";
		mysqli_query($conn,$activateQuery) or die(mysqli_error($conn));
		
	}catch(PDOException $e){
        echo 'Error: ' . $e->getMessage();
    }
	mysqli_close($conn);
}

/*
	functions for verifying user's account
	END
*/
/* 
	functions to check if various data exists 
	START
*/
function isPersonInDBase($userName, $password){
    try {
        $conn = connectToDBase();
        $statement = $conn->prepare("select userpwd from genesisUser where user= :userName");
        $statement->execute(array(':userName' => $userName ));
        $pwd = $statement->fetch();
        $hash = $pwd[0];

        $getSalt = $conn->prepare("SELECT security FROM genesisUser where user= :userName");
        $getSalt->execute(array(':userName' => $userName ));
        $salty = $getSalt->fetch();
        $pass = $password . $salty[0];

        if (  password_verify( $pass, $hash) ){
            $personIDSetup = $conn->prepare("SELECT userID FROM genesisUser where user= :userName AND userpwd= :hash");
            $personIDSetup->execute(array(':userName' => $userName, ':hash' =>$hash ));
            $personID = $personIDSetup->fetchAll();

            if (  count($personID) == 1){
                return $personID[0];
            }else{
                return 0;
            }
        }
    }
    catch(PDOException $ex){
        //person doesn't exist
        return "Something went wrong in while retreiving person.";
    }
    closeDBase($conn);
}
function isTemplateCreated($userID, $templateName, $isGuest){
    $templateExists = false;
    $conn = connectViaMysql();
    $userID = mysqli_real_escape_string($conn,$userID);
    $templateName = mysqli_real_escape_string($conn,$templateName );

    if ($isGuest === true)
    {
        $querySetup = "SELECT guestAmount FROM genesisGuest WHERE guestLogin='".$userID."' AND type='t' AND name='". $templateName ."'";
    }
    else{
        $querySetup = "SELECT DateCreated FROM TemList WHERE UserID='".$userID."' AND TemName='". $templateName . "'";
    }
    $query = mysqli_query($conn,$querySetup);
    if ($query->num_rows > 0) {
        $templateExists = true;
        //$success = true;
    }
    else{
        //echo "Error: " . $$querySetup . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
    return $templateExists;
}
function isCharacterCreated($userID, $templateName, $isGuest){
    $charExists = false;
    $conn = connectViaMysql();
    $userID = mysqli_real_escape_string($conn,$userID);
    $templateName = mysqli_real_escape_string($conn,$templateName );
   
    
    if ($isGuest === true)
    {
        $querySetup = "SELECT guestAmount FROM genesisGuest WHERE guestLogin='".$userID."' AND type='c' AND name='". $templateName ."'";
    }
    else{
        $querySetup = "SELECT DateCreated FROM CharList WHERE UserID='".$userID."' AND CharName='". $templateName . "'";
    }
    $query = mysqli_query($conn,$querySetup);
    if ($query->num_rows > 0) {		//testing
        $charExists= true;
    } else {
        //echo "Error: " . $$querySetup . "<br>" . mysqli_error($conn2);
    }
    mysqli_free_result($query);
    mysqli_close($conn);
    return $charExists;
}
/* 
	functions to check if various data exists 
	END
*/
/*
	functions to create various templates and characters
	START
*/
function createCharacterTemplateRegUser($userID, $template){
    $success = false;
    $dateVar = date("Ymdh");
    
    //create template table
    $conn2 = connectViaMysql();
    $userID = mysqli_real_escape_string($conn2,$userID);
    $template = mysqli_real_escape_string($conn2,$template );
    $table = $template.$userID;

    $conn = connectToDBase();
    //add link to template in template table
    $saveTemplate = $conn->prepare("INSERT INTO TemList (userID,DateCreated,TemName) VALUES (:userID,:dateVar,:template)");
    $saveTemplate->bindParam(':userID', $userID);
    $saveTemplate->bindParam(':dateVar', $dateVar);
    $saveTemplate->bindParam(':template', $template);
    $saveTemplate->execute();
    closeDBase($conn);


    
    $querySetup = "CREATE TABLE IF NOT EXISTS " .  $table . "(propertyName varchar(255) NOT NULL, lowValue int(255) , highValue int(255) , sectionName varchar(255) NOT NULL, description varchar(255) NOT NULL, sectType varchar(255) NOT NULL)";
    $query = mysqli_query($conn2,$querySetup);
    if ($query ) {		//testing
        $success = true;
    } else {
        //echo "Error: " . $$querySetup . "<br>" . mysqli_error($conn2);
    }
    mysqli_close($conn2);
    return $success;
}
function createCharacterTemplateGuest($userID, $template){
    $success = false;
    $dateVar = date("Ymdh");
    $conn = connectViaMysql();
    $userID = mysqli_real_escape_string($conn,$userID);
    $template = mysqli_real_escape_string($conn,$template );
    
    $table = $template.$userID ;

    
    //create template table
    $querySetup = "SELECT * FROM genesisGuest WHERE guestLogin='".$userID ."' AND name='" .$template ."'";
    $query = mysqli_query($conn,$querySetup);

    if ($query->num_rows == 0) {
        $conn2 = connectToDBase();
        //add link to template in template table
        $saveTemplate = $conn2->prepare("INSERT INTO genesisGuest (guestLogin,name,type) VALUES (:userID,:name,'t')");
        $saveTemplate->bindParam(':userID', $userID);
        $saveTemplate->bindParam(':name', $template);
        $saveTemplate->execute();
        closeDBase($conn2);

        //create template table
        $conn3 = connectViaMysql();
        //create template table
        $querySetup = "CREATE TABLE IF NOT EXISTS ".$table. "(propertyName varchar(255) NOT NULL ,lowValue int(255) NOT NULL,highValue int(255) NOT NULL,sectionName varchar(255) NOT NULL, description varchar(255) NOT NULL, sectType varchar(255) NOT NULL )";
        $query1 = mysqli_query($conn3,$querySetup);
        if ($query1 ) {		//testing
            $success = true;
        } else {
            echo "Error: query2" . $$querySetup . "<br>" . mysqli_error($conn2);
        }
        mysqli_close($conn3);
    } else {
        echo "Error: query1" . $$querySetup . "<br>" . mysqli_error($conn);
    }
    mysqli_free_result($query);
    mysqli_close($conn);
    return $success;
}
function createCharacterRegUser($userID, $template){
    $success = false;
    $dateVar = date("Ymdh");
    $conn2 = connectViaMysql();
    $userID = mysqli_real_escape_string($conn2,$userID);
    $template = mysqli_real_escape_string($conn2,$template );
    $table = $template.$userID;

    $conn = connectToDBase();
    //add link to template in template table
    $saveTemplate = $conn->prepare("INSERT INTO CharList (userID,DateCreated,CharName) VALUES (:userID,:dateVar,:template)");
    $saveTemplate->bindParam(':userID', $userID);
    $saveTemplate->bindParam(':dateVar', $dateVar);
    $saveTemplate->bindParam(':template', $template);
    $saveTemplate->execute();
    closeDBase($conn);

    //create template table
    
    $querySetup = "CREATE TABLE IF NOT EXISTS " .  $table . "(propertyName varchar(255) NOT NULL, value int(255) NOT NULL, sectionName varchar(255) NOT NULL, description varchar(255) NOT NULL, sectType varchar(255) NOT NULL )";
    $query = mysqli_query($conn2,$querySetup);
    if ($query ) {		//testing
        $success = true;
    } else {
        //echo "Error: " . $$querySetup . "<br>" . mysqli_error($conn2);
    }
    mysqli_close($conn2);
    return $success;
}
function createCharacterGuest($userID, $template){
    $success = false;
    $dateVar = date("Ymdh");
    $conn = connectViaMysql();
    $userID = mysqli_real_escape_string($conn,$userID);
    $template = mysqli_real_escape_string($conn,$template );
    $table = $template.$userID ;

    
    //create template table
    $querySetup = "SELECT * FROM genesisGuest WHERE guestLogin='".$userID ."' AND name='" .$template ."'";
    $query = mysqli_query($conn,$querySetup);
    mysqli_close($conn);
    if ($query->num_rows == 0) {
        $conn2 = connectToDBase();
        //add link to template in template table
        $saveTemplate = $conn2->prepare("INSERT INTO genesisGuest (guestLogin,name,type) VALUES (:userID,:name,'c')");
        $saveTemplate->bindParam(':userID', $userID);
        $saveTemplate->bindParam(':name', $template);
        $saveTemplate->execute();
        closeDBase($conn2);

        //create template table
        $conn2 = connectViaMysql();
        //create template table
        $querySetup = "CREATE TABLE IF NOT EXISTS ".$table. "(propertyName varchar(255) NOT NULL ,value int(255) NOT NULL,sectionName varchar(255) NOT NULL, description varchar(255) NOT NULL, sectType varchar(255) NOT NULL)";
        $query2 = mysqli_query($conn2,$querySetup);
        if ($query2 ) {		//testing
            $success = true;
        } else {
            //echo "Error: " . $$querySetup . "<br>" . mysqli_error($conn2);
        }
        mysqli_close($conn2);
    }
    mysqli_free_result($query);
    return $success;
}

/*
	functions to create various templates and characters
	END
*/
/* 
	functions to add data to templates/characters
	START
*/
function addPropertiesToTemplate($userID,$template, $property,$lowVal,$highVal,$section, $description,$sectType){
    $success = false;
    $conn = connectViaMysql();
    $userID = mysqli_real_escape_string($conn,$userID);
    $template = mysqli_real_escape_string($conn,$template );
    $property= mysqli_real_escape_string($conn,$property);
    $lowVal= mysqli_real_escape_string($conn,$lowVal);
    $highVal= mysqli_real_escape_string($conn,$highVal);
    $section= mysqli_real_escape_string($conn,$section);
    $sectType= mysqli_real_escape_string($conn,$sectType);
    $description= mysqli_real_escape_string($conn,$description);
    $table = $template.$userID;

    
    $querySetup = "INSERT INTO  ".$table."(propertyName,lowValue,highValue,sectionName,description,sectType ) VALUES ('".$property."','".$lowVal."','".$highVal."','".$section."','".$description."','".$sectType."')";
    $query = mysqli_query($conn,$querySetup);
    if ($query ) { //testing
        $success = true;
    } else {
        //echo "Error: " . $$querySetup . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
    return $success;
}

function addPropertiesToCharacter($userID,$template, $property,$val,$section, $description,$sectType){
    $success = false;
    $conn = connectViaMysql();
    $userID = mysqli_real_escape_string($conn,$userID);
    $template = mysqli_real_escape_string($conn,$template );
    $property= mysqli_real_escape_string($conn,$property);
    $val= mysqli_real_escape_string($conn,$val);
    $section= mysqli_real_escape_string($conn,$section);
    $sectType= mysqli_real_escape_string($conn,$sectType);
    $description= mysqli_real_escape_string($conn,$description);
    $table = $template.$userID;

    
    $querySetup = "INSERT INTO  ".$table."(propertyName,value,sectionName,description,sectType) VALUES ('".$property."','".$val."','".$section."','".$description."','".$sectType."')";
    $query = mysqli_query($conn,$querySetup);
    if ($query ) { //testing
        $success = true;
    } else {
        //echo "Error: " . $$querySetup . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
    return $success;
}
/* 
	functions to add data to templates/characters
	END
*/

/*
	retrieve various data from template
	START
*/
function getCharacterTemplateList($userId,$isGuest){
    $conn = connectToDBase();
    if($isGuest == true){
        $getTemplate = $conn->prepare("SELECT DISTINCT name FROM genesisGuest where guestLogin =:userId AND type='t'");
        $getTemplate->bindParam(':userId', $userId);
        $getTemplate->execute();
        $template = $getTemplate->fetchAll();
    }
    else{
        $saveTemplate = $conn->prepare("SELECT DISTINCT TemName FROM TemList where UserID = :userId ORDER BY DateCreated DESC");
        $saveTemplate->bindParam(':userId', $userId);
        $saveTemplate->execute();
        $template = $saveTemplate->fetchAll();
    }
    closeDBase($conn);

    $templateList = array();
    foreach($template as $name){
        array_push($templateList,$name[0]);
    }
    return $templateList ;
}

/*
	retrieve various data from template
	END
*/
/*
	retrieve various data from character
	START
*/
function getCharacterList($userId,$isGuest){
    $conn = connectToDBase();
    
    if ($isGuest == true){
        $charSetup = $conn->prepare("SELECT DISTINCT name FROM genesisGuest where guestLogin = :userId AND type='c'");

        $charSetup ->bindParam(':userId', $userId);
        $charSetup->execute();
        $characterList = $charSetup->fetchAll();
    }
    else{
        $charSetup = $conn->prepare("SELECT DISTINCT CharName FROM CharList where UserID = :userId ORDER BY DateCreated DESC");

        $charSetup ->bindParam(':userId', $userId);
        $charSetup->execute();
        $characterList = $charSetup->fetchAll();
    }
    closeDBase($conn);
    $characters = array();
    foreach($characterList as $name){
        array_push($characters ,$name[0]);
    }
    return $characters;
}

/*
	retrieve various data from character
	END
*/
/*
	generic retrieval functions -- works for both templates & characters
	START
*/

function updateProperty($user, $template, $oldPropertyValue, $newPropertyValue, $propertyName, $sectionName){

    $table = $template.$user;
    $querySetup = "UPDATE ". $table . " SET ". $propertyName. "='" . $newPropertyValue ."' WHERE sectionName='". $sectionName."' AND ". $propertyName . "='" .$oldPropertyValue . "'";
    
    $conn = connectViaMysql();
    $query = mysqli_query($conn,$querySetup);
	if (mysqli_affected_rows($conn) > 0 ){
		return true;
	}
	else{
		return false;
	}
}
function getSectionList($user,$characterTemplate){
    $SectionNames = null;
    $conn = connectViaMysql();
    $user= mysqli_real_escape_string($conn,$user);
    $characterTemplate= mysqli_real_escape_string($conn,$characterTemplate);
    $table = $characterTemplate.$user;

    
    $querySetup = "SELECT DISTINCT sectionName FROM ".$table;
    $SectionNames = mysqli_query($conn,$querySetup);
    
    $sectionList = array();
    if($SectionNames){
	    while ($row = $SectionNames->fetch_row()) {
	        array_push($sectionList, $row[0] );
	    }
    }
    mysqli_close($conn);

    return $sectionList;
}
function getSectionTypeList($user,$characterTemplate){
    $sectionList = getSectionList($user,$characterTemplate);
    
    $conn = connectViaMysql();
    $user= mysqli_real_escape_string($conn,$user);
    $characterTemplate= mysqli_real_escape_string($conn,$characterTemplate);
    $table = $characterTemplate.$user;

     $listOfSectTypesInOrder = array();
     for ($i = 0; $i < count($sectionList); $i ++){
	$querySetup1 = "SELECT sectType FROM ".$table . " where sectionName='".$sectionList[$i] . "'";
	$SectionTypes = mysqli_query($conn,$querySetup1);
	$row = $SectionTypes->fetch_row();
	array_push($listOfSectTypesInOrder, $row[0] );
     }
    mysqli_close($conn);

    return $listOfSectTypesInOrder ;
}
function getSpecificValueofProperty($user,$characterTemplate,$sectionName,$property,$value, $dataWanted){
	$conn = connectViaMysql();
	$user= mysqli_real_escape_string($conn,$user);
	$characterTemplate= mysqli_real_escape_string($conn,$characterTemplate);
	$property= mysqli_real_escape_string($conn,$property);
	$sectionName= mysqli_real_escape_string($conn,$sectionName);
	$value= mysqli_real_escape_string($conn,$value);
	$dataWanted= mysqli_real_escape_string($conn,$dataWanted);
	
	$table = $characterTemplate.$user;

	$querySetup = "SELECT $dataWanted FROM $table where sectionName='$sectionName' AND $property='$value'";
	$data = mysqli_query($conn,$querySetup);
	mysqli_close($conn);
	 if ($data->num_rows === 1 ) {
	     $info = $data->fetch_row();
	     mysqli_free_result($data);
	     return $info;
	 }
	 else{
	     return 0;
	 }    
}
function getDataPerSection($user,$characterTemplate,$sectionName,$dataWanted){
    $conn = connectViaMysql();
    $user= mysqli_real_escape_string($conn,$user);
    $characterTemplate= mysqli_real_escape_string($conn,$characterTemplate);
    $sectionName= mysqli_real_escape_string($conn,$sectionName);
    $dataWanted= mysqli_real_escape_string($conn,$dataWanted);
    $table = $characterTemplate.$user;
    
    
    $querySetup = "SELECT $dataWanted FROM $table where sectionName='$sectionName'";
    $data = mysqli_query($conn,$querySetup);
    $dataList = array();
    if($data){
	    while ($row = $data->fetch_row()) {
	        array_push($dataList, $row[0] );
	    }
	}
	
	mysqli_close($conn);
    	return $dataList ;
}
/*
	generic retrieval functions -- works for both templates & characters
	END
*/

/* 
	displaying info functions -- experimental
	START
*/
function displayTemplateInfo($user,$template){
    
    //get character identification
    $display = "";
    $data1 = "propertyName";
    $data2 = "lowValue";
    $data3 = "highValue";

    $display += "<p>Template Name : $template </p>";
    $sections = getSectionListFromTemplate($user,$template);
    $leftDisplay="";
    $rightDisplay="";
    $count = 0;
    foreach($sections as $section){
        $sectionName = $section[0];
        if ( ($count % 2) == 0){    //add to the left( even)
            $leftDisplay += "<br><p class='sectionNames'>Section : $sectionName</p>";
            $properties = getDataPerSectionFromTemplate($user,$template,$sectionName,$data1) ;
            $lowPropertyValues = getDataPerSectionFromTemplate($user,$template,$sectionName,$data2) ;
            $highPropertyValues = getDataPerSectionFromTemplate($user,$template,$sectionName,$data3) ;
            for ($i = 0; $i < count($properties); $i++){
                $leftDisplay += "<br><p class='properties'>". $properties[$i] . ":". $lowPropertyValues . " --> ". $highPropertyValues ."</p>";
            }
            $leftDisplay += "<br>";
        }
        else{   //add to the right (odd)
            $rightDisplay += "<br><p class='sectionNames'>Section : $sectionName</p>";
            $properties = getDataPerSectionFromTemplate($user,$template,$sectionName,$data1) ;
            $lowPropertyValues = getDataPerSectionFromTemplate($user,$template,$sectionName,$data2) ;
            $highPropertyValues = getDataPerSectionFromTemplate($user,$template,$sectionName,$data3) ;
            for ($i = 0; $i < count($properties); $i++){
                $rightDisplay += "<br><p class='properties'>". $properties[$i] . ":". $lowPropertyValues . " --> ". $highPropertyValues ."</p>";
            }
            $rightDisplay += "<br>";
        }
        $count += 1;
    }

    $display += "<section id='centerSide'>" . $leftDisplay . "</section>";
    $display += "<section id='rightSide'>" . $rightDisplay . "</section>";

    return $display;
}
/*
function displayCharacterInfo($user,$template){
    //get character identification
    $display = "";
    $data1 = "propertyName";
    $data2 = "value";

    $display += "<p>Template Name : $template </p>";
    $sections = getSectionListFromCharacter($user,$template);
    $leftDisplay="";
    $rightDisplay="";
    $count = 0;
    foreach($sections as $section){
            $sectionName = $section[0];
        if ( ($count % 2) == 0){    //add to the left( even)
            $leftDisplay += "<br><p class='sectionNames'>Section : $sectionName</p>";
            $properties = getDataPerSectionFromCharacter($user,$template,$sectionName,$data1) ;
            $propertyValues = getDataPerSectionFromCharacter($user,$template,$sectionName,$data2) ;
            for ($i = 0; $i < count($properties); $i++){
                $leftDisplay += "<br><p class='properties'>". $properties[$i] . ":". $propertyValues ."</p>";
            }
            $leftDisplay += "<br>";
        }
        else{   //add to the right (odd)
            $rightDisplay += "<br><p class='sectionNames'>Section : $sectionName</p>";
            $properties = getDataPerSection($user,$template,$sectionName,$data1) ;
            $propertyValues = getDataPerSection($user,$template,$sectionName,$data2) ;
            for ($i = 0; $i < count($properties); $i++){
                $rightDisplay += "<br><p class='properties'>". $properties[$i] . ":". $propertyValues . "</p>";
            }
            $rightDisplay += "<br>";
        }
        $count += 1;
    }

    $display += "<section id='centerSide'>" . $leftDisplay . "</section>";
    $display += "<section id='rightSide'>" . $rightDisplay . "</section>";

    return $display;
}
*/

/* 
	displaying info functions -- experimental
	END
*/
/*
	delete functions (for use of guest logging out)
	START
*/
function deleteTable($userId,$table){
    $conn = connectViaMysql();
    $userId= mysqli_real_escape_string($conn,$userId);
    $table= mysqli_real_escape_string($conn,$table);
    
    $querySetup = "DROP TABLE IF EXISTS ". $table;
    $query = mysqli_query($conn,$querySetup);
    if ($query ) {		//testing
        $success = true;
    } else {
        echo "Error: " . $$querySetup . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);

}
function deleteGuestCharacters($guestID){
    //get list of characters
    $characterList = getCharacterList($guestID,true);

    //delete all characters
    foreach($characterList as $character){
        $table = $character.$guestID;
        deleteTable($guestID,$table);
    }
}
function deleteGuestTemplates($guestID){
    //get list of characters
    $templateList = getCharacterTemplateList($guestID,true);

    //delete all characters
    foreach($templateList as $template){
        $table = $template.$guestID;
        deleteTable($guestID,$table);
    }
}
function deleteGuest($guestID){
    $conn = connectViaMysql();
    $guestID= mysqli_real_escape_string($conn,$guestID);
    
    $querySetup = "DELETE FROM genesisGuest WHERE guestLogin='".$guestID . "'";
    $query = mysqli_query($conn,$querySetup);
    if ($query ) {		//testing
        $success = true;
    } else {
        //echo "Error: " . $$querySetup . "<br>" . mysqli_error($conn2);
    }
    mysqli_close($conn);

}

/*
	delete functions (for use of guest logging out)
	END
*/

/*Miscellaneous Functions  
	START
*/
function userExists($User){
	$conn = connectViaMysql();
	$querySetup = "SELECT userID FROM genesisUser WHERE user='".$User . "'";
	   $query = mysqli_query($conn,$querySetup);
	   
	    if ($query->num_rows > 0 ) {
	        return true;
	    }
	    else{
	        return false;
	    } 
	    mysqli_free_result($query);
	    mysqli_close($conn);   
}

/*   creates a character */
function generateCharacter($userID,$template,$isGuest, $characterNum){
    $isTableCreated = false;
    $guestLogin = $isGuest;
    $dateVar = date("Ymdh");
    $propertyValue = array();
    $errorOccurred = "";

    //generate temp characterName
    $characterName = $template ."temp" . $characterNum;
    
    //create table
    if($guestLogin === true){
        $isTableCreated = createCharacterGuest($userID, $characterName );
    }
    else{
        $isTableCreated = createCharacterRegUser($userID,$characterName );
    }
    if ($isTableCreated == true){
        //echo "<p>table was created</p>";
        //get list of sections  & section types
        $sectionList = getSectionList($userID,$template);
        $sectionTypes = getSectionTypeList($userID,$template);
        for ($sectionIndex = 0; $sectionIndex < count($sectionList); $sectionIndex ++){
        
            if ($sectionTypes[$sectionIndex] === "property"){
	            $propertyNameList = getDataPerSection($userID,$template,$sectionList[$sectionIndex],"propertyName");
	            $lowVal = getDataPerSection($userID,$template,$sectionList[$sectionIndex],"lowValue");
	            $highVal= getDataPerSection($userID,$template,$sectionList[$sectionIndex],"highValue");
	            for($i = 0; $i < count($propertyNameList);$i++){
	            	$tempValue = rand($lowVal[$i] ,$highVal[$i]);
	                $result = addPropertiesToCharacter($userID, $characterName, $propertyNameList[$i],$tempValue, $sectionList[$sectionIndex],"","property");
	                if ($result === false){
	                    echo "<p>There was an error when creating the character.</p>";
	                    return false;
	                }
	            }
	     }else if ($sectionTypes[$sectionIndex] === "description"){
		     	$descriptionNameList = getDataPerSection($userID,$template,$sectionList[$sectionIndex],"propertyName");
			$descrList = getDataPerSection($userID,$template,$sectionList[$sectionIndex],"description");
		     	
		     	for($i = 0; $i < count($descriptionNameList);$i++){
			        $result = addPropertiesToCharacter($userID, $characterName, $descriptionNameList[$i],"", $sectionList[$sectionIndex],$descrList[$i],"description");
		                if ($result === false){
		                    echo "<p>There was an error when creating the character.</p>";
		                    return false;
		                }
	                }
	     }
        }
        return $characterName;
    }
    else{
        echo "<p>There was an error when creating the character.</p>";
        return false;
    }
}
/* saves a template -- experimental    */
function saveModifiedTemplate($userID,$template,$propertyName, $lowValue, $highValue, $sectionName){
	$errorOccurred = "";
	
	$sectionNameBase = "sectionName";
	$propertiesBase = "propContainer";
	$propertiesProperty1 = "propName";
	$propertiesProperty2 = "lowVal";
	$propertiesProperty3 = "highVal";
	
	// array of section Names
	$sections = getSectionList($userID, $template);
	$sectionAmount = count(  $sections  );
	
	for ( $sectionIndex = 0; $sectionIndex < $sectionAmount;  $sectionIndex++){
		$propertyNumber = count( getDataPerSection($userID,$template,$sections[$sectionIndex],"propertyName") );
		
		$sectionNameIndex = $sectionNameBase . $sectionIndex;
		$propertyBase = $propertiesBase . $sectionIndex;
		
		$sectionName = $_POST[$sectionNameIndex] ;
		
		for( $propertyIndex = 0; $propertyIndex < $propertyNumber ; $propertyIndex++){
		
		// "propContainer" + i ;  +  "property" + i ;
			$tempPropertyIndex = $sectionNameIndex . $propertiesProperty1 . $propertyIndex;
			$tempLowRangeIndex = $sectionNameIndex . $propertiesProperty2 . $propertyIndex;
			$tempHighRangeIndex = $sectionNameIndex . $propertiesProperty3 . $propertyIndex;
			$propertyName = $_POST[$tempPropertyIndex];
			$lowValue = $_POST[$tempLowRangeIndex];
			$highValue = $_POST[$tempHighRangeIndex];
			$result = addPropertiesToTemplate($userID, $template, $propertyName, $lowValue, $highValue, $sectionName);
			if ($result === false){
				$errorOccurred = "<p>There was an error when creating the template.</p>";
				break;
			}
		}
		if($errorOccurred != ""){
			break;
		}
	}
	if($errorOccurred != ""){
		echo $errorOccurred;
	}
	else{
		echo "<p>Character Template has been created.  Please go to character generation to generate NPC characters.</p>";
	}
}
/*
returns type of data. if not created then returns false
*/
function isDataCreated($user, $dataTable){
	$dataType = false;
	$isTemplate = isTemplateCreated($user, $dataTable, false);
	if ($isTemplate === true){
		$dataType = "t";
	}
	else{
		$isCharacter = isCharacterCreated($user, $dataTable, false);
		if ( $isCharacter === true){
			$dataType = "c";
		}
	}
	return $dataType;
}
function getID_UNameTemplate($user,$template){
    $conn = connectViaMysql();
    $user= mysqli_real_escape_string($conn,$user);
    $template= mysqli_real_escape_string($conn,$template);

    $checkCharacters = "SELECT genesisUser.userId FROM genesisUser INNER JOIN CharList ON genesisUser.userId=CharList.UserID WHERE CharList.CharName='$template' AND genesisUser.user ='$user'";
    $characterList = mysqli_query($conn,$checkCharacters );
    $checkTemplates = "SELECT genesisUser.userId FROM genesisUser INNER JOIN TemList ON genesisUser.userId=TemList.UserID WHERE TemList.TemName='$template' AND genesisUser.user ='$user'";
    $TemplateList = mysqli_query($conn,$checkTemplates );


    if ($characterList->num_rows > 0) {
        $row = $characterList->fetch_row();
        return $row[0];
    }
    else{
        //echo "Error: " . $$querySetup . "<br>" . mysqli_error($conn);
    }
    if ($TemplateList->num_rows > 0) {
        $row = $TemplateList->fetch_row();
        return $row[0];
    }
    else{
        //echo "Error: " . $$querySetup . "<br>" . mysqli_error($conn);
    }

    return false;
}

/*Miscellaneous Functions  
	END
*/
?>