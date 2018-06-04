/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: charGenVerify.js
file description: js functions for actions on character generation page
*/



var totalChance = 0;

//outlines specific textbox in red when there is an error
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

// returns complete number at the end of a string
function getNumberFromEndOfString(value){
	var backIndex = value.length;
	do{
		backIndex -= 1;
	
	}while(! isNaN( value.substr(backIndex) )  );
	//add one so it the final result is a number
	backIndex += 1;
	return value.substr(backIndex);
}

//checks if the string contains anything that is not a letter or a number
function isValidName(value){
	var pattern1 = /[^a-zA-Z0-9]/g;	//dont allow spaces into the template name
	var result = value.match(pattern1);
	if(result){
		//there are unacceptable characters in the name 
		return (result + " is not a valid character.Please enter a name containing a-z, A-Z, and/or numbers. Spaces are not allowed.");
	}
	
	return 1;
}

/*
	Parameters: param1 : string, param2: maximum number of range (0 up to max number)	
	returns: 1 if string is a valid number within the given range, error string if not
*/
function isValidNumber(value,maxNum){
	if ( !isNaN(value)  ){
		if( (value >= 0 ) && (value <= maxNum) ){
			return 1;
		}
	}
	return ( String(value) + " is not a valid number.");
}

function isValidNumOfTemplates(value, maxNum){
	var isValid = false;
	var TemplateNum = value;
	if ( (TemplateNum > 0 )&& (isValidNumber(TemplateNum ,maxNum) == 1) ){
		isValid = true;
	}
	return isValid ;
}
//clears a section of all data/children html nodes
function clearContainer(str){
	var box = document.getElementById(String(str));

	while (box.firstChild){
	    	box.removeChild(box.firstChild);
	    	box.removeAttribute("style");
	}
}
function validateCharacterNumber(){
	var maxNum = 10;
	var errorMessageContainer = "charGenMainError";
	var errorMessageID = "characterNumberError";
	if ( isValidNumber(this.value,maxNum) == 1 ) {
		setTextbox( this, 1,"" , errorMessageID , errorMessageContainer );
	}
	else{
		var message = "That number of characters is out of range.  Please enter a number between 0 and " + String(maxNum); 
		setTextbox( this, 0,message , errorMessageID , errorMessageContainer );
	}

}
function validateTemplateName(textbox){
	var numOfTemplates = document.getElementById( "templateNum" ).value;
	var index = getNumberFromEndOfString( textbox.id)
	var template = String(textbox.value);
	var errorMessageContainer = "charGenError" + index;
	var errorMessageID = "charGenTemNameError" + index ;
	
	var message = isValidName( template );
	if ( message  == 1){
		/*var errorOccurred = true;
		//no errors
		for(var i = 0; i < numOfTemplates ; i ++){
			var templateNameIndex = "template" + i;
			if( template === document.getElementByID(templateNameIndex ).value ){
				errorOccurred = false;
				break;
			}
		}
		if (errorOccurred === false){*/
			setTextbox(textbox, 1,"", errorMessageID , errorMessageContainer );
		/*}
		else{
			message = "Not a valid template.  Please choose a template name from under \'Templates:\' .";
			setTextbox(textbox,0,message , errorMessageID , errorMessageContainer );
		}*/
	}
	else{
		//errors
		setTextbox(textbox,0,message , errorMessageID , errorMessageContainer );
	}
}

function getSum(total, num) {
    return parseFloat(total) + parseFloat(num);
}
function validateTemplateChance(){
	var numOfTemplates = document.getElementById("templateNum").value;
	var chances = new Array();
	var isSuccess = false;
	var errorMessage = " The total percent chance for all templates must equal 1 or 100."; 
	
	for(var i = 0; i < numOfTemplates; i ++){
		var tempID = "temChance" + i ;
		var tempValue = document.getElementById( tempID ).value ;
		if ( isValidNumber(tempValue,100) == 1)
		{
			chances.push( tempValue);
		}
	}
	totalChance = chances.reduce(getSum, 0);
	if (totalChance > 0){
		if( (totalChance == 1) || (totalChance == 100)  ){
			//no errors
			isSuccess = true;
		}
		else if ( totalChance < 2) {
			//must add up to 1
			isSuccess = errorMessage + " Please recheck for probability to equal 1. ";
		}
		else if ( totalChance < 100){
			//must add up to 100
			isSuccess = errorMessage + " Please recheck for probability to equal 100. ";
		}
		else{
			//error - not in range
			isSuccess = errorMessage + " Out of Range. ";
		}
	}
	else{
		isSuccess = errorMessage + "Not a valid number or some fields are blank.";
	}
	if (isSuccess == true){
		var errorMessageContainer = "charGenMainError";
		var errorMessageID = "charGenProbabilityError";	
		for(var i = 0; i < numOfTemplates; i ++){
			var tempID = "temChance" + i ;
			setTextbox( document.getElementById( tempID ), 1,"" , errorMessageID , errorMessageContainer );
		}
		return isSuccess;
	}
	else{
		var errorMessageContainer = "charGenMainError";
		var errorMessageID = "charGenProbabilityError";	
		for(var i = 0; i < numOfTemplates; i ++){
			var tempID = "temChance" + i ;
			setTextbox( document.getElementById( tempID ), 0,isSuccess , errorMessageID , errorMessageContainer );
		}
	}
}
function createBaseTemplates(){
	var box = document.getElementById( "formDyna" );
	var numOfTemplates = this.value;
	var maxNumOfBaseTemplates = 5;
	
	clearTemplateBases();
	if ( isValidNumOfTemplates( numOfTemplates,maxNumOfBaseTemplates ) == true ){
		for(var i = 0 ; i < numOfTemplates; i ++){
			var sectionSect = document.createElement('section');
			sectionSect.id = "charGenSect" + i ;
			sectionSect.name = 'section';
			sectionSect.style.border = 'outset';
			sectionSect.style.margin = "10px 0 10px 0";
			sectionSect.style.padding = "0 0 0 0";
			
			var templateNameText = document.createElement('input');
			templateNameText.type = 'text';
		    	templateNameText.size ='20';
		    	templateNameText.className = "correctTextbox";
			templateNameText.required = "true";
			templateNameText.id = "temName" +  i;
			templateNameText.name = "temName" +  i;
			templateNameText.setAttribute('onchange', "validateTemplateName(this)" );
			
			var templateChance = document.createElement('input');
			templateChance.type = 'text';
		   	templateChance.size ='3';
		    	templateChance.className = "correctTextbox";
			templateChance.required = "true";
			templateChance.id = "temChance" + i;
			templateChance.name = "temChance" + i;
			templateChance.setAttribute('onchange', "validateTemplateChance()" );
			
			var breakTag = document.createElement('br');
			
			var sectionError = document.createElement('section');
			sectionError.id = "charGenError" + i ;
			
			sectionSect.appendChild(  document.createTextNode("TemplateName: ")  );
			sectionSect.appendChild(templateNameText);
			sectionSect.appendChild(breakTag );
			sectionSect.appendChild(  document.createTextNode("Chance of Creation: ")  );
			sectionSect.appendChild(templateChance );
			sectionSect.appendChild(sectionError);
			
		    	box.appendChild(sectionSect);
		}
		document.getElementById("charGenMainError").innerHTML = "";
		document.getElementById("charGenMainError").removeAttribute("style");
	}
	else{
		document.getElementById("charGenMainError").innerHTML = "The maximum number of templates is: " + maxNumOfBaseTemplates ;
		document.getElementById("charGenMainError").style.color = "red";
	}
}
function clearTemplateBases(){
	clearContainer("formDyna");
}

function verifyForm(){
	//if there are empty fields, dont submit
	//if errors,dont submit the form
	
	
	var numOfTemplates = document.getElementById( "templateNum" ).value;
	var charTempErrorsExist = 0;
	var index = numOfTemplates -1;
	var lastTemplate = document.getElementById("temName" +  index.toString() );
	if (lastTemplate === null){
		charTempErrorsExist = 1;
		alert("There are errors on this page. Please fix them and resubmit.");
	}
	
	for(var i = 0; i <  numOfTemplates ;i ++){
		if( document.getElementById("charGenError" + i).hasChildNodes() === true ){
			charTempErrorsExist = 1;
		}
	}
	if( document.getElementById("charGenMainError").hasChildNodes() === true ){
		charTempErrorsExist = 1;
	}

	if( charTempErrorsExist === 1) {
		alert("There are errors on this page. Please fix them and resubmit.");
		return false;
	}
	else{
		var box = document.getElementById( "formDyna" );
		var templateNameText = document.createElement('input');
		templateNameText.type = 'text';
		templateNameText.size ='5';
		templateNameText.style.visibility = "hidden";
		templateNameText.name = "totalChance";
		templateNameText.id = "totalChance";
		templateNameText.value = totalChance.toString();
		box.appendChild(templateNameText);
		return true;
	}
}