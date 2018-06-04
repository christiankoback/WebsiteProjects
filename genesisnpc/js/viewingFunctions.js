/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: viewingFunctions.js
file description: all js for viewing display 
	---> error checking of modified templates/characters
*/

/*
	Parameters: param1 : string	
	returns: 1 if param is string of valid characters, sends error string if invalid character is found
*/
function isValidName(value){
	var pattern1 = /[^a-zA-Z0-9]/g;	//dont allow spaces into the template name
	var result = value.match(pattern1);
	
	if(result){
		//there are unacceptable characters in the name 
		return (result + " is not a valid character.Please enter a name containing a-z, A-Z, and/or numbers. Spaces are not allowed.");
	}
	
	return true;
}
/*
	Parameters: param1 : string, param2: maximum number of range (0 up to max number)	
	returns: 1 if string is a valid number within the given range, error string if not
*/
function isValidNumber(value,maxNum){
	if ( !isNaN(value)  ){
		if( (value >= 0 ) && (value <= maxNum) ){
			return true;
		}
	}
	return ( String(value) + " is not a valid number.");
}
//create generic function to check value is an integer -> isNan(value) <= return true when not an integer

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
function getNumberFromEndOfString(value){
	var backIndex = value.length;
	do{
		backIndex -= 1;
	
	}while(! isNaN( value.substr(backIndex) )  );
	//add one so it the final result is a number
	backIndex += 1;
	return value.substr(backIndex);
}
function checkName(textbox){
	var input = textbox.value;
	var result = isValidName(value);
	var index = getNumberFromEndOfString(textbox.id) ; 
	
	 if (result != true){
	 	setTextbox(textbox,0,result, "newError"+index, "errorMessages");
	 }
	 else{
	 	setTextbox(textbox,1,"", "newError"+index, errorMessageContainer);
	 }
}
function checkNumber(textbox){
	var input = textbox.value;
	var result = isValidNumber(value);
	var index = getNumberFromEndOfString(textbox.id) ; 
	
	 if (result != true){
	 	setTextbox(textbox,0,result, "newError"+index, "errorMessages");
	 }
	 else{
	 	setTextbox(textbox,1,"", "newError"+index, errorMessageContainer);
	 }
}


function verifyTemplateForm(){
	var errorsOccurred = document.getElementById("errorMessages");
	if (errorsOccurred.hasChildNodes() ){
		//errors occurred
		return false;
	}
	else{
		return true;
	}
}
function verifyCharacterForm(){
	var errorsOccurred = document.getElementById("errorMessages");
	if (errorsOccurred.hasChildNodes() ){
		//errors occurred
		return false;
	}
	else{
		return true;
	}
}