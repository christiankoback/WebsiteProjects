/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: indexJS.js
file description: error checking for login/index page
*/
function loginWarning(){
	window.alert("WARNING : if you login as a guest, your characters will be deleted immediately after you log out or session times out.");
}
/*
function setTextbox(textbox,isValueCorrect,errorMessage, errorMessageID, errorMessageContainer){
	if (isValueCorrect == 0 ){
		var errorText = document.getElementById(errorMessageID);
		if ( errorText === null){
			var errorMessageP = document.createElement('p');
			errorMessageP.id = errorMessageID;
			errorMessageP.innerHTML = errorMessage; 
			errorMessageP.style.color = "red";
			document.getElementById(errorMessageContainer).appendChild(errorMessageP); 
		}
		else{
			errorText.innerHTML = errorMessage;
		}
		textbox.className = 'errorTextbox';
	}
	else{
		var errorMessageP = document.getElementById(errorMessageID);
		if (document.getElementById(errorMessageID) !== null){
			document.getElementById(errorMessageContainer).removeChild(errorMessageP); 
		}
		textbox.className = 'correctTextbox';
	}
}

function verifyLogin(){
alert("id:" + this.id );

	var userBase = "userName";
	var pattern1 = /[^a-zA-Z0-9]/g;	
	var result = (this.value).match(pattern1);
	var errorContainer = (this.id).substring(userBase.length) +"Error";
	var errorMessageID = this.id + "error" ;
	
	alert("id:" + this.id + ",  container:" + errorContainer );
	
	if(result){
		//there are unacceptable characters in the name 
		var errorMessage = result + " is not a valid character.Please enter a name containing a-z, A-Z, and/or numbers.";
		setTextbox(this,0,errorMessage, errorMessageID, errorContainer);
	}
	else{
		setTextbox(this,1,"", errorMessageID, errorContainer);
	}
	
}

function verifyPass(){
	alert("id:" + this.id );
	var pass = document.getElementById("passwordRegister").value;
	var errorContainer = "RegisterError";
	var errorMessageID = this.id + "error" ;
	
	if (pass !== this.value){
		var errorMessage = "The passwords do not match. Please re-enter them.";
		setTextbox(this,0,errorMessage, errorMessageID, errorContainer);
	}
	else{
		setTextbox(this,1,"", errorMessageID, errorContainer);
	}
	
	alert("id:" + this.id + ",  container:" + errorContainer );
}

function verifyEmail(){
alert("id:" + this.id );

	var value = this.value;
	var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
	var errorContainer = "RegisterError";
	var errorMessageID = this.id + "error" ;
	alert("id:" + this.id + ",  container:" + errorContainer );
	
	
	if (value == '' || !re.test(value))
	{
	    var errorMessage ='Please enter a valid email address.';
	    setTextbox(this,0,errorMessage, errorMessageID, errorContainer);
	}
	else{
		setTextbox(this,1,"", errorMessageID, errorContainer);
	}
	
}
*/