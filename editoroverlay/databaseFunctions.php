<?php
/*
programmer ---> Christian Koback
program ---> Editor Overlay
Date started : January 2018
Date stopped development: Ongoing
Purpose : allow editing over video view

file: databaseFunctions.php
file description:  contains all functions for use with database

*/
/*   
	connections to database functions
	START
*/
/*connects to the database using PDO methods*/
function connectToDBase(){
    $server = "";
    $user = "";		//user --database
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
        $saveUser = $conn->prepare('INSERT INTO users (user,cred,security,email) VALUES (:user, :hash, :salt, :email)');
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
		
		mysqli_query($conn, "INSERT INTO users (user, cred, security, hash, email) VALUES(
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
	https://www.kobackproducts.ca/editoroverlay/verify.php?email='.$email.'&hash='.$loginInfo.''; // Our message above including the link
	
	$headers = 'From:noreply@kobackproducts.ca' . "\r\n"; // Set from headers
	mail($to, $subject, $message, $headers); // Send our email
	
}
/* checks if the values are correct for validation */
function verifyUser($email, $hash){
	$conn = connectViaMysql();
	$email = mysqli_real_escape_string($conn,$email);
	$loginInfo = mysqli_real_escape_string($conn,$hash);

	$temp = $loginInfo;
	echo " <br><br><p>temp = : ". $temp . "</p>";
	$success = false;
	try{

		$checkQuery = "SELECT hash FROM users WHERE email='".$email."' AND hash='".$loginInfo."' AND active='0'";
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
        	
		echo "saving data";
		
		//set active to 1 
		$activateQuery = "UPDATE users SET active='1' WHERE email='".$email."' AND hash='".$hash."' AND active='0'";
		mysqli_query($conn,$activateQuery) or die(mysqli_error($conn));
		
		//save password into database
		$activateQuery = "UPDATE users SET cred='".$pass. "' WHERE email='".$email."' AND hash='".$hash."' AND active='1'";
		mysqli_query($conn,$activateQuery) or die(mysqli_error($conn));
		$activateQuery = "UPDATE users SET security='".$passSalt. "' WHERE email='".$email."' AND hash='".$hash."' AND active='1'";
		mysqli_query($conn,$activateQuery) or die(mysqli_error($conn));
		
		// set temp stuff to 00000
		$activateQuery = "UPDATE users SET hash='0000' WHERE email='".$email."' AND cred='".$pass."' AND active='1'";
		mysqli_query($conn,$activateQuery) or die(mysqli_error($conn));
		
		echo "done saving data";
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
        $statement = $conn->prepare("select cred from users where user= :userName");
        $statement->execute(array(':userName' => $userName ));
        $pwd = $statement->fetch();
        $hash = $pwd[0];

        $getSalt = $conn->prepare("SELECT security FROM users where user= :userName");
        $getSalt->execute(array(':userName' => $userName ));
        $salty = $getSalt->fetch();
        $pass = $password . $salty[0];

        if (  password_verify( $pass, $hash) ){
            $personIDSetup = $conn->prepare("SELECT active FROM users where user= :userName AND cred= :hash");
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
/* 
	functions to check if various data exists 
	END
*/
?>