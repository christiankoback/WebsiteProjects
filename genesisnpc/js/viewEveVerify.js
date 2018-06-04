window.onload = function(){
	var dropdown = document.getElementById("temCharOptions");
	populateDataForOptions(dropdown);
}

function clearContainer(str){
    var box = document.getElementById(String(str));

    while (box.firstChild){
        box.removeChild(box.firstChild);
        box.removeAttribute("style");
    }
}
function populateDataForOptions(dropdown){
	var validOptions = ["userTemCharName","idName","personalTemChar"];
	var currentOption = dropdown.value;
	
	var containerID = "viewingData";
	var container = document.getElementById(containerID);
	clearContainer(containerID);

	//faster than for loop
	
	if (currentOption == validOptions[0] ) {
		//create elements for user name & template/character name
		var userNameText = document.createElement('input');
	        userNameText.type = 'text';
	        userNameText.size = '20';
	        userNameText.className = "textbox";
	        userNameText.id = "usName";
	        userNameText.name = "usName";
	        userNameText.className = 'correctTextbox';
	        userNameText.required = "true";
	        userNameText.setAttribute('onchange',"checkName(this)" );
	        
	        var dataText = document.createElement('input');
	        dataText.type = 'text';
	        dataText.size = '20';
	        dataText.className = "textbox";
	        dataText.id = "temChar";
	        dataText.name = "temChar";
	        dataText.className = 'correctTextbox';
	        dataText.required = "true";
	        dataText.setAttribute('onchange',"checkName(this)" );
	        
	        container.appendChild(  document.createTextNode("User's Name: ")  );
		container.appendChild(userNameText);
		container.appendChild(  document.createTextNode("User's Template/Character Name: ")  );
		container.appendChild(dataText);
	}else if (currentOption == validOptions[1] ) {
		//create element for template/character ID name
		var dataText = document.createElement('input');
	        dataText.type = 'text';
	        dataText.size = '20';
	        dataText.className = "textbox";
	        dataText.id = "temCharID";
	        dataText.name = "temCharID";
	        dataText.className = 'correctTextbox';
	        dataText.required = "true";
	        dataText.setAttribute('onchange',"checkName(this)" );
		
		container.appendChild(  document.createTextNode("Template's/Character's ID: ")  );
		container.appendChild(dataText);
	}else if (currentOption == validOptions[2] ) {
		//create element for personal template/character
		var dataText = document.createElement('input');
	        dataText.type = 'text';
	        dataText.size = '20';
	        dataText.className = "textbox";
	        dataText.id = "persTemChar";
	        dataText.name = "persTemChar";
	        dataText.className = 'correctTextbox';
	        dataText.required = "true";
	        dataText.setAttribute('onchange',"checkName(this)" );
	        
	        container.appendChild(  document.createTextNode("Template's/Character's Name: ")  );
		container.appendChild(dataText);
	}
	else{}
}
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
function isValidString(value){
    var pattern1 = /[^a-zA-Z0-9. ]/g;	//
    var result = value.match(pattern1);

    if(result){
        //there are unacceptable characters in the name
        return (result + " is not a valid character.Please enter a name containing a-z, A-Z, and/or numbers.");
    }

    return true;
}
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



function checkName(textBox){
	var text = textBox.value;
	var isValid = isValidName(text);
	var errorMessageContainer = "errorsViewing";		//gotten from php file
    	var errorMessageID = "invalidNameError";
    	
	if (isValid !== true){
    		setTextbox(this,0,isValid, errorMessageID , errorMessageContainer ); 
	}
	else{
		setTextbox(this, 1,"", errorMessageID , errorMessageContainer );
	}
}
