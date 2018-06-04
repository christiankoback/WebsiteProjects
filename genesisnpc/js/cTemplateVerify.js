/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: cTemplateVerify.js
file description: js for all events/html elements on the template creation page
*/


function clearContainer(str){
    var box = document.getElementById(String(str));

    while (box.firstChild){
        box.removeChild(box.firstChild);
        box.removeAttribute("style");
    }
}
/*
	dynamically create each section data based on #ofSections wanted 
	---> adds javascript events as attributes for better organization

*/
function showSections(str1,sideOfSections, str2){
    var maxSectionNum = 10;
    var numOfSections = parseInt(str1, 10);
    var startingPoint = parseInt(str2, 10);
    var box = document.getElementById(String(sideOfSections));
    if( (numOfSections <= maxSectionNum  ) && (numOfSections > 0) ){

        for (i = startingPoint; i < numOfSections ; i += 2){
            var sectionSect = document.createElement('section');
            sectionSect.id = "section" + i ;
            sectionSect.name = 'propSection';
            sectionSect.className = "templateSection";
            sectionSect.style.border = 'outset';
            sectionSect.style.margin = "10px 0 10px 0";
            sectionSect.style.padding = "0 0 0 0";

            var propertyNum = document.createElement('input');
            propertyNum.type = 'text';
            propertyNum.size='5';
            propertyNum.className = "correctTextbox";
            propertyNum.id = "propertyNum" + i;
            propertyNum.name = "propertyNum"+ i;
            propertyNum.required = "true";

            var sectName = document.createElement('input');
            sectName.type = 'text';
            sectName.size='5';
            sectName.className = "correctTextbox";
            sectName.id = "sectionName" + i;
            sectName.name = "sectionName"+ i;
            sectName.required = "true";
            sectName.setAttribute('onchange', "checkSectionName(this)");

            var errorSect = document.createElement('section');
            errorSect.id = "errorContainer" + i ;
            errorSect.name = "errorContainer" + i ;

            var propertySect = document.createElement('section');
            propertySect.id = "propContainer" + i ;
            propertySect.name = "propContainer" + i ;

            var eventKeyUp = "generateSectionData(this.id,'" + propertySect.id  + "')" ;
            propertyNum.setAttribute('onchange', eventKeyUp );
            //var eventKeyDown = "clearContainer('" +  propertySect.id   + "')" ;
            // propertyNum.setAttribute('onkeydown', eventKeyDown );

	    var breakTag = document.createElement('br');

            sectionSect.innerHTML += "Section Name: ";
            sectionSect.appendChild(sectName);
            sectionSect.innerHTML += "Number of Section Properties: ";
            sectionSect.appendChild(propertyNum);
            sectionSect.appendChild(breakTag );
            sectionSect.innerHTML += "Type of Section Input: ";


             var dropdownID = "sectionType"+i;
             
             var dropdownTypes = document.createElement("SELECT");
             dropdownTypes.id = dropdownID;
             dropdownTypes.setAttribute("name",dropdownID );
             var dropdownEvent = "generateSectionData(this.id,'" + propertySect.id  + "')" ;
             dropdownTypes.setAttribute('onchange', dropdownEvent );
             sectionSect.appendChild(dropdownTypes);

            sectionSect.appendChild(errorSect);
            sectionSect.appendChild(propertySect);

            box.appendChild(sectionSect);
            
            var options = ["basic","description"];
            for( var optionIndex = 0; optionIndex < options.length;optionIndex++){
                 var option = document.createElement("option");
                 option.value = options[optionIndex];
                 option.text = options[optionIndex];

                 document.getElementById(dropdownID).appendChild(option);
             } 
        }
    }
}
/*
	create all description necessary elements and error checking functions
*/
function addDescription(numOfProperties, box){
    var container = document.getElementById(String(box));
	//window.alert("creating description"+ " properties: "+ numOfProperties+" box: "+ box);  //testing

    for( var i = 0; i < numOfProperties; i++){
	var propertySect = document.createElement('section');
        propertySect.id = container +"." + i ;
        propertySect.name = 'propertySect';
        propertySect.className = "propSection";
        
        var propertyName = document.createElement('input');
        propertyName.type = 'text';
        propertyName.size='20';
        propertyName.className = "textbox";
        propertyName.id = box + "udesc" + i ;
        propertyName.name = box + "udesc" + i ;
        propertyName.className = 'correctTextbox';
        propertyName.required = "true";
        propertyName.setAttribute('onchange',"checkDescription(this)" );
        
        var breakTag = document.createElement('br');
        
        var textArea = document.createElement("textarea");
        textArea.maxlength = "255";
        textArea.rows = "10";
        textArea.cols = "26";
        textArea.id = box + "udesctext" + i;
        textArea.name = box + "udesctext" + i;
        textArea.placeholder = "This is a text area. Please type a description here. Maxiumum length of saved description is 255 characters.";
        textArea.required = "true";
        textArea.setAttribute('onchange',"checkTextArea(this)" );
	
	var errorSect = document.createElement('section');
        errorSect.id = box + "errorSect" + i;
        
        propertySect.appendChild(  document.createTextNode("Description Name: ")  );
        propertySect.appendChild(propertyName);
        propertySect.appendChild(breakTag );
        propertySect.appendChild(textArea);
        propertySect.appendChild(breakTag );
        propertySect.appendChild(errorSect);
        
        container.appendChild(propertySect);
    }
}
/*
	create table for skill adding --> possible future feature
*/
function addTable(numOfProperties, box){
    //possible for future use
}
/*
	create all property necessary elements and error checking functions
*/
function addProperties(numOfProperties, box){
    var container = document.getElementById(String(box));

	//window.alert("creating properties"+ " properties: "+ numOfProperties+" box: "+ box); //testing
    for( var i = 0; i < numOfProperties; i++){

        var propertySect = document.createElement('section');
        propertySect.id = container +"." + i ;
        propertySect.name = 'propertySect';
        propertySect.className = "propSection";

        var propertyBase = String(box) ;
        var propertyInputId = propertyBase + "uprop" + i ;
        var lowRangeInputId = propertyBase + "ulowR" + i;
        var highRangeInputId = propertyBase + "uhigh" + i;

        var breakTag = document.createElement('br');
        var propertyName = document.createElement('input');
        propertyName.type = 'text';
        propertyName.size='20';
        propertyName.className = "textbox";
        propertyName.id = propertyInputId;
        propertyName.name = propertyInputId;
        propertyName.className = 'correctTextbox';
        propertyName.required = "true";
        propertyName.setAttribute('onchange',"checkPropertyName(this)" );

        var lowRange = document.createElement('input');
        lowRange.type = 'text';
        lowRange.size='5';
        lowRange.className = "textbox";
        lowRange.id = lowRangeInputId;
        lowRange.name = lowRangeInputId;
        lowRange.className = 'correctTextbox';
        lowRange.required = "true";
        lowRange.setAttribute('onchange', "checkPropertyIntegers(this)" );


        var highRange = document.createElement('input');
        highRange.type = 'text';
        highRange.size='5';
        highRange.className = "textbox";
        highRange.id = highRangeInputId;
        highRange.name = highRangeInputId;
        highRange.className = 'correctTextbox';
        highRange.required = "true";
        highRange.setAttribute('onchange', "checkPropertyIntegers(this)" );



        var errorSect = document.createElement('section');
        errorSect.id = propertyBase + "errorSect" + i;

        propertySect.appendChild(  document.createTextNode("Property: ")  );
        propertySect.appendChild(propertyName);
        propertySect.appendChild(breakTag );
        propertySect.appendChild(  document.createTextNode("Range: from - ") );
        propertySect.appendChild(lowRange);
        propertySect.appendChild(  document.createTextNode(" to - ") );
        propertySect.appendChild(highRange);

        container.appendChild(propertySect);
        container.appendChild(errorSect);
    }
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

    return 1;
}
function isValidString(value){
    var pattern1 = /[^a-zA-Z0-9. ]/g;	//
    var result = value.match(pattern1);

    if(result){
        //there are unacceptable characters in the name
        return (result + " is not a valid character.Please enter a name containing a-z, A-Z, and/or numbers.");
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
    if ( String(value) === "NaN"){
    	value = "<blank>";
    }
    return ( value + " is not a valid number.");
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

//create function to verify valid string for templateName field
function isValidTemplateName(){
    var valueChecked = isValidName(this.value);
    var errorMessageContainer = "templateTopError";
    var errorMessageID = "templateNameError";
    var message = "Template Name: " + valueChecked;

    if ( valueChecked == 1 ){
        setTextbox(this, 1,"", errorMessageID , errorMessageContainer );
    }
    else{
        setTextbox(this,0,message , errorMessageID , errorMessageContainer );
    }
}

//create function to verify section # is valid number
function isValidSectionAmount(){
    var sectionAmount = this.value;
    var errorMessageContainer = "templateTopError";
    var errorMessageID = "sectionAmountError";

    var errorMessage = isValidNumber(sectionAmount, 10);

    //number is valid within the range
    if ( errorMessage == 1){
        setTextbox(this, 1,"" , errorMessageID , errorMessageContainer );
    }
    else{
        var message = "Section Amount: " + errorMessage + " Maximum amount of sections is 10.";
        setTextbox(this,0,message , errorMessageID , errorMessageContainer );
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




//create generic function for verifying valid name for sections

function checkSectionName(textbox){
    var sectionIndex = getNumberFromEndOfString(textbox.id) ;
    var errorMessageID = "errSectName" + sectionIndex;
    var errorMessageContainer = "errorContainer" + sectionIndex;
    var valueChecked = isValidName(textbox.value);


    if ( valueChecked == 1 ){
        setTextbox(textbox, 1,"", errorMessageID , errorMessageContainer );
    }
    else{
        var message = "Section Name: " + valueChecked;
        setTextbox(textbox,0,message , errorMessageID , errorMessageContainer );
    }
}

function isValidSectionPropertyAmount(textboxID, textboxValue){
    var maxProperties = 5;
    var sectionIndex = getNumberFromEndOfString(textboxID) ;
    var errorMessageID = "errSectPropAmount" + sectionIndex;
    var errorMessageContainer = "errorContainer" + sectionIndex;
    var errorMessage = isValidNumber(textboxValue, maxProperties);

    //number is valid within the range
    if ( errorMessage == 1){
        setTextbox(this, 1,"" , errorMessageID , errorMessageContainer );
        return 1;
    }
    else{
        var message = errorMessage + " Maximum amount of properties per section is " + maxProperties +".";
        setTextbox(this,0,message , errorMessageID , errorMessageContainer );
        return 0;
    }
}

/*
	generate the necessary data per section based on the sectioin type chosen
*/
function generateSectionData(textboxID,sectionID){
    var maxProperties = 5;
    var sectionIndex = getNumberFromEndOfString(textboxID) ;
    
    //get generic section number
    var sectionNumber = "propertyNum" + sectionIndex;
    var sectionAmount = parseInt(  document.getElementById(sectionNumber).value  );

    //get generic section type
    var sectionType = "sectionType" + sectionIndex;
    var sectionTypeValue = document.getElementById(sectionType).value
    
    //window.alert("sectionDropDownValue: " +sectionTypeValue + "  sectionNum: " + sectionAmount  + "  sect Id: " +sectionNumber  );
    
    clearContainer(sectionID);

    //if basic, create properties
    
    isValidSectionPropertyAmount(sectionNumber, sectionAmount )
    if ( isValidSectionPropertyAmount(sectionNumber, sectionAmount ) === 1){
	    //window.alert("valid section amount");   //testing
		if (sectionTypeValue == "basic"){
			//window.alert("create basic");   //testing
			addProperties(sectionAmount, sectionID);
	    }
	    else if (sectionTypeValue == "description"){
	        //if description, create textarea
	        //window.alert("create description");   //testing
	        addDescription(sectionAmount, sectionID);
	    }
	    else{}
	    
    }
}
//create generic function to verify property integer values
function checkDescription(textbox){
	var valueChecked = isValidName(textbox.value);
	var errorMessageContainer = (textbox.id).substr(0, (textbox.id).indexOf("u") ) + "errorSect" + getNumberFromEndOfString(textbox.id);
	var errorMessageID = errorMessageContainer + "description";
	
	if ( valueChecked == 1 ){
		setTextbox(textbox, 1,"", errorMessageID , errorMessageContainer );
	}
	else{
		var message = "Description Name: " + valueChecked;
		setTextbox(textbox,0,message , errorMessageID , errorMessageContainer );
	}
}
function checkPropertyName(textbox){
    var valueChecked = isValidName(textbox.value);
    var propertyBase = "section";

    //create an id corresponding to the property id
    var errorMessageContainer = (textbox.id).substr(0, (textbox.id).indexOf("u") ) + "errorSect" + getNumberFromEndOfString(textbox.id);
    var errorMessageID = errorMessageContainer + "Name";


    if ( valueChecked == 1 ){
        setTextbox(textbox, 1,"", errorMessageID , errorMessageContainer );
    }
    else{
        var message = "Property Name: " + valueChecked;
        setTextbox(textbox,0,message , errorMessageID , errorMessageContainer );
    }
}
function checkTextArea(textbox){
    var valueChecked = isValidString(textbox.value);
    var propertyBase = "section";

    var errorMessageContainer = (textbox.id).substr(0, (textbox.id).indexOf("u") ) + "errorSect" + getNumberFromEndOfString(textbox.id);

    var errorMessageID = errorMessageContainer + "Name";


    if ( valueChecked == 1 ){
        setTextbox(textbox, 1,"", errorMessageID , errorMessageContainer );
    }
    else{
        var message = "Property Name: " + valueChecked;
        setTextbox(textbox,0,valueChecked , errorMessageID , errorMessageContainer );
    }
    if( ( (textbox.value).length > 255) || ( (textbox.value).length < 0) ){
    	var message = "Please enter a descripton with a length between 0 and 255 characters.";
        setTextbox(textbox,0,valueChecked , errorMessageID , errorMessageContainer );
    }
    else{
    	setTextbox(textbox, 1,"", errorMessageID , errorMessageContainer );
    }
}
function checkPropertyIntegers(textbox){
    var rangeIntegerMax = 255;
    var currentID = textbox.id;
    var uniqueSplit = currentID.indexOf("u") ;
    var propertyIndex = getNumberFromEndOfString(currentID);

    //create an id corresponding to the property id
    var errorMessageContainer = currentID.substr(0, uniqueSplit) + "errorSect" + propertyIndex ;
    var errorMessageID = errorMessageContainer + currentID.substr(uniqueSplit, uniqueSplit + 4 ) ;
    var lowRange = 0;
    var highRange = 0;
    var errorMessageID2 = "";
    var documentID2 = "";

    if (  currentID.substring(uniqueSplit+1, uniqueSplit+5)  == "lowR"){
        lowRange  = parseInt( textbox.value );
        errorMessageID2 = errorMessageID.replace("lowR","high");
        documentID2 = currentID.replace("lowR","high");
        highRange = parseInt(   document.getElementById(documentID2).value  );
    }
    else{
        errorMessageID2 = errorMessageID.replace("high","lowR");
        documentID2 = currentID.replace("high","lowR");
        lowRange = parseInt(   document.getElementById(documentID2).value  );
        highRange = parseInt(textbox.value);
    }
    if( (isValidNumber(lowRange,rangeIntegerMax )==1 )&& (isValidNumber(highRange,rangeIntegerMax )==1) ){
        if (lowRange >= highRange){
            var message = "error: low range is greater than or is equal to high range.";
            setTextbox(textbox,0,message , errorMessageID , errorMessageContainer );
            setTextbox(document.getElementById(documentID2),0,"", errorMessageID2, errorMessageContainer );

        }
        else{
            setTextbox(textbox, 1,"" , errorMessageID , errorMessageContainer );
            setTextbox(document.getElementById(documentID2),1,"", errorMessageID2, errorMessageContainer );
        }
    }
    else{
        var message =" Please ensure the range values are integers from 0 to " + rangeIntegerMax +".";
        setTextbox(textbox,0,message , errorMessageID , errorMessageContainer );
        setTextbox(document.getElementById(documentID2),0,"", errorMessageID2, errorMessageContainer );
    }
}

function verifyForm(){
    //if there are empty fields, dont submit
    //if errors,dont submit the form
    var sectionAmount = document.getElementById("sectionAmount").value;
    var sectionErrors = 0;		//section errors
    var propertyErrors = 0;		//property errors
    var templateErrors = 0;

    if( document.getElementById("templateTopError").childNodes.length > 1){   //template errors
        templateErrors = 1;
    }
    var length = document.getElementById("templateTopError").childNodes.length;


    for(var i = 0; i <  sectionAmount;i ++){
        if ( document.getElementById("errorContainer" + i).hasChildNodes() === true)
        {
            sectionErrors = 1;
        }
        var propertyAmount = document.getElementById("propertyNum"+i).value;
        for(var j = 0; j < propertyAmount; j++){
            if ( document.getElementById("propContainer" + i + "errorSect" + j).hasChildNodes() === true)
            {
                propertyErrors = 1;
            }
        }
    }

    if( ( templateErrors === 1) || (sectionErrors === 1) || (propertyErrors === 1) ){
        alert("There are errors on this page. Please fix them and resubmit.");
        return false;
    }
    else{
        return true;
    }
}