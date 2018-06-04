$(function(){
	var userHelp = "Enter the username here. ";
	var passHelp = "To get login info, contact Chris Koback. Contact information is on his resume.";
	var helpSection="";
	var helpMessage="";
	var tabComponentsLogin = ["userNameLogin","passwordLogin","loginButton"];
	//var tabComponentsLogin = ["userNameLogin","passwordLogin","loginButton","registerButton"]; 	//once registering is enabled
	var tabComponentsRegister = ["userNameRegister","emailRegister","registerButton","loginButton"];
	
	$("#userHelp").hover(
	function(){
		var thisPosition = $(this).offset();
		helpSection = "<section id='helpSection'><p id='helpMessage'></p></section>";
		$("body").append(helpSection);
		
		$("#helpSection").offset({ top: thisPosition.top-25, left: thisPosition.left+25});
		$("#helpMessage").text(userHelp);
		$("#helpSection").css("z-index","20");
		$("#helpSection").css("position","absolute");
		$("#helpSection").css({"border-weight":"1px","border-style":"solid"});
		$("#helpSection").css("display","block");
	},function(){
		$("#helpMessage").remove();
		$("#helpSection").remove();
	}
	);
	
	$(".correctTextbox").on("keydown", function(event) {
		// Cancel the default action, if needed
		event.preventDefault();
		// Number 13 is the "Enter" key on the keyboard
		if (event.keyCode === 13) {
			// Trigger the button element with a click
			$("#loginButton").click();
		}
		else if (event.keyCode == 9){
			var count= tabComponentsLogin.indexOf(event.target.id);
			if (count >= 0 ){
				if ( count == (tabComponentsLogin.length-1) ){
					$("#"+tabComponentsLogin[0]).focus();
				}else{
					$("#"+tabComponentsLogin[count+1]).focus();
				}
			}
		}
		else{
			if ( $(this).val().length >=0 ){
				var key = event.key ;
				if (key =="Backspace"){
					var textValue = $(this).val();
					$(this).val(   textValue.substr(0, textValue.length - 1 )  );
				}else if (key =="Shift"){}
				else{
					$(this).val( $(this).val() + event.key );
				}
			}else{
				$(this).val( event.key );
			}
		}
	});
	
	$("#loginButton").on("keydown", function(event) {
		event.preventDefault();
		if (event.keyCode == 9){
			var count= tabComponentsLogin.indexOf(event.target.id);
			if (count >= 0 ){
				if ( count == (tabComponentsLogin.length-1) ){
					$("#"+tabComponentsLogin[0]).focus();
				}else{
					$("#"+tabComponentsLogin[count+1]).focus();
				}
			}
		}else if (event.keyCode === 13) {
			// Trigger the button element with a click
			$("#loginButton").click();
		}
		else{}
	});
	$("#loginButton").click( function(){
		//alert ( "pass: " + $("#passwordLogin").text() );
		
		$("#forms").submit();
	});
	$(".registering").on("keydown", function(event) {
		event.preventDefault();
		if (event.keyCode == 9){
			var count= tabComponentsLogin.indexOf(event.target.id);
			if (count >= 0 ){
				if ( count == (tabComponentsLogin.length-1) ){
					$("#"+tabComponentsLogin[0]).focus();
				}else{
					$("#"+tabComponentsLogin[count+1]).focus();
				}
			}
		}else if (event.keyCode === 13) {
			// Trigger the button element with a click
			$("#registerButton").click();
		}
		else{
			if ( $(this).is('input:text') ){
				if ( $(this).val().length >=0  ){
					var key = event.key ;
					if (key =="Backspace"){
						var textValue = $(this).val();
						$(this).val(   textValue.substr(0, textValue.length - 1 )  );
					}else if (key =="Shift"){}
					else{
						$(this).val( $(this).val() + event.key );
					}
				}else{
					$(this).val( event.key );
				}
			}	
		}
	});
	$("#passHelp").hover(
	function(){
		var thisPosition = $(this).offset();
		helpSection = "<section id='helpSection'><p id='helpMessage'></p></section>";
		$("body").append(helpSection);
		
		$("#helpSection").offset({ top: thisPosition.top-25, left: thisPosition.left+25});
		$("#helpMessage").text(passHelp);
		$("#helpSection").css("z-index","20");
		$("#helpSection").css("position","absolute");
		$("#helpSection").css({"border-weight":"1px","border-style":"solid"});
		$("#helpSection").css("display","block");
	},function(){
		$("#helpMessage").remove();
		$("#helpSection").remove();
	}
	);
});