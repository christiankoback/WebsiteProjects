<?php
/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: index.php
file description:  index for the genesis NPC project
	--> handles login and registering of user
*/

/*
	get various database functions: check if user exists
		---> access database
*/
include "databaseFunctions.php";
include "basicSetup.php";

function verifyVariableToExpected($expected, $given){
	$verifiedVar  = "";
	if (  isset($given) && !empty($given)  ){
		$verifiedVar = $given;
	}
	if( ( $verifiedVar != "") && ( strcmp($expected, $given) === 0 ) ){
		return 1;
	}
	return 0;
}

//test if user info has been entered
session_start();
if ( ! isset($_SESSION['loginPage'] ) ){	//new session
	$viewedPage = "";
}
else{	//already created session
	$viewedPage = $_SESSION['loginPage'] ;
}

if ( verifyVariableToExpected("loginPage", $viewedPage) == 1){
	if( isset($_POST['userNameLogin']) && !empty($_POST['userNameLogin']) && isset($_POST['passwordLogin']) && !empty($_POST['passwordLogin']) )
	{
		$conn = connectViaMysql();
		
		//check if user and password are valid
		$user = mysqli_real_escape_string($conn, $_POST['userNameLogin'] );
		$pwd = mysqli_real_escape_string($conn, $_POST['passwordLogin'] );
		mysqli_close($conn);
		
		$userId = isPersonInDBase($user, $pwd);
		if( $userId != 0 ) {
			$_SESSION["userID"] = $userId[0];
			$_SESSION["user"] = $user;
			$_SESSION["pass"] = $pwd;
			$guestTime = "";
			header("Location: characterDisplay.php");
			exit;
			
		}else {	
			//else keep links classes at "links1"
			$viewedPage = "loginPage";
			$guestTime = "";
			$userId = "";
			echo "<p>Something is not correct. Please check your login information or register.";
		}
	}elseif ( isset($_POST['register']) && !empty($_POST['register']) ){	//test if register button was pressed
		$viewedPage = "registerPage";
		$guestTime = "";
	}elseif ( isset($_POST['guestLogin']) && !empty($_POST['guestLogin']) ) {	
	// test if login as guest button was pressed
		$_SESSION['loginPage'] = "guestPage";
		header("Location: index.php");
		exit;
	}else {
		$viewedPage = "loginPage";
		$guestTime = "";
	}
}elseif ( verifyVariableToExpected("registerPage", $viewedPage) == 1 ){
	if( isset($_POST['backToLogin']) && !empty($_POST['backToLogin']) ){
		$viewedPage = "loginPage";
		$guestTime = "";
	}
	
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
	}
	
}elseif ( verifyVariableToExpected("guestPage", $viewedPage) == 1 ){
//continue as guest
	$_SESSION["userID"] = "1";
	$_SESSION['guestID'] = date("Ymdhi");
	header("Location: characterDisplay.php");
	exit;
	
}else{
	$viewedPage = "loginPage";
	$guestTime = "";
}
$_SESSION['loginPage'] = $viewedPage;
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="css/indexDesign.css" type="text/css" >
	</link>
	<!-- add javascript page -->
	<script type = "text/javascript" src = "js/indexJS.js"></script>
	<title>Genesis NPC Generator</title>
</head>


<body>
<p id="debugging"></p>
<h1 id="title">Genesis NPC : Non-Player Character Generation & Management</h1>


<?php if ($viewedPage === "loginPage"){ ?>
<section id="loginInfo" >
	<form action="index.php" method="post" >
		User Name: <input type="text" id="userNameLogin" name="userNameLogin" class="correctTextbox"><br>
		Password: <input type="password" id="passwordLogin" name="passwordLogin" class="correctTextbox"><br>
		<section id="LoginError"></section>
		<input type="submit" value="Submit">
	
	
	<br>
	<section id="buttonsBypassLogin" ><input type="submit" id="register" name="register" value= "Register Here">   <input type="submit" id="guestLogin" name="guestLogin" value= "Login as Guest"></section>
	
	</form>
	
</section>
<?php }elseif ($viewedPage === "registerPage"){ ?>
<section id="registerInfo">
	<?php 
    if(isset($msg)){  // Check if $msg is not empty
        echo '<div class="statusmsg">'.$msg.'</div>'; // Display our message and wrap it with a div with the class "statusmsg".
    } 
	?>
	NOTE: You must verify your email in order to set a password for your account.
	<br><br>
	<form action="index.php" method="post">
		First name: <input type="text" id="userNameRegister" name="userNameRegister" class="correctTextbox"><br>

		email : <input type="text" id="emailRegister" name="emailRegister" class="textbox"><br>
		<section id="RegisterError"></section>
		
		<section id="buttonsRegister" ><input id="registered" name="registered" type="submit" value="Submit for Registration">  <input id="backToLogin" name="backToLogin" type="submit" value="Back To Login"></section>
	
	</form>


</section>

<?php }else{} ?>


</body>
<?php createFooter(); ?>
   <script type = "text/javascript" src = "js/indexListeners.js"></script>  
</html>