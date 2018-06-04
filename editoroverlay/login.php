<?php

include "databaseFunctions.php";


// test if user info has been entered
session_start();
if ( (!isset($_SESSION["user"])) && (!isset($_SESSION["pageMode"]) ) ){	//new session
	$_SESSION["pageMode"] = "loginPage";
}
else{	// already created session
	if( isset($_SESSION["user"] ) && isset($_SESSION["userID"] )  ){
		unset ($_SESSION["pageMode"]);
		header("Location: editorOverlay.php");
		exit;
	}else{
		if ($_SESSION["pageMode"] === "loginPage"){
			if ( isset($_POST['loginButton']) ) {
				if( isset($_POST['userNameLogin']) && !empty($_POST['userNameLogin']) && isset($_POST['passwordLogin']) && !empty($_POST['passwordLogin']) )
				{
					$conn = connectViaMysql();
					
					//check if user and password are valid
					$user = mysqli_real_escape_string($conn, $_POST['userNameLogin'] );
					$pwd = mysqli_real_escape_string($conn, $_POST['passwordLogin'] );
					mysqli_close($conn);

					$userId = isPersonInDBase($user, $pwd);
					if( $userId != 0 ) {
						$_SESSION["user"] = $user;
						$_SESSION["userID"] = $userId[0];
						$guestTime = "";
						unset ($_SESSION["pageMode"]);
						header("Location: editorOverlay.php");
						exit;
					}else {	
						//else keep links classes at "links1"
						$viewedPage = "loginPage";
						$guestTime = "";
						$userId = "";
						echo "<p>Something is not correct. Please check your login information or register.";
					}
				}
			}else if (isset($_POST['registerButton']) ){
				$_SESSION["pageMode"] = "registerPage";
			}else{}
		
		}
		else if ($_SESSION["pageMode"] === "registerPage" ){
			if (isset($_POST['registerButton']) ){ 
				if  (  isset($_POST['userNameRegister']) && !empty($_POST['userNameRegister']) && isset($_POST['emailRegister']) && !empty($_POST['emailRegister']) )
				{		
					$conn = connectViaMysql();
					$user = mysqli_real_escape_string($conn, $_POST['userNameRegister'] );
					$email = mysqli_real_escape_string($conn, $_POST['emailRegister'] );
					mysqli_close($conn);
					
					if(!preg_match("(^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9]+(\.[a-z0-9]+)*(\.[a-z]{2,3})$)", $email)){
						// Return Error - Invalid Email
						$msg = 'The email you have entered is invalid, please try again.';
					}else{
						//check if user name is taken
						if ( userExists($user) === false){
							// Return Success - Valid Email
							$msg = 'Your account has been made. <br /> Please verify it by clicking the activation link that has been send to your email.<br><br>';
							$hash = md5( rand(0,1000) ); // Generate random 32 character hash and assign it to a local variable.
							
							$salt1 = "";
							$bytesPerSalt1 = 16;
				
							do{
								$salt1 = openssl_random_pseudo_bytes($bytesPerSalt1, $cstrong);
							}while($cstrong != true);
				
							$temp = password_hash((String)($hash . $salt1), PASSWORD_BCRYPT );
							
							sendEmail($user, $email, $temp);
							saveNewUser($user,$email,"0","0", $temp);
						}
					}	
				}else{}
			}else if (isset($_POST['loginButton']) ){
				$_SESSION["pageMode"] = "loginPage";
			}else{}

		}
	}
}
?>

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="css/loginDesign.css" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type = "text/javascript" src = "js/index.js"></script>
	<title>Editor Overlay</title>
</head>

<body>
<section id="logobar">
	<image id="logo" src="pics/ringOfFire.jpg" alt="Fire Logo"/>
	<h1 id="title">Editor Overlay </h1>
</section>
<section id="forms"></section>
<?php 
	if ($_SESSION["pageMode"] === "loginPage"){ ?>
		<section id="loginInfo" >
			<form action="login.php" method="post" >
				<fieldset id="userFields" class="fieldGrouping">User Name:<input type="text" id="userNameLogin" name="userNameLogin" class="correctTextbox"><button id="userHelp" class="help" /></fieldset><br>
				<fieldset id="passFields" class="fieldGrouping">Password:<input type="password" id="passwordLogin" name="passwordLogin" class="correctTextbox"><button id="passHelp" class="help" /></fieldset><br>
				<section id="LoginError"></section>
				<button id="loginButton" name="loginButton">Log in</button>
				<!-- <input id="registerButton" name="registerButton" type="submit" value="Register">  -->
			</form>
		</section>
	<?php 
	}elseif ($_SESSION["pageMode"] === "registerPage"){ ?>
		<section id="registerInfo">
			<?php 
		    if(isset($msg)){  // Check if $msg is not empty
		        echo '<div class="statusmsg">'.$msg.'</div>'; // Display our message and wrap it with a div with the class "statusmsg".
		    } 
			?>
			NOTE: You must verify your email in order to set a password for your account.
			<br><br>
			<form action="login.php" method="post">
				First name: <input type="text" id="userNameRegister" name="userNameRegister" class="registering">
				<br />
				 Email : <input type="text" id="emailRegister" name="emailRegister" class="registering"><br> 
				<section id="RegisterError"></section>
				<section id="buttonsRegister" >
					<input id="registerButton" class="registering" name="registerButton" type="submit" value="Submit for Registration">
					<input id="loginButton" class="registering" name="loginButton" type="submit" value="Back To Login">
				</section>
			
			</form>
		</section>
	<?php 
	}else{} ?>
</section>
</body>
</html>