<?php
include "databaseFunctions.php";

session_start();

$guestID ="";
if (isset($_SESSION['guestID'])  && !empty($_SESSION['guestID'])  ){
	$guestID = $_SESSION['guestID'];
	deleteGuestCharacters($guestID);
	deleteGuestTemplates($guestID);
	deleteGuest($guestID);
}
session_unset();
session_destroy();

header("Location:index.php");
exit;

?>