/*
programmer ---> Christian Koback
program ---> Genesis NPC : NPC Generator & Management
Date started : September 2016
Date stopped development: April 2017
Purpose : Capstone (4th year  project)

file: charGenVerifyListeners.js
file description: event listeners for basic html elements on character generation page
*/

//create event listeners for character generation page
document.getElementById("templateNum").addEventListener("keyup", createBaseTemplates);
document.getElementById("characterNum").addEventListener("keyup", validateCharacterNumber);