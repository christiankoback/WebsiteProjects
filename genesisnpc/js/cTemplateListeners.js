/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: cTemplateListeners.js
file description: event listeners for basic html elements on template creation page
*/

//create event listeners for template page
document.getElementById("templateName").addEventListener("keyup", isValidTemplateName );
document.getElementById("sectionAmount").addEventListener("keyup", isValidSectionAmount );


