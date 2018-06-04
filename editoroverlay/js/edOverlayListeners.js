document.getElementById("urlEdit").addEventListener("change",checkEditCheckboxes);
document.getElementById("fileEdit").addEventListener("change",checkEditCheckboxes);
document.getElementById("fileInput").addEventListener("change",UpdateFileDisplay);
document.getElementById("fOpacity").addEventListener("change",UpdateOpacity);
document.getElementById("UOpacity").addEventListener("change",UpdateOpacity);

document.getElementById("urlSubmit").addEventListener("click",checkUrlFields);
document.getElementById("filesave").addEventListener("click",checkFileFields);


window.addEventListener("load",windowSetup);
window.addEventListener("click",windowClickCloseModal);
document.getElementById("colorDisplay").addEventListener("click",displayModal);
document.getElementById("modalClose").addEventListener("click",closeModal);
document.getElementById("redRange").addEventListener("input",updateColor);
document.getElementById("blueRange").addEventListener("input",updateColor);
document.getElementById("greenRange").addEventListener("input",updateColor);


document.getElementById("saveWarningYes").addEventListener("click",saveFileSetup);
document.getElementById("saveWarningNo").addEventListener("click",saveFileSetup);
