/*
parse youtube URL to get embed code

change z-index of frames depending on which checkbox is checked
Ensure a checkbox is checked by default

*/

function checkEditCheckboxes(event){
	var urlEdit = "urlEdit";
	if (event.target.checked == true){
		document.getElementById("invisibleFrame").style.zIndex="-10";
		if (event.target.id == urlEdit){
			document.getElementById("fileEdit").checked = false;
			document.getElementById("frame1").style.zIndex="0";
			document.getElementById("frame2").style.zIndex="-1";
			
		}else{
			document.getElementById("urlEdit").checked = false;
			document.getElementById("frame1").style.zIndex="-1";
			document.getElementById("frame2").style.zIndex="0";
		}
	}else{
		document.getElementById("invisibleFrame").style.zIndex="1";
	}
}
function UpdateFileDisplay(){
	var fileInput = document.getElementById('fileInput');
	var fileDisplayArea = document.getElementById('frame2');

	fileInput.addEventListener('change', function(e) {
		var file = fileInput.files[0];
		var textType = /text.*/;

		if (file.type.match(textType)) {
			var reader = new FileReader();

			reader.onload = function(e) {
				fileDisplayArea.innerText = reader.result;
			}

			reader.readAsText(file);	
		} else {
			fileDisplayArea.innerText = "File not supported!";
		}
	});

}
function UpdateOpacity(event){
	var value= parseFloat( event.target.value );
	var decimals = 1;
	if (isNaN(value) ){
		value = 0 ;
	}
	if (value > 1){
		value = 1;
	}
	var eventID=  event.target.id;
	var roundedValue = Number( Math.round( value +'e'+ decimals ) +'e-'+ decimals );
	document.getElementById(eventID).value = roundedValue;
	if ( eventID == "UOpacity"){
		document.getElementById("frame1").style.opacity = roundedValue.toString();
	}else if ( eventID  == "fOpacity"){
		document.getElementById("frame2").style.opacity = roundedValue.toString();
	}else{}
}
function UpdateURLContent(urlWebsite){
	var url = urlWebsite;
	if (  document.getElementById("frame1").sandbox == ""){
		document.getElementById("frame1").sandbox = "allow-scripts";
	}
	if (document.getElementById("urlEdit").checked){
		var checkURLForyoutube = (new RegExp('youtube')).test(url);
		if (checkURLForyoutube == true){
			var tempCode="";
			var websiteBase = "https://www.youtube.com/embed/";
			var isPlaylist = 0;
			
			var playlistParam = "list=";
			var checkForPlaylist = url.indexOf(playlistParam);
			if (checkForPlaylist > 0){
				isPlaylist = 1;
				var checkForAdditionalParams = url.indexOf("&",checkForPlaylist );
				if (checkForAdditionalParams > 0){
					tempCode = url.substr(checkForPlaylist + playlistParam.length, checkForAdditionalParams);
				}else{
					tempCode = url.substr(checkForPlaylist + playlistParam.length);
				}
				websiteBase = websiteBase + "videoseries?list=" + tempCode + "&autoplay=1&loop=1";
			}else{
				var videoParam = "v=";
				checkForPlaylist = url.indexOf(videoParam);
				var checkForAdditionalParams = url.indexOf("&",checkForPlaylist );
				if (checkForAdditionalParams > 0){
					tempCode = url.substr(checkForPlaylist + videoParam.length, checkForAdditionalParams);
				}else{
					tempCode = url.substr(checkForPlaylist + videoParam.length);
				}
				websiteBase = websiteBase + tempCode + "?autoplay=1";
			}
			document.getElementById("frame1").src = websiteBase ;
			
			
			//document.getElementById("frame1").sandbox = (new RegExp('allow-scripts')).test(url);
			
		}else{
			if (  document.getElementById("frame1").sandbox != ""){
				document.getElementById("frame1").sandbox = "";
			}
			document.getElementById("frame1").src = url;
		}
	}
}
// When the user clicks the button, open the modal 
function displayModal(event) {
	event.preventDefault();
    	document.getElementById('colorPicker').style.display = "block";
}

// When the user clicks on <span> (x), close the modal
function closeModal() {
    document.getElementById('colorPicker').style.display = "none";
}

