<?php 
/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: verify.php 
file description:  verifies the user's email
	---> resets the user's password
*/

include "databaseFunctions.php";


$isValidUser = false;
$message = "";
if  (  isset($_POST['passwordRegister']) && !empty($_POST['passwordRegister']) && isset($_POST['passwordRegister2']) && !empty($_POST['passwordRegister2']) )
{	
	echo " values are posted";
	$conn = connectViaMysql();
	$passOrig = mysqli_real_escape_string($conn, $_POST['passwordRegister']);
	$passNew = mysqli_real_escape_string($conn, $_POST['passwordRegister2']);
	$email = mysqli_real_escape_string($conn, $_POST['email']); // Set email variable
	$hash = mysqli_real_escape_string($conn, $_POST['hash']); // Set hash variable

	echo "done retrieving data";
	
	if ($passOrig === $passNew){
		echo "passwords match";
		
		//save password into database
		$salt = "";
		$bytesPerSalt = 16;
	
		do{
			$salt = openssl_random_pseudo_bytes($bytesPerSalt, $cstrong);
		}while($cstrong != true);
		
		$pass = password_hash((String)($passNew . $salt), PASSWORD_BCRYPT );
		setupUser($email, $hash, $pass , $salt);
		
		header("Location: logout.php");
		exit;
		
		//set active to 1 
		// set temp stuff to 00000
	}
}
if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash']) ){
	// Verify data
	$email = $_GET['email'];
	$hash = $_GET['hash'];
	echo "email: ". $email .", hash: ". $hash;		//testing
	
	//check if the data is correct
	$isValidUser = verifyUser($email, $hash);
	if ($isValidUser !== true){
		$message =  '<div class="statusmsg">' . $isValidUser . '</div>';
	}
}
else{
	$message = '<div class="statusmsg">Invalid approach, please use the link that has been send to your email.</div>';
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- add javascript page 
	<script type = "text/javascript" src = "jsFiles/indexJS.js"></script>  -->
	<title>Genesis NPC Generator</title>
</head>
<body>
<?php
		if ($isValidUser === true){		
		?>
			<form action="verify.php" method="post">
				Password: <input type="password" id="passwordRegister" name="passwordRegister" class="correctTextbox" required><br>
				Re-enter Password: <input type="password" id="passwordRegister2" name="passwordRegister2" class="correctTextbox" required><br>
			
				<input type="hidden" name="hash" value="<?php echo $hash; ?>" />
				<input type="hidden" name="email" value="<?php echo $email; ?>" />
			
			
				<input type="submit" id="verified" name="verified" value="Submit">
			</form>
		<?php
		}else{?>  	 
			<!-- start wrap div -->   
			<div id="wrap">
				<?php
					if ($message !== ""){
						echo $message;
					}
				?>
			</div>
		
		<?php
		} 
		?>
</body>
</html>	
