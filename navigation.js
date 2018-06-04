function getHomepageContents(){
	var homePage = "<h1 id='Title'>Homepage</h1><section id='Sect1'><h2>Projects coming soon to you</h2><ul id='futureProjects'><li id='NPCGen2'>NPC Character Generator V2</li></ul></section><section id='Sect2'><h2>Projects in Development</h2><ul id='devProjects'><li id='escapeR1'>Season 1 Escape Room<p>To be finished and able to be played come fall 2017.</p></li><li id='personalWeb'>This website<p>Please wait in anticipation for animations</p></li></ul></section><section id='Sect3'><h2>Current Projects</h2><ul id='doneProjects'><li id='NPCGen1'> <a href='https://kobackproducts.ca/genesisnpc/'>NPC Generator Version 1</a></li><li id='editorOverlay'> <a href='https://www.kobackproducts.ca/editoroverlay/login.php'>Youtube - Editor Overlay</a> </li></ul><p>For access to the Editor Overlay project, please contact Chris Koback.</p></section>";
	return homePage;
}
function aboutDeveloper(){ 
	var developerPageContents = "<h1 id = 'Title'>About the Developer</h1><section id='Sect1'><h2>Personal Information:</h2><h3>Chris Koback</h3><h5>University: </h5> University of Regina<h5>Hobbies:</h5> Programming. Hanging out with friends. Brainstorming solutions to hypothetical and realistic situations.</section>";
	return developerPageContents;
}
function researchMaterial(){  
	var researchPageContents = "<h1 id='Title'> Topics/Materials Researched or looked at</h1><section id='Sect1'><ul id='topicList'><li>Escape Room </li><li>IoT/Internet of Things<p>Lighting</p></li><li>Li-Fi/ Light Fidelity</li><li>solar panels</li><li>personal transportation<p>motorized skateboards, bikes, roller blades</p></li><ul></section>";
	return researchPageContents;
}
/*document ready - page is loaded*/
$(document).ready(function() { 
	// jQuery methods go here...
	$("#mainContent").html( getHomepageContents() );
	$("#myInput").prop('checked',true);
	var prevPage = ""; 
	var currentPage = "";
	
	$("#toolsList li").not('.emptyMessage').click(function(){
		currentPage = this.id;
		
		if ( (prevPage != "") && (prevPage != ("#"+currentPage))){
			$(prevPage).css("background-color","rgb(7,153,211)");
			$(prevPage).css("color","rgb(255,255,255)");
		}
		if (currentPage != ""){
			if (currentPage == "home"){
				$("#mainContent").html(getHomepageContents() );
			}
			else if (currentPage == "devInfo"){
				$("#mainContent").html(aboutDeveloper() );
			}else if (currentPage == "resInfo"){
				$("#mainContent").html(researchMaterial() );
			}else{}	
			var currPage = "#" + currentPage;	
			$(currPage).css("background-color","rgb(255,255,255)");
			$(currPage).css("color","rgb(7,153,211)");
			
			prevPage = currPage;
		}
	});
});