function windowSetup(){
	var red = Number( document.getElementById("redRange").value );
	var blue = Number(document.getElementById("blueRange").value );
	var green = Number(document.getElementById("greenRange").value );
	
	document.getElementById('redValue').value = red ;
	document.getElementById('blueValue').value = blue ;
	document.getElementById('greenValue').value = green ;
	var finalHex = "#" + red.toString(16) + green.toString(16) + blue.toString(16);
	
	document.getElementById("frame2").style.color = finalHex;
	document.getElementById("frame1").style.opacity = document.getElementById("UOpacity").value;
	document.getElementById("frame2").style.opacity = document.getElementById("fOpacity").value;
}


// When the user clicks anywhere outside of the modal, close it
function windowClickCloseModal(event) {
	var modal = document.getElementById('colorPicker');
	var modal2 = document.getElementById('fileSaveWarning');
	if (event.target == modal) {
		modal.style.display = "none";
	}else if (event.target == modal2) {
		modal2.style.display = "none";
	}else{}
}

function updateColor(event){
	if (event.target.id == "redRange" ){
		document.getElementById("redValue").value = event.target.value;
	}else if (event.target.id == "blueRange" ){
		document.getElementById("blueValue").value = event.target.value;
	}else{
		document.getElementById("greenValue").value = event.target.value;
	}
	
	var red = Number( document.getElementById("redRange").value );
	var blue = Number(document.getElementById("blueRange").value );
	var green = Number(document.getElementById("greenRange").value );
	var finalHex = "#" + red.toString(16) + green.toString(16) + blue.toString(16);
	
	document.getElementById("frame2").style.color = finalHex;
}
function checkUrlFields(event){
	//check if fields are populataed
	event.preventDefault();
	if ( (document.getElementById("urlEdit").checked) && (document.getElementById("url").value != "" ) ){
		UpdateURLContent(document.getElementById("url").value);
	}
}
function checkFileFields(event){
	event.preventDefault();
	//check if fields are populated
	if ( (document.getElementById("fileInput").value != "") && (document.getElementById("fileEdit").checked)  ){
		document.getElementById('saveFile').value = "";
		document.getElementById('fileSaveWarning').style.display = "block";
	}
}
// When the user clicks the button, open the modal 
function displayModal(event) {
	event.preventDefault();
    	document.getElementById('colorPicker').style.display = "block";
}

function saveFileSetup(event){
	if (event.target.id == "saveWarningYes" ){
		document.getElementById('saveFile').value = "saveFile";
	}else{
		document.getElementById('saveFile').value = "noSaveFile";
	}
	document.getElementById('fileSaveWarning').style.display = "none";
	document.getElementById("forms").submit();
	
	
	
	var file = document.forms['forms']['fileInput'].files[0];
	if (file.type == 'text/plain'){
		download('file text', 'myfilename.txt', 'text/plain')
	
	
	
	
	
	}
	
	
	
	
	
}

/*



<input type="file" id="files" name="files[]" multiple />
<output id="list"></output>

<script>
  function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // files is a FileList of File objects. List some properties.
    var output = [];
    for (var i = 0, f; f = files[i]; i++) {
      output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
                  f.size, ' bytes, last modified: ',
                  f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a',
                  '</li>');
    }
    document.getElementById('list').innerHTML = '<ul>' + output.join('') + '</ul>';
  }

  document.getElementById('files').addEventListener('change', handleFileSelect, false);
</script>

FileReader.readAsText(Blob|File, opt_encoding) - The result property will contain the file/blobs data as a text string. By default the string is decoded as 'UTF-8'. Use the optional encoding parameter can specify a different format.
  function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }

      var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          var span = document.createElement('span');
          span.innerHTML = ['<img class="thumb" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/>'].join('');
          document.getElementById('list').insertBefore(span, null);
        };
      })(f);

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }
  }

  document.getElementById('files').addEventListener('change', handleFileSelect, false);
  
  
  
  
  <script>
  function readBlob(opt_startByte, opt_stopByte) {

    var files = document.getElementById('files').files;
    if (!files.length) {
      alert('Please select a file!');
      return;
    }

    var file = files[0];
    var start = parseInt(opt_startByte) || 0;
    var stop = parseInt(opt_stopByte) || file.size - 1;

    var reader = new FileReader();

    // If we use onloadend, we need to check the readyState.
    reader.onloadend = function(evt) {
      if (evt.target.readyState == FileReader.DONE) { // DONE == 2
        document.getElementById('byte_content').textContent = evt.target.result;
        document.getElementById('byte_range').textContent = 
            ['Read bytes: ', start + 1, ' - ', stop + 1,
             ' of ', file.size, ' byte file'].join('');
      }
    };

    var blob = file.slice(start, stop + 1);
    reader.readAsBinaryString(blob);
  }
  
  
  










*/