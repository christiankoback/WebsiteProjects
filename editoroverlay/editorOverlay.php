<?php

error_reporting(E_ALL);

include "databaseFunctions.php";

// get file and location from user <- $newFileName
//$newFileName = ;
// get contents of the editor and save in the file
//$newFileContent = ;
/*
if (file_put_contents($newFileName, $newFileContent) !== false) {
    echo "File created (" . basename($newFileName) . ")";
} else {
    echo "Cannot create file (" . basename($newFileName) . ")";
}
*/
session_start();
$zIndex = 0;
if ( !isset($_SESSION["user"])  ){	//new session
	header("Location: login.php");
	exit;
}else{
	$urlEdit = 0;
	$messages="";
	$fileEdit= $urlEdit-1;
	$_SESSION["pastFile"] = "testing.txt";
	$fileEditFlag = false;	//true if file save is pressed
	if (isset($_POST["urlSubmit"])){
		if (isset($_POST["urlEdit"] )  ){
		
		}
	}
	else if (isset($_POST["saveFile"])){
		$messages = $messages . "fileSave button was pressed";
		if (isset($_POST["fileEdit"] )  ){
		/*
			$fileEditFlag = true;
			$prevFile = $_SESSION["pastFile"];
			if (  $_POST["saveFile"] == "saveFile" ){
				$messages = $messages . "/n saveFile is set";
				$html = file_get_html('editorOverlay.php');
				$code = $html->find('#frame2', 0);
				$content = $code->outertext;
				
				// store it to file
				file_put_contents($prevFile, $content);
				
				$tempString= "Content-Disposition: attachment; filename=" . $prevFile;
				$messages = $messages . "/n tempString is:" . $tempString;
				
				// send content to browser as a file
				header("Content-Type: application/force-download");
				header("Content-Length: " . filesize($prevFile));
				header($tempString); // is a virtual filename
				readfile($prevFile);
				$messages = $messages . "/n done file download";
				unlink($prevFile);
			}
			
			*/
			/*
			$target_dir = "tempUpload/";
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$uploadOk=1	
			$_FILES['fileInput']['tmp_name']
			if ($_FILES['fileInput']['type'] == 'text/plain'){
				$uploadOk=1;
				// Check file size - 500KB
				if ($_FILES["fileInput"]["size"] > 500000) {
				    echo "Sorry, your file is too large.";
				    $uploadOk=0;
				}
			
			}else{
				$uploadOk=0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
			    echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
			    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			        $_SESSION["pastFile"] =  basename($_FILES["fileToUpload"]["name"]);
			    } else {
			        echo "Sorry, there was an error uploading your file.";
			    }
			}
			
			
			
			
			
			
			if ($prevFile && $_POST["frame2"]  ){
			
			
			
			
			}
			$_SESSION["pastFile"] = $fileEdit;
			$fileEditFlag = true;
			$myfile = fopen($fileEdit, "r+");
			fwrite($prevFile, );
			fwrite($myfile, $txt);
fclose($myfile);
			
			*/
		}
	}else{}
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="css/editorTheme.css" type="text/css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://fastcdn.org/FileSaver.js/1.1.20151003/FileSaver.js" ></script>
		<script type = "text/javascript" src = "js/overlayJquery.js"></script>
		<title>Editor Overlay</title>
	</head>
	<body>
		<section id="logobar">
			<image id="logo" src="pics/ringOfFire.jpg" alt="Fire Logo"/>
			<button id="logoutButton" type="button"onclick="window.location='logout.php'" > Log out</button><h1 id="title">Editor Overlay</h1>
		</section>
		<section id="formSection"> 
			<form id="forms" method="post" enctype="multipart/form-data">
				<fieldset id="urlfields">
					<fieldset id="urlSection1">
						<fieldset id="url1fieldset" >URL:<input type="text" name="url" id="url"></fieldset>
						<fieldset id="url1fieldset1" >URL Opacity:<input type="number" name="UOpacity" id="UOpacity" size="4" step="0.1" min="0" max="1" value="0.2" required /></fieldset>
					</fieldset>
					<fieldset id="urlSection2">
						<fieldset id="url1fieldset2">Edit URL:<input type="checkbox" id="urlEdit" name="urlEdit" value="enabled"> </fieldset>
					<fieldset id="urlsave"><button id="urlSubmit" type="button" name="urlSubmit">Get URL Content</button></fieldset>
					</fieldset>
				</fieldset>
				<fieldset id="filefields">
					 <fieldset id="fileSection1">
						  <fieldset id="fileOpacity" >File Opacity:<input type="text" name="fOpacity" id="fOpacity" step="0.1" min="0" max="1"  size="4" value="0.7" required></fieldset>
						  <fieldset id="fileLocation">Edit:
							<input type="file" name="fileInput" id="fileInput" class="inputfile" />
							<label for="fileInput" id="fileLabel"></label>
						  </fieldset>
					 </fieldset>
					 <fieldset id="fileSection2">
						<fieldset id="fileCheck">Edit File:<input id="fileEdit" type="checkbox" name="fileEdit" value="enabled"></fieldset>
						<fieldset id="fileSubmit"><button type="button" id="filesave" name="filesave" >Open/Save File</button></fieldset>
						<button id="colorDisplay" type="button">Choose Font Color</button>
						<input type="hidden" name="saveFile" id="saveFile">
					</fieldset>
				</fieldset>
			</form>
		</section>
		<div id="colorPicker" class="modal">
		  <!-- Modal content -->
		  <div class="modal-content" id="modal-content">
		    <span class="close" id="modalClose">&times;</span>
			<div id="redSlider">
				<h3>Red</h3>
				<input type="range" min="0" max="255" value="0" id="redRange" />
				<div class="colorValueAlign"><input type="text" id="redValue" size="3" readonly></input></div>
			</div>
			<div id="blueSlider">
				<h3>Blue</h3>
				<input type="range" min="0" max="255" value="0" id="blueRange" />
				<div class="colorValueAlign"><input type="text" id="blueValue" size="3" readonly></input></div>
			</div>
			<div id="greenSlider">
				<h3>Green</h3>
				<input type="range" min="0" max="255" value="0" id="greenRange" />
				<div class="colorValueAlign"><input type="text" id="greenValue" size="3" readonly></input></div>
			</div>
		  </div>
		</div>
		<div id="fileSaveWarning" class="modal">
		  <!-- Modal content -->
		  <div class="modal-content" id="fileSaveWarningContent">
		    	<p> Do you want to save the typed content to the previous file before opening a new file?</p>
		    	<button id="saveWarningYes" type="button">YES</button>
		    	<button id="saveWarningNo" type="button">NO</button>
		  </div>
		</div>
		
		<section id="frames">
			<!--website frame -->		
			 <iframe id="frame1" src="https://www.youtube.com/embed/videoseries?list=PLx0sYbCqOb8TBPRdmBHs5Iftvv9TPboYG&autoplay=1" frameborder="0" allow="autoplay; encrypted-media" sandbox="allow-scripts allow-same-origin allow-presentation"> 
			 </iframe>
			<div contentEditable="true" id="frame2" >
				<?php 
				if ($fileEditFlag == true){
					$fileEditFlag = false;
					
					echo fread($myfile,filesize($fileEdit));
					
					
					
				}
				
				?>
			</div>
			<div contentEditable="false" id="invisibleFrame" >
			</div>
			<iframe id="downloadIframe"><a id="tempLink"></a></iframe>
		</section>
	</body>
	<!--  <script type = "text/javascript" src = "js/edOverlayListeners.js"></script>  -->
</html>


