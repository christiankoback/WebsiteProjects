<?php
/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: basicSetup.php
file description:  Provides website-universal styles
*/

/* universal header*/
function createHeader($title, $userID, $guestID){
	?><section id="header">
	<h1 id="topLeft"><?php echo $title;?></h1><section id="topRight"><p><?php if ($userID != ""){echo $userID;}else{echo "guest:". $guestID;} ?></p><button id="logoutButton" type="button" onclick="window.location='logout.php'" > Log out</button>  </section>
	</section>
	<?php
}
/* universal footer*/
function createFooter(){
	$footer = "<footer><span id='siteseal'><script async type='text/javascript' src='https://seal.godaddy.com/getSeal?sealID=hQfcyySwC3UTNieTzQ2OlN6OXHtQcuMQW5yZB1uTyEeWHHVjhMEfPEmb9JPo'></script></span></footer>";
	echo $footer;
}
function setAccordionjavascript(){
?>
	<script>
	var acc = document.getElementsByClassName("accordion");
	var i;
	
	for (i = 0; i < acc.length; i++) {
	    acc[i].onclick = function(){
	        this.classList.toggle("active");
	        this.nextElementSibling.classList.toggle("show");
	    }
	}
	</script>
	<?php
}
function displayTemplatesAccordion($user, $isGuest){
	$templateList = getCharacterTemplateList($user,$isGuest);

	if (count($templateList) > 0) {
		//person has multiple templates created, show most recent 5 in accordion
		$i = 0;
		echo "<button class='accordion' id='TemplateList'>Quick Templates</button>";
		if ( !empty($templateList)  ){
			//echo "template is not empty : part2";
			
			echo "<div class='panel'>";
			echo "<form method='post' action='characterDisplay.php'>";
			foreach($templateList as $template){
				if ($i >= 5  ){
					break;
				}
				echo "<input type='submit' id='".$template."' name='viewTemplate' class='templates' value='".$template."' ><br>";
				$i++;
			}
			echo "<input type='hidden' name='viewingUser' id='viewingUser' value='". $user ."' />";
			echo "</form>"; 
			if( count($templateList) >= 5){
				echo "<button class='accordion' id='CompleteTemplateList'>Other Templates</button>";
				echo "<div class='panel'>";
				echo "<form method='post' action='characterDisplay.php'>";
				
				 for ($i = 5; $i < count($templateList);$i++){
					echo "<input type='submit' id='".$templateList[$i]."' name='viewTemplate' class='templates' value='".$templateList[$i]."' ><br>";
				}
				echo "<input type='hidden' name='viewingUser' id='viewingUser' value='". $user ."' />";
				echo "</form>"; 
				echo "</div>";
			}
			echo "</div>";
			 
			

		}
	}else{ //person has no templates created, do nothing
	}
	
	setAccordionjavascript();
}
function displayCharacterAccordion($user, $isGuest){
	$charList = getCharacterList($user,$isGuest);

	if( (count($charList) > 0)&& !empty($charList) ){
		$i = 0;
		echo "<button class='accordion' id='CharacterList'>Quick Characters</button>";
		echo "<div class='panel'>";
		echo "<form method='post' action='characterDisplay.php'>";
		foreach($charList as $char){
			if ($i >= 5  ){
				break;
			}
			echo "<input type='submit' id='".$char."' name='viewChar' class='characters' value='".$char."' ><br>";
			$i++;
		}
		echo "<input type='hidden' name='viewingUser' id='viewingUser' value='". $user ."' />";
		echo "</form>"; 
		
		
		if( count($charList) >= 5){
			echo "<button class='accordion' id='CompleteCharacterList'>Other Characters</button>";
			echo "<div class='panel'>";
			echo "<form method='post' action='characterDisplay.php'>";
			
			 for ($i = 5; $i < count($charList);$i++){
				echo "<input type='submit' id='".$charList[$i]."' name='viewChar' class='characters' value='".$charList[$i]."' ><br>";
			}
			echo "<input type='hidden' name='viewingUser' id='viewingUser' value='". $user ."' />";
			echo "</form>"; 
			echo "</div>";
		}
		
		echo "</div>";
	}else{ //person has no templates created, do nothing
	}
	setAccordionjavascript();
}
?> 